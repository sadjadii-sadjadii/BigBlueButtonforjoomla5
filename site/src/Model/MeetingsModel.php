<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class MeetingsModel extends ListModel
{
    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Select meetings that have an active state (1)
        $query->select('*')
              ->from($db->quoteName('#__bbb_bastan_meetings'))
              ->where($db->quoteName('state') . ' = 1');

        return $query;
    }
}