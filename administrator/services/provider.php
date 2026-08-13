<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Sajadi\Component\BbbBastan\Administrator\Extension\BbbBastanComponent;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Sajadi\\Component\\BbbBastan'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Sajadi\\Component\\BbbBastan'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new BbbBastanComponent($container->get(ComponentDispatcherFactoryInterface::class));
                
                // Set the correct MVC factory for Joomla 5 compatibility
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};