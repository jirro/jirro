<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\Route;

use Jirro\Component\Http\Route;

trait RouteAwareTrait
{
    protected $route;

    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }
}
