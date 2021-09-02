<?php

namespace Laraneat\Core\Generator;

use Laraneat\Core\Generator\Commands\ActionGenerator;
use Laraneat\Core\Generator\Commands\ConfigurationGenerator;
use Laraneat\Core\Generator\Commands\ContainerApiGenerator;
use Laraneat\Core\Generator\Commands\ContainerGenerator;
use Laraneat\Core\Generator\Commands\ContainerWebGenerator;
use Laraneat\Core\Generator\Commands\ControllerGenerator;
use Laraneat\Core\Generator\Commands\EventGenerator;
use Laraneat\Core\Generator\Commands\EventHandlerGenerator;
use Laraneat\Core\Generator\Commands\ExceptionGenerator;
use Laraneat\Core\Generator\Commands\FactoryGenerator;
use Laraneat\Core\Generator\Commands\JobGenerator;
use Laraneat\Core\Generator\Commands\MailGenerator;
use Laraneat\Core\Generator\Commands\MigrationGenerator;
use Laraneat\Core\Generator\Commands\ModelGenerator;
use Laraneat\Core\Generator\Commands\NotificationGenerator;
use Laraneat\Core\Generator\Commands\PolicyGenerator;
use Laraneat\Core\Generator\Commands\QueryWizardGenerator;
use Laraneat\Core\Generator\Commands\ReadmeGenerator;
use Laraneat\Core\Generator\Commands\RequestGenerator;
use Laraneat\Core\Generator\Commands\ResourceGenerator;
use Laraneat\Core\Generator\Commands\RouteGenerator;
use Laraneat\Core\Generator\Commands\SeederGenerator;
use Laraneat\Core\Generator\Commands\ServiceProviderGenerator;
use Laraneat\Core\Generator\Commands\SubActionGenerator;
use Laraneat\Core\Generator\Commands\TaskGenerator;
use Laraneat\Core\Generator\Commands\TestFunctionalTestGenerator;
use Laraneat\Core\Generator\Commands\TestTestCaseGenerator;
use Laraneat\Core\Generator\Commands\TestUnitTestGenerator;
use Laraneat\Core\Generator\Commands\ValueGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class GeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // all generators ordered by name
        $this->registerGenerators([
            ActionGenerator::class,
            ConfigurationGenerator::class,
            ContainerGenerator::class,
            ContainerApiGenerator::class,
            ContainerWebGenerator::class,
            ControllerGenerator::class,
            EventGenerator::class,
            EventHandlerGenerator::class,
            ExceptionGenerator::class,
            FactoryGenerator::class,
            JobGenerator::class,
            MailGenerator::class,
            MigrationGenerator::class,
            ModelGenerator::class,
            NotificationGenerator::class,
            PolicyGenerator::class,
            QueryWizardGenerator::class,
            ReadmeGenerator::class,
            RequestGenerator::class,
            ResourceGenerator::class,
            RouteGenerator::class,
            SeederGenerator::class,
            ServiceProviderGenerator::class,
            SubActionGenerator::class,
            TestFunctionalTestGenerator::class,
            TestTestCaseGenerator::class,
            TestUnitTestGenerator::class,
            TaskGenerator::class,
            ValueGenerator::class
        ]);
    }

    /**
     * Register the generators.
     */
    private function registerGenerators(array $classes): void
    {
        foreach ($classes as $class) {
            $lowerClass = Str::lower($class);

            $this->app->singleton("command.porto.$lowerClass", function ($app) use ($class) {
                return $app[$class];
            });

            $this->commands("command.porto.$lowerClass");
        }
    }
}
