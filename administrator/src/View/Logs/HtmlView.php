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
    public $items;
    public $pagination;
    public $state;
    public $filterForm;
    public $activeFilters;

    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Call the toolbar
        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        // Title for the logs page
        ToolbarHelper::title(Text::_('COM_BBBBASTAN_VIEW_LOGS_TITLE'), 'list');
        
        // Only the delete and preferences buttons are needed on the logs page
        ToolbarHelper::deleteList(Text::_('COM_BBBBASTAN_CONFIRM_DELETE'), 'logs.delete', 'JTOOLBAR_DELETE');
        ToolbarHelper::preferences('com_bbb_bastan');
    }
}