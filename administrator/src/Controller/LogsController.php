<?php
namespace Sajadi\Component\BbbBastan\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class LogsController extends AdminController
{
    public function getModel($name = 'Log', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }
}