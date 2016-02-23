<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Event\Container\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;

class EventServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.event.emitter',
    ];

    public function register()
    {
        $this->container['service.event.emitter'] = function () {
            $emitter = new Emitter();
        };
    }
}
