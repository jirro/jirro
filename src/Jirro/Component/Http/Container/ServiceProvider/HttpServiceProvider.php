<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\Container\ServiceProvider;

use Jirro\Bundle\AccountBundle\Domain\User;
use Jirro\Component\Http\Route;
use Jirro\Component\Http\View\Extension\AssetUrlExtension;
use Jirro\Component\Http\View\Extension\AuthenticatedUserExtension;
use Jirro\Component\Http\View\Extension\BaseUrlExtension;
use Jirro\Component\Http\View\Extension\FlashBagExtension;
use Jirro\Component\Http\View\Extension\RequestExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Plates\Engine as View;

class HttpServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.http.request',
        'service.http.route',
        'service.http.view',
        'Symfony\Component\HttpFoundation\Request',
    ];

    public function register()
    {
        $this->container->add(
            'service.http.request',
             function () {
                return Request::createFromGlobals();
            }
        );

        $this->container->add(
            'service.http.route',
            function () {
                $route = new Route($this->container);

                foreach ($this->container->get('config')['http']['routes'] as $item) {
                    $route->addRoute(strtoupper($item['method']), $item['path'], $item['target']);
                }

                return $route;
            }
        );

        $this->container->add(
            'Symfony\Component\HttpFoundation\Request',
            function () {
                return $this->container->get('service.http.request');
            }
        );

        $this->container->add(
            'service.view.view',
            function () {
                $view = new View();
                foreach ($this->container->get('config')['http']['views_path'] as $name => $path) {
                    $view->addFolder($name, $path);
                }

                $request = $this->container->get('service.http.request');
                $view->loadExtension(new RequestExtension($request));
                $view->loadExtension(new FlashBagExtension($request->getSession()->getFlashBag()));
                $view->loadExtension(new BaseUrlExtension($request->getBasePath()));

                $assetBaseUrl = $this->container->get('config')['asset_base_url'];
                $view->loadExtension(new AssetUrlExtension($assetBaseUrl));

                if ($this->container->get('service.account.auth')->hasAuthenticatedUser()) {
                    $authenticatedUser = $this->container->get('service.account.auth')->getAuthenticatedUser();
                    $view->loadExtension(new AuthenticatedUserExtension($authenticatedUser));
                }

                $view->addData(['applicationName' => $this->container->get('config')['application_name']]);

                return $view;
            }
        );
    }
}
