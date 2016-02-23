<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\DebugBarBundle\Container\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use DebugBar\StandardDebugBar;
use DebugBar\Bridge\DoctrineCollector;
use DebugBar\DataCollector\TimeDataCollector;
use Jirro\Component\View\Extension\DebugBarExtension;

class DebugBarServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
        'bundle.service.debug_bar',
    ];

    public function register()
    {
        $this->container->add(
            'bundle.service.debug_bar',
            function () {
                $debugBar = new StandardDebugBar();

                $sqlLogger = $this->container->get('db_connection')->getConfiguration()->getSQLLogger();
                $debugBar->addCollector(new DoctrineCollector($sqlLogger));

                $view = $this->container->get('service.view.view');
                if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                    $view->loadExtension(new DebugBarExtension($debugBar));
                }

                return $debugBar;
            }
        );
    }

    public function boot()
    {
    }
}
