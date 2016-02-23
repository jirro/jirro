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
use Symfony\Component\HttpFoundation\Request;

class RequestExtension implements ExtensionInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->setRequest($request);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function register(View $view)
    {
        $view->registerFunction('request', [$this, 'request']);
    }

    public function request()
    {
        return $this->request;
    }
}
