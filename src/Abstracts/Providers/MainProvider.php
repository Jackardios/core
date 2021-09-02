<?php

namespace Laraneat\Core\Abstracts\Providers;

use Laraneat\Core\Loaders\AliasesLoaderTrait;
use Laraneat\Core\Loaders\ProvidersLoaderTrait;
use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;

abstract class MainProvider extends LaravelAppServiceProvider
{
    use ProvidersLoaderTrait;
    use AliasesLoaderTrait;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->loadServiceProviders();
        $this->loadAliases();
    }

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
    }
}
