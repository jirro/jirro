<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http;

use Closure;
use RuntimeException;
use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Container\ContainerInterface;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\UnauthorizedException;
use League\Route\Strategy\RequestResponseStrategy;
use League\Route\Strategy\RestfulStrategy;
use League\Route\Strategy\StrategyInterface;
use League\Route\Strategy\StrategyTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Jirro\Component\Account\Auth;

class Dispatcher extends GroupCountBasedDispatcher implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    use StrategyTrait;

    protected $container;

    protected $request;

    protected $routes;

    protected $auth;

    public function __construct(ContainerInterface $container, array $routes, array $data)
    {
        $this->container = $container;
        $this->request   = $container->get('service.http.request');
        $this->routes    = $routes;
        $this->auth      = $container->get('service.account.auth');

        parent::__construct($data);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function dispatch($method, $uri)
    {
        $routes = $this->container->get('config')['http']['routes'];

        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri .= '/';
        }

        $matchedRoute    = parent::dispatch($method, $uri);
        $hasMatchedRoute = false;
        $anonymous       = false;

        if ($matchedRoute[0] === Dispatcher::NOT_FOUND) {
            return $this->handleRouteNotFound();
        }

        if ($matchedRoute) {
            foreach ($routes as $key => $route) {
                if (
                    $matchedRoute[0] === Dispatcher::METHOD_NOT_ALLOWED
                    && $route['path'] === $this->request->getPathInfo()
                ) {
                    if (isset($route['strategy'])) {
                        switch ($route['strategy']) {
                            case Route::STRATEGY_RESTFUL:
                                $this->setStrategy(new RestfulStrategy());
                                break;
                            case Route::STRATEGY_REQUEST_RESPONSE:
                            default:
                                $this->setStrategy(new RequestResponseStrategy());
                        }
                    } else {
                        $this->setStrategy(new RequestResponseStrategy());
                    }
                } elseif (
                    $matchedRoute[0] === Dispatcher::FOUND
                    && $route['path'] === $this->request->getPathInfo()
                    && $route['method'] === $this->request->getMethod()
                    && $route['target'] === $matchedRoute[1]
                ) {
                    if (isset($route['anonymous'])) {
                        $anonymous = $route['anonymous'];
                    }

                    if (isset($route['strategy'])) {
                        switch ($route['strategy']) {
                            case Route::STRATEGY_RESTFUL:
                                $this->setStrategy(new RestfulStrategy());
                                break;
                            case Route::STRATEGY_REQUEST_RESPONSE:
                            default:
                                $this->setStrategy(new RequestResponseStrategy());
                        }
                    } else {
                        $this->setStrategy(new RequestResponseStrategy());
                    }

                    break;
                }
            }
        }

        if ($matchedRoute[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->handleMethodNotAllowed();
        }

        $handler = (isset($this->routes[$matchedRoute[1]]['callback']))
            ? $this->routes[$matchedRoute[1]]['callback']
            : $matchedRoute[1];

        $vars = (array) $matchedRoute[2];

        if (! $anonymous) {
            $this->auth->authenticate(Auth::METHOD_TOKEN);
            if (! $this->auth->hasAuthenticatedUser()) {
                return $this->handleUnauthenticated();
            } elseif (! $this->auth->isAuthenticatedUserAuthorized($handler)) {
                return $this->handleUnauthorized();
            }
        }

        return $this->handleResponse($handler, $this->getStrategy(), $vars);
    }

    public function handleResponse($handler, StrategyInterface $strategy, array $vars)
    {
        if (is_null($this->getStrategy())) {
            $this->setStrategy($strategy);
        }

        $controller = null;

        // figure out what the controller is
        if (($handler instanceof Closure) || is_callable($handler)) {
            $controller = $handler;
        }

        if (is_string($handler) && strpos($handler, '::') !== false) {
            $controller = explode('::', $handler);
        }

        // if controller method wasn't specified, throw exception.
        if (! $controller) {
            throw new RuntimeException('A class method must be provided as a controller. ClassName::methodName');
        }

        // inject container to strategy
        if ($strategy instanceof ContainerAwareInterface) {
            $strategy->setContainer($this->container);
        }

        // dispatch via strategy
        return $strategy->dispatch($controller, $vars);
    }

    public function handleRouteNotFound()
    {
        // @todo add route not found view

        $exception = new NotFoundException();

        if ($this->getStrategy() instanceof RestfulStrategy) {
            return $exception->getJsonResponse();
        }

        throw $exception;
    }

    public function handleMethodNotAllowed()
    {
        $exception = new MethodNotAllowedException();

        if ($this->getStrategy() instanceof RestfulStrategy) {
            return $exception->getJsonResponse();
        }

        throw $exception;
    }

    public function handleUnauthenticated()
    {
        $message = 'You are not authenticated!';

        if ($this->getStrategy() instanceof RestfulStrategy) {
            $exception = new UnauthorizedException($message);

            return $exception->getJsonResponse();
        }

        return new RedirectResponse($this->container->get('request')->getBasePath() . '/account/login');
    }

    public function handleUnauthorized()
    {
        $message = 'You are not authorized!';

        if ($this->getStrategy() instanceof RestfulStrategy) {
            $exception = new UnauthorizedException($message);

            return $exception->getJsonResponse();
        }

        return new Response(
            $this->container->get('view')->render(
                'Admin::exception/unauthorized',
                ['message' => $message]
            ),
            403
        );
    }
}
