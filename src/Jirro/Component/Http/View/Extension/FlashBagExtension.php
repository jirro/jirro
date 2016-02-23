<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\View\Extension;

use League\Plates\Engine as View;
use League\Plates\Extension\ExtensionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Request;

class FlashBagExtension implements ExtensionInterface
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->setFlashBag($flashBag);
    }

    public function setFlashBag(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function getFlashBag()
    {
        return $this->flashBag;
    }

    public function register(View $view)
    {
        $view->registerFunction('flashBag', [$this, 'flashBag']);
    }

    public function flashBag()
    {
        return $this->flashBag;
    }
}
