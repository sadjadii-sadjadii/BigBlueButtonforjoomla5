<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\View\Logs;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        ToolbarHelper::title(Text::_('COM_BBBBASTAN_VIEW_LOGS_TITLE'), 'chart');
        ToolbarHelper::preferences('com_bbb_bastan');

        parent::display($tpl);
    }
}