<?php

namespace Laraneat\Core\Loaders;

use Illuminate\Contracts\Http\Kernel;

trait MiddlewaresLoaderTrait
{
    public function loadMiddlewares(): void
    {
        $this->registerMiddleware($this->middlewares);
        $this->registerMiddlewareGroups($this->middlewareGroups);
        $this->registerMiddlewarePriority($this->middlewarePriority);
        $this->registerRouteMiddleware($this->routeMiddleware);
    }

    /**
     * Registering Route Groups
     */
    private function registerMiddleware(array $middlewares = []): void
    {
        $httpKernel = $this->app->make(Kernel::class);

        foreach ($middlewares as $middleware) {
            $httpKernel->prependMiddleware($middleware);
        }
    }

    /**
     * Registering Route Groups
     */
    private function registerMiddlewareGroups(array $middlewareGroups = []): void
    {
        foreach ($middlewareGroups as $key => $middleware) {
            if (!is_array($middleware)) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            } else {
                foreach ($middleware as $item) {
                    $this->app['router']->pushMiddlewareToGroup($key, $item);
                }
            }
        }
    }

    /**
     * Registering Route Middlewares priority
     */
    private function registerMiddlewarePriority(array $middlewarePriority = []): void
    {
        foreach ($middlewarePriority as $key => $middleware) {
            if (!in_array($middleware, $this->app['router']->middlewarePriority, true)) {
                $this->app['router']->middlewarePriority[] = $middleware;
            }
        }
    }

    /**
     * Registering Route Middlewares
     */
    private function registerRouteMiddleware(array $routeMiddlewares = []): void
    {
        foreach ($routeMiddlewares as $key => $routeMiddleware) {
            $this->app['router']->aliasMiddleware($key, $routeMiddleware);
        }
    }
}
