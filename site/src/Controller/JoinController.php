<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sajadi\Component\BbbBastan\Administrator\Helper\BbbApiHelper;

class JoinController extends BaseController
{
    public function meeting()
    {
        $app = Factory::getApplication();
        
        $id = $app->input->getInt('id', 0);
        $joinPassword = $app->input->getString('join_password', '');
        $joinName = $app->input->getString('join_name', Text::_('COM_BBBBASTAN_DEFAULT_USER_NAME'));

        if (!$id) {
            $app->redirect('index.php?option=com_bbb_bastan&view=meetings');
            return;
        }

        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__bbb_bastan_meetings'))
            ->where($db->quoteName('id') . ' = ' . (int) $id)
            ->where($db->quoteName('state') . ' = 1');
        
        $db->setQuery($query);
        $meeting = $db->loadObject();

        if (!$meeting) {
            $app->enqueueMessage(Text::_('COM_BBBBASTAN_ERROR_MEETING_NOT_FOUND'), 'error');
            $app->redirect('index.php?option=com_bbb_bastan&view=meetings');
            return;
        }

        // --- 1. Determine user role (Moderator or Attendee) ---
        $isModerator = false;
        if ($joinPassword === $meeting->moderator_pw) {
            $rolePassword = $meeting->moderator_pw;
            $isModerator = true;
        } elseif ($joinPassword === $meeting->attendee_pw) {
            $rolePassword = $meeting->attendee_pw;
            $isModerator = false;
        } else {
            $app->enqueueMessage(Text::_('COM_BBBBASTAN_ERROR_INVALID_PASSWORD'), 'error');
            $app->redirect('index.php?option=com_bbb_bastan&view=join&id=' . $id);
            return;
        }

        $api = new BbbApiHelper();
        
        $context = stream_context_create([
            'http' => ['ignore_errors' => true],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);

        $createParams = [
            'name' => $meeting->title,
            'meetingID' => $meeting->meeting_id,
            'attendeePW' => $meeting->attendee_pw,
            'moderatorPW' => $meeting->moderator_pw,
            'record' => ($meeting->record_meeting == 1) ? 'true' : 'false',
            'muteOnStart' => ($meeting->mute_on_start == 1) ? 'true' : 'false'
        ];

        // --- 2. Smart logic for waiting for the moderator ---
        if ($isModerator) {
            $createUrl = $api->generateUrl('create', $createParams);
            @file_get_contents($createUrl, false, $context);
        } else {
            if ($meeting->wait_moderator == 1) {
                $runningUrl = $api->generateUrl('isMeetingRunning', ['meetingID' => $meeting->meeting_id]);
                $runningResponse = @file_get_contents($runningUrl, false, $context);
                
                if ($runningResponse) {
                    $xml = @simplexml_load_string($runningResponse);
                    // Important change: If the class was not created (FAILED) or not running (false)
                    if ($xml && ((string)$xml->returncode === 'FAILED' || (string)$xml->running === 'false')) {
                        $app->enqueueMessage(Text::_('COM_BBBBASTAN_WARNING_WAIT_FOR_MODERATOR'), 'warning');
                        $app->redirect('index.php?option=com_bbb_bastan&view=join&id=' . $id);
                        return;
                    }
                }
            } else {
                $createUrl = $api->generateUrl('create', $createParams);
                @file_get_contents($createUrl, false, $context);
            }
        }

        // --- 3. Build the final join URL ---
        $joinParams = [
            'fullName' => $joinName,
            'meetingID' => $meeting->meeting_id,
            'password' => $rolePassword, 
            'redirect' => 'true'
        ];
        
        $joinUrl = $api->generateUrl('join', $joinParams);
        
        // --- 4. Log the entry into the database (with exit time +1 hour) ---
        $userId = Factory::getApplication()->getIdentity()->id;
        $now = Factory::getDate();
        $joinTime = $now->toSql();
        $now->modify('+1 hour');
        $leaveTime = $now->toSql();
        
        $logQuery = $db->getQuery(true);
        $logQuery->insert($db->quoteName('#__bbb_bastan_logs'))
                 ->columns([
                     $db->quoteName('meeting_id'), 
                     $db->quoteName('user_id'), 
                     $db->quoteName('join_time'),
                     $db->quoteName('leave_time')
                 ])
                 ->values(
                     (int) $id . ', ' . 
                     (int) $userId . ', ' . 
                     $db->quote($joinTime) . ', ' .
                     $db->quote($leaveTime)
                 );
                 
        $db->setQuery($logQuery);
        $db->execute();
        
        // --- 5. Redirect the student or moderator to the class ---
        $app->redirect($joinUrl);
    }
}