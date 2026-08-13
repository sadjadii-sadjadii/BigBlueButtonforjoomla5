<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
    // Set the default view of the component in the administrator section to 'meetings'
    protected $default_view = 'meetings';
    
    public function display($cachable = false, $urlparams = [])
    {
        return parent::display($cachable, $urlparams);
    }
}