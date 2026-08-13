<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class MeetingTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        // اتصال به جدولی که قبلا در SQL ساختیم
        parent::__construct('#__bbb_bastan_meetings', 'id', $db);
    }
}