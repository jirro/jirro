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
use DebugBar\StandardDebugBar as DebugBar;

class DebugBarExtension implements ExtensionInterface
{
    private $debugBar;

    public function __construct(DebugBar $debugBar)
    {
        $this->setDebugBar($debugBar);
    }

    public function setDebugBar(DebugBar $debugBar)
    {
        $this->debugBar = $debugBar;
    }

    public function getDebugBar()
    {
        return $this->debugBar;
    }

    public function register(View $view)
    {
        $view->registerFunction('debugBar', [$this, 'debugBar']);
    }

    public function debugBar()
    {
        $renderer = $this
            ->debugBar
            ->getJavascriptRenderer()
            ->setBaseUrl('/assets/debug_bar/')
        ;

        return $renderer;
    }
}
