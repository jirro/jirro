<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\View;

use League\Plates\Engine as View;

interface ViewAwareInterface
{
    public function setView(View $view);

    public function getView();
}
