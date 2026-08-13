<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Site\View\Meetings;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Sajadi\Component\BbbBastan\Administrator\Helper\BbbApiHelper;

class HtmlView extends BaseHtmlView
{
    protected $items;
    public $runningMeetings = []; // Declared publicly to be accessible in the template

    public function display($tpl = null)
    {
        // Get the list of classes from the Model
        $this->items = $this->get('Items');

        // --- Add logic to detect online classes ---
        $this->runningMeetings = []; // Default array for running classes

        try {
            $api = new BbbApiHelper();
            $getMeetingsUrl = $api->generateUrl('getMeetings');
            
            // Connection settings (3-second timeout to prevent Joomla from hanging if BBB server is down)
            $context = stream_context_create([
                'http' => ['ignore_errors' => true, 'timeout' => 3],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);
            
            $response = @file_get_contents($getMeetingsUrl, false, $context);
            
            if ($response) {
                $xml = @simplexml_load_string($response);
                if ($xml && (string)$xml->returncode === 'SUCCESS' && isset($xml->meetings->meeting)) {
                    // Extract Meeting ID of all running classes
                    foreach ($xml->meetings->meeting as $m) {
                        $this->runningMeetings[] = (string)$m->meetingID;
                    }
                }
            }
        } catch (\Exception $e) {
            // Prevent site disruption if a server connection error occurs
        }
        // --- End of online classes logic ---

        parent::display($tpl);
    }
}