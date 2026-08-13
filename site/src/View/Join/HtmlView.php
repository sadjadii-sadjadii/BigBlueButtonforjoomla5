<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Site\View\Join;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class HtmlView extends BaseHtmlView
{
    protected $item;

    public function display($tpl = null)
    {
        $app = Factory::getApplication();
        $id = $app->input->getInt('id', 0);

        // Fetch class information to display its name at the top of the form
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__bbb_bastan_meetings'))
            ->where($db->quoteName('id') . ' = ' . (int) $id)
            ->where($db->quoteName('state') . ' = 1');
        
        $db->setQuery($query);
        $this->item = $db->loadObject();

        if (!$this->item) {
            $app->enqueueMessage(Text::_('COM_BBBBASTAN_ERROR_MEETING_NOT_FOUND'), 'error');
            $app->redirect('index.php?option=com_bbb_bastan&view=meetings');
            return;
        }

        parent::display($tpl);
    }
}