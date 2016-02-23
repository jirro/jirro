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

class BaseUrlExtension implements ExtensionInterface
{
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->setBaseUrl($baseUrl);
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function register(View $view)
    {
        $view->registerFunction('baseUrl', [$this, 'baseUrl']);
    }

    public function baseUrl($url = null)
    {
        if (null === $url) {
            return $this->baseUrl;
        }

        return $this->baseUrl . $url;
    }
}
