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

class AssetUrlExtension implements ExtensionInterface
{
    private $assetBaseUrl;

    public function __construct($assetBaseUrl)
    {
        $this->setAssetBaseUrl($assetBaseUrl);
    }

    public function setAssetBaseUrl($assetBaseUrl)
    {
        $this->assetBaseUrl = $assetBaseUrl;
    }

    public function getAssetBaseUrl()
    {
        return $this->assetBaseUrl;
    }

    public function register(View $view)
    {
        $view->registerFunction('assetUrl', [$this, 'assetUrl']);
    }

    public function assetUrl($url = null)
    {
        if ($url === null) {
            return $this->assetBaseUrl;
        }

        return $this->assetBaseUrl . $url;
    }
}
