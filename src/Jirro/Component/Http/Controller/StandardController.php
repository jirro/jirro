<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\Controller;

use Jirro\Component\Http\View\ViewAwareInterface;
use Jirro\Component\Http\View\ViewAwareTrait;

class StandardController extends AbstractController implements ViewAwareInterface
{
    use ViewAwareTrait;
}
