<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jirro\Component\Http\Route;

return [
    // account/login
    [
        'path'      => '/api/account/login',
        'method'    => 'POST',
        'target'    => 'Jirro\Bundle\RestfulBundle\Controllers\AuthenticationController::loginAction',
        'strategy'  => Route::STRATEGY_RESTFUL,
        'anonymous' => true,
    ],


    // admin/resources
    [
        'path'      => '/api/admin/resources',
        'method'    => 'GET',
        'target'    => 'Jirro\Bundle\RestfulBundle\Controllers\ResourcesController::indexAction',
        'strategy'  => Route::STRATEGY_RESTFUL,
    ],
];
