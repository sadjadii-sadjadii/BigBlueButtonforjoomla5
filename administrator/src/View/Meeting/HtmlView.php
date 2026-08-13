<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\View\Meeting;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        // If the ID is zero, it's a new class; otherwise, it's an edit
        $isNew = ($this->item->id == 0);
        ToolbarHelper::title($isNew ? Text::_('COM_BBBBASTAN_VIEW_MEETING_TITLE_NEW') : Text::_('COM_BBBBASTAN_VIEW_MEETING_TITLE_EDIT'), 'video-camera');
        
        // Save and close buttons
        ToolbarHelper::apply('meeting.apply');
        ToolbarHelper::save('meeting.save');
        ToolbarHelper::cancel('meeting.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}