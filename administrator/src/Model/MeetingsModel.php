<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class MeetingsModel extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            // 'created' removed as it does not exist in the database schema
            $config['filter_fields'] = ['id', 'title', 'state'];
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        // Connect to the database and fetch the list of meetings
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('*')
              ->from($db->quoteName('#__bbb_bastan_meetings'));

        // Searching
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('(' . $db->quoteName('title') . ' LIKE ' . $search . 
                          ' OR ' . $db->quoteName('meeting_id') . ' LIKE ' . $search . ')');
        }
        return $query;
    }
}