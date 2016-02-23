<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Bundle\Container\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Jirro\Component\Bundle\BundleContainer;

class BundleServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.bundle.container',
    ];

    public function register()
    {
        $this->container->add(
            'service.bundle.container',
            function () {
                $bundleContainer = new BundleContainer();

                return $bundleContainer;
            }
        );
    }
}
