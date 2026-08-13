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

        if (!parent::save($data)) {
            return false;
        }

        if ($data['state'] == 0) {
            return true;
        }

        $api = new BbbApiHelper();

        // Get Yes/No settings from the Joomla form and convert for BBB
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
            $context = stream_context_create([
                'http' => ['ignore_errors' => true],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);

            $responseXml = @file_get_contents($url, false, $context);
            
            if ($responseXml) {
                $response = @simplexml_load_string($responseXml);
                
                if ($response && isset($response->returncode) && $response->returncode == 'SUCCESS') {
                    $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_MEETING_CREATED_SUCCESS'), 'success');
                } else {
                    $errorMsg = $response ? (string) $response->messageKey . ': ' . (string) $response->message : Text::_('COM_BBBBASTAN_MSG_INVALID_SERVER_RESPONSE');
                    $app->enqueueMessage(Text::_('COM_BBBBASTAN_MSG_BBB_SERVER_RESPONSE') . ' ' . $errorMsg, 'warning');
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
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__bbb_bastan_meetings'))
                ->where($db->quoteName('id') . ' = ' . (int) $pk);
            $db->setQuery($query);
            $meeting = $db->loadObject();

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
        
        return parent::delete($pks);
    }
}