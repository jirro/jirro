<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Kernel;

use League\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpApplication implements HttpKernelInterface
{
    private $container;

    public function __construct(array $config)
    {
        $this->container = new Container();

        $this->initEventEmitter();
        $this->initConfig($config);
        $this->registerServices();
    }

    private function initEventEmitter()
    {
        $this
            ->container
            ->addServiceProvider('Jirro\Component\Event\Container\ServiceProvider\EventServiceProvider')
            ->inflector('Jirro\Component\Event\EmitterAwareInterface')
            ->invokeMethod('setEmitter', ['service.event.emitter'])
        ;
    }

    private function initConfig($config)
    {
        $this->container->add('config', $config);

        $this->initConfigFromBundles();
    }

    private function initConfigFromBundles()
    {
        $this
            ->container
            ->addServiceProvider('Jirro\Component\Bundle\Container\ServiceProvider\BundleServiceProvider')
        ;

        $config = $this->container->get('config');

        $config['services']           = [];
        $config['orm']['mappings']    = [];
        $config['orm']['validators']  = [];
        $config['http']['views_path'] = [];
        $config['http']['routes']     = [];

        $bundleContainer = $this->container->get('service.bundle.container');
        foreach ($config['bundles'] as $name => $namespace) {
            $bundleContainer->register($name, $namespace);

            $bundle       = $bundleContainer->get($name);
            $bundleConfig = $bundle->getConfig();

            // attach bundle services into config
            if (isset($bundleConfig['services'])) {
                $config['services'] = array_merge(
                    $config['services'],
                    $bundleConfig['services']
                );
            }

            // attach bundles ORM mappings into config
            if (isset($bundleConfig['orm']['mappings'])) {
                $config['orm']['mappings'] = array_merge(
                    $config['orm']['mappings'],
                    $bundleConfig['orm']['mappings']
                );
            }

            // attach bundles ORM validators into config
            if (isset($bundleConfig['orm']['validators'])) {
                $config['orm']['validators'] = array_merge(
                    $config['orm']['validators'],
                    $bundleConfig['orm']['validators']
                );
            }

            // attach bundles route collections into config
            if (isset($bundleConfig['http']['routes'])) {
                $config['http']['routes'] = array_merge(
                    $config['http']['routes'],
                    $bundleConfig['http']['routes']
                );
            }

            // attach bundles views path into config
            if (isset($bundleConfig['http']['views_path'])) {
                $config['http']['views_path'][$name] = $bundleConfig['http']['views_path'];
            }
        }

        $this->container->add('config', $config);
    }

    private function registerServices()
    {
        // register DBAL components
        $this
            ->container
            ->addServiceProvider('Jirro\Component\DBAL\Container\ServiceProvider\DBALServiceProvider')
        ;

        // register connection inflector
        $this
            ->container
            ->inflector('Jirro\Component\DBAL\Connection\ConnectionAwareInterface')
            ->invokeMethod('setConnection', ['service.dbal.connection'])
        ;

        // register ORM components
        $this
            ->container
            ->addServiceProvider('Jirro\Component\ORM\Container\ServiceProvider\ORMServiceProvider')
        ;

        // register object manager inflector
        $this
            ->container
            ->inflector('Jirro\Component\ORM\ObjectManager\ObjectManagerAwareInterface')
            ->invokeMethod('setObjectManager', ['service.orm.object_manager']);

        // register validator inflector
        $this
            ->container
            ->inflector('Jirro\Component\ORM\Validator\ValidatorAwareInterface')
            ->invokeMethod('setValidator', ['service.orm.validator'])
        ;

        // register HTTP components
        $this
            ->container
            ->addServiceProvider('Jirro\Component\Http\Container\ServiceProvider\HttpServiceProvider')
        ;

        // register route inflector
        $this
            ->container
            ->inflector('Jirro\Component\Http\RouteAwareInterface')
            ->invokeMethod('setRoute', ['service.http.route'])
        ;

        // register account components
        $this
            ->container
            ->addServiceProvider('Jirro\Component\Account\Container\ServiceProvider\AccountServiceProvider')
        ;

        // register auth inflector
        $this
            ->container
            ->inflector('Jirro\Component\Account\Auth\AuthAwareInterface')
            ->invokeMethod('setAuth', ['service.account.auth'])
        ;

        // register file system components
        $this
            ->container
            ->addServiceProvider('Jirro\Component\FileSystem\Container\ServiceProvider\FileSystemServiceProvider')
        ;

        // register file system inflector
        $this
            ->container
            ->inflector('Jirro\Component\FileSystem\FileSystemAwareInterface')
            ->invokeMethod('setFileSystem', ['file_system'])
        ;

        // register bundle services
        foreach ($this->container->get('config')['services'] as $service) {
            $this->container->addServiceProvider($service);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->container->add('service.http.request', $request);

        $route      = $this->container->get('service.http.route');
        $dispatcher = $route->getDispatcher();
        $response   = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $response;
    }
}
