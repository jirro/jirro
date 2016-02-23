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
    // home
    [
        'path'      => '/',
        'method'    => 'GET',
        'target'    => 'Jirro\Bundle\FrontBundle\Controllers\HomeController::indexAction',
        'anonymous' => true,
    ],
];
