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
    'orm' => [
        'mappings' => [
            __DIR__ . '/../orm/mappings',
        ],
        'validators' => [
            __DIR__ . '/../orm/validators/Jirro.Bundle.AccountBundle.Validator.Group.dcm.xml',
        ],
    ],
    'http' => [
        'routes' => require __DIR__ . '/routes.php',
        'views_path'       => __DIR__ . '/../../Views',
    ],
];
