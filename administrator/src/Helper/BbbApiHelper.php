<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;

class BbbApiHelper
{
    private $bbbUrl;
    private $bbbSecret;

    public function __construct()
    {
        $params = ComponentHelper::getParams('com_bbb_bastan');
        
        // 1. Get the URL and remove spaces or trailing slashes
        $url = trim($params->get('bbb_url'));
        $url = rtrim($url, '/');
        
        // 2. Smart correction: If the URL does not end with 'api', add it
        if (!str_ends_with(strtolower($url), 'api')) {
            $url .= '/api';
        }
        
        // 3. Add a trailing slash for standardization
        $this->bbbUrl = $url . '/';
        
        // Remove potential spaces from the beginning and end of the secret key
        $this->bbbSecret = trim($params->get('bbb_secret'));
    }

    public function generateUrl($action, $params = [])
    {
        $queryString = http_build_query($params);
        $checksum = sha1($action . $queryString . $this->bbbSecret);
        $finalQuery = $queryString . (empty($queryString) ? '' : '&') . 'checksum=' . $checksum;
        
        return $this->bbbUrl . $action . '?' . $finalQuery;
    }
}