<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class LogsModel extends ListModel
{
    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Fetch logs and join with meetings and users tables to retrieve names
        $query->select('a.*, m.title as meeting_title, u.name as user_name')
              ->from($db->quoteName('#__bbb_bastan_logs', 'a'))
              ->leftJoin($db->quoteName('#__bbb_bastan_meetings', 'm') . ' ON a.meeting_id = m.id')
              ->leftJoin($db->quoteName('#__users', 'u') . ' ON a.user_id = u.id')
              ->order($db->quoteName('a.join_time') . ' DESC');

        // Searching
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('(' . $db->quoteName('meeting_name') . ' LIKE ' . $search . 
                          ' OR ' . $db->quoteName('user_name') . ' LIKE ' . $search . ')');
        }
        return $query;
    }
}