<?php

namespace Laraneat\Core\Providers;

use Laraneat\Core\Abstracts\Events\Providers\EventServiceProvider;
use Laraneat\Core\Abstracts\Providers\MainProvider as AbstractMainProvider;
use Laraneat\Core\Foundation\Laraneat;
use Laraneat\Core\Loaders\AutoLoaderTrait;
use Laraneat\Core\Traits\ValidationTrait;
use Illuminate\Support\Facades\App;

class LaraneatProvider extends AbstractMainProvider
{
    use AutoLoaderTrait;
    use ValidationTrait;

    public function boot(): void
    {
        parent::boot();

        $this->offerPublishing();

        // Autoload most of the Containers and Ship Components
        $this->runLoadersBoot();

        // Registering custom validation rules
        $this->extendValidationRules();
    }

    public function register(): void
    {
        parent::register();

        $this->mergeConfig();

        $this->overrideLaravelBaseProviders();

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

    /**
     * Register Override Base providers
     * @see \Illuminate\Foundation\Application::registerBaseServiceProviders
     */
    protected function overrideLaravelBaseProviders(): void
    {
        // The custom Laraneat EventServiceProvider
        App::register(EventServiceProvider::class);
    }
}
