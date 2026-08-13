<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sajadi\Component\BbbBastan\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Psr\Container\ContainerInterface;

class BbbBastanComponent extends MVCComponent implements BootableExtensionInterface
{
    public function boot(ContainerInterface $container): void
    {
        // Code to be executed before anything else in the component is loaded goes here
    }
}