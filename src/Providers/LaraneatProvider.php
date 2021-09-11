<?php

namespace Laraneat\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Laraneat\Core\Foundation\Laraneat;
use Laraneat\Core\Loaders\AutoLoaderTrait;
use Laraneat\Core\Traits\ValidationTrait;

class LaraneatProvider extends ServiceProvider
{
    use AutoLoaderTrait;
    use ValidationTrait;

    public function boot(): void
    {
        $this->offerPublishing();

        // Autoload most of the Containers and Ship Components
        $this->runLoadersBoot();

        // Registering custom validation rules
        $this->extendValidationRules();
    }

    public function register(): void
    {
        $this->mergeConfig();

        // Register Core Facade Classes, should not be registered in the $aliases property, since they are used
        // by the auto-loading scripts, before the $aliases property is executed.
        $this->app->alias(Laraneat::class, 'Laraneat');
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/laraneat.php',
            'laraneat'
        );
    }

    protected function offerPublishing(): void
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../../config/laraneat.php' => config_path('laraneat.php'),
        ], 'config');
    }
}
