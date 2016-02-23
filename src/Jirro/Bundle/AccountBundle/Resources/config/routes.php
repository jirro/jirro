<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    // login
    [
        'path'      => '/account/login',
        'method'    => 'GET',
        'target'    => 'Jirro\Bundle\AccountBundle\Controllers\AuthenticationController::loginAction',
        'anonymous' => true,
    ],
    [
        'path'      => '/account/login',
        'method'    => 'POST',
        'target'    => 'Jirro\Bundle\AccountBundle\Controllers\AuthenticationController::loginAction',
        'anonymous' => true,
    ],

    // logout
    [
        'path'      => '/account/logout',
        'method'    => 'GET',
        'target'    => 'Jirro\Bundle\AccountBundle\Controllers\AuthenticationController::logoutAction',
        'anonymous' => true,
    ],
];
