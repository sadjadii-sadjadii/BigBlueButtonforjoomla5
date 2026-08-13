<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\View\Meetings;

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
        // Get data from the Model
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Add toolbar buttons
        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('COM_BBBBASTAN_VIEW_MEETINGS_TITLE'), 'video-camera');
        ToolbarHelper::addNew('meeting.add', 'JTOOLBAR_NEW');
        ToolbarHelper::editList('meeting.edit', 'JTOOLBAR_EDIT');
        ToolbarHelper::deleteList(Text::_('COM_BBBBASTAN_CONFIRM_DELETE'), 'meetings.delete', 'JTOOLBAR_DELETE');
        ToolbarHelper::preferences('com_bbb_bastan');
    }
}