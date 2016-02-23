<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\FrontBundle;

use Jirro\Component\Bundle\Bundle as BaseBundle;

class FrontBundle extends BaseBundle
{
    public function getConfig()
    {
        return require __DIR__ . '/Resources/config/config.php';
    }
}
