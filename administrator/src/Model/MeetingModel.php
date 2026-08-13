<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sajadi\Component\BbbBastan\Administrator\Helper\BbbApiHelper;

class MeetingModel extends AdminModel
{
    protected $option = 'com_bbb_bastan';

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_bbb_bastan.meeting', 'meeting', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_bbb_bastan.edit.meeting.data', []);
        
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    public function save($data)
    {
        $app = Factory::getApplication();

        // Save the data to the Joomla database first
        if (!parent::save($data)) {
            return false;
        }

        // If the meeting state is unpublished (0), do not send a create request to BBB
        if ($data['state'] == 0) {
            return true;
        }

        $api = new BbbApiHelper();

        // Get Yes/No settings from the Joomla form and convert for the BBB API
        $params = [
            'name' => $data['title'],
            'meetingID' => $data['meeting_id'],
            'attendeePW' => $data['attendee_pw'],
            'moderatorPW' => $data['moderator_pw'],
            'record' => (isset($data['record_meeting']) && $data['record_meeting'] == 1) ? 'true' : 'false',
            'waitForModerator' => (isset($data['wait_moderator']) && $data['wait_moderator'] == 1) ? 'true' : 'false',
            'muteOnStart' => (isset($data['mute_on_start']) && $data['mute_on_start'] == 1) ? 'true' : 'false'
        ];

        $url = $api->generateUrl('create', $params);

        try {
            // Setup stream context to ignore HTTP errors and bypass SSL verification
            $context = stream_context_create([
                'http' => ['ignore_errors' => true],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);

            $responseXml = @file_get_contents($url, false, $context);
            
            if ($responseXml) {
                $response = @simplexml_load_string($responseXml);
                
                if ($response && isset($response->returncode)) {
                    if ((string) $response->returncode === 'SUCCESS') {
                        // Successfully created a new meeting instance on the server
                        $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_MEETING_CREATED_SUCCESS'), 'success');
                    } else {
                        $messageKey = (string) $response->messageKey;
                        
                        // Check if the warning is strictly about the meeting already running
                        if ($messageKey === 'idNotUnique') {
                            // Ignore this warning: the meeting is already active on the BBB server.
                            // Since the database update was successful, no further action is needed.
                        } else {
                            // Display actual BBB server errors to the administrator
                            $errorMsg = $messageKey . ': ' . (string) $response->message;
                            $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_BBB_SERVER_RESPONSE') . ' ' . $errorMsg, 'warning');
                        }
                    }
                } else {
                    $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_INVALID_SERVER_RESPONSE'), 'warning');
                }
            } else {
                $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_NETWORK_CONNECTION_FAILED'), 'error');
            }
        } catch (\Exception $e) {
            $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_REQUEST_EXECUTION_ERROR') . ' ' . $e->getMessage(), 'error');
        }

        return true;
    }

    public function delete(&$pks)
    {
        $api = new BbbApiHelper();
        $db = $this->getDatabase();

        foreach ($pks as $pk) {
            // Retrieve the meeting details from the database before deleting
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__bbb_bastan_meetings'))
                ->where($db->quoteName('id') . ' = ' . (int) $pk);
            $db->setQuery($query);
            $meeting = $db->loadObject();

            // If the meeting exists, send an 'end' request to the BBB server
            if ($meeting) {
                $endParams = [
                    'meetingID' => $meeting->meeting_id,
                    'password' => $meeting->moderator_pw
                ];
                $endUrl = $api->generateUrl('end', $endParams);
                
                $context = stream_context_create([
                    'http' => ['ignore_errors' => true],
                    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
                ]);
                @file_get_contents($endUrl, false, $context);
            }
        }
        
        // Delete the records from the Joomla database
        return parent::delete($pks);
    }
}