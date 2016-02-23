<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\DBAL\Container\ServiceProvider;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\DebugStack;
use League\Container\ServiceProvider\AbstractServiceProvider;

class DBALServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.dbal.connection',
    ];

    public function register()
    {
        $this->container->add(
            'service.dbal.connection',
             function () {
                $connectionParams = $this->container->get('config')['dbal']['connection']['params'];

                $config = new Configuration();
                $config->setSQLLogger(new DebugStack());

                return DriverManager::getConnection($connectionParams, $config);
            }
        );
    }
}
