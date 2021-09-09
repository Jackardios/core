<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ContainerApiGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
        ['docversion', null, InputOption::VALUE_OPTIONAL, 'The version of all endpoints to be generated (1, 2, ...)'],
        ['doctype', null, InputOption::VALUE_OPTIONAL, 'The type of all endpoints to be generated (private, public)'],
        ['url', null, InputOption::VALUE_OPTIONAL, 'The base URI of all endpoints (/stores, /cars, ...)'],
        ['api-prefix', null, InputOption::VALUE_OPTIONAL, 'The prefix of API endpoints (/api)'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:container:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Container for laraneat from scratch (API Part)';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Container';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'composer.stub';

    public function getUserInputs(): array
    {
        $ui = 'api';

        // section name as inputted and lower
        $sectionName = $this->sectionName;
        $_sectionName = Str::snake($this->sectionName);

        // container name as inputted and lower
        $containerName = $this->containerName;
        $_containerName = Str::snake($this->containerName);

        // name of the model (singular and plural)
        $model = $this->containerName;
        $models = Pluralizer::plural($model);

        // add the README file
        $this->printInfoMessage('Generating README File');
        $this->call('laraneat:generate:readme', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'README',
        ]);

        // create the configuration file
        $this->printInfoMessage('Generating Configuration File');
        $this->call('laraneat:generate:configuration', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => Str::camel($this->sectionName) . '-' . Str::camel($this->containerName),
        ]);

        // create the MainServiceProvider for the container
        $this->printInfoMessage('Generating MainServiceProvider');
        $this->call('laraneat:generate:provider', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'MainServiceProvider',
            '--stub' => 'mainserviceprovider',
        ]);

        // create the model for this container
        $this->printInfoMessage('Generating Model');
        $this->call('laraneat:generate:model', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model,
        ]);

        // create the migration file for the model
        $this->printInfoMessage('Generating a basic Migration file');
        $this->call('laraneat:generate:migration', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'create_' . Str::snake($models) . '_table',
            '--tablename' => Str::snake($models),
        ]);

        // create a factory for the model
        $this->printInfoMessage('Generating Factory for the Model');
        $this->call('laraneat:generate:factory', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model . 'Factory',
            '--model' => $model,
        ]);

        // create an API Resource for the model
        $this->printInfoMessage('Generating Resource for the Model');
        $this->call('laraneat:generate:resource', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model . 'Resource',
            '--model' => $model,
        ]);

        // generate a permissions seeder for the model
        $this->printInfoMessage('Generating Permissions Seeder for the Model');
        $this->call('laraneat:generate:seeder', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model . 'PermissionsSeeder_1',
            '--model' => $model,
            '--stub' => 'permissions'
        ]);

        // create a policy for the model
        $this->printInfoMessage('Generating Policy for the Model');
        $this->call('laraneat:generate:policy', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model . 'Policy',
            '--model' => $model,
        ]);

        // create the default routes for this container
        $this->printInfoMessage('Generating Default Routes');
        $apiPrefix = trim(Str::lower($this->checkParameterOrAsk('api-prefix', 'Enter the prefix of API endpoints', 'api/')), '/');
        $version = $this->checkParameterOrAsk('docversion', 'Enter the version for *all* API endpoints (integer)', 1);
        $doctype = $this->checkParameterOrChoice('doctype', 'Select the type for *all* endpoints', ['private', 'public'], 0);

        // get the URI and remove the first trailing slash
        $url = Str::kebab($this->checkParameterOrAsk('url', 'Enter the base URI for *all* endpoints (foo/bar)', Str::kebab($models)));
        $url = ltrim($url, '/');
        $modelRouteKey = Str::camel($model);

        $this->printInfoMessage('Creating Requests for Routes');
        $this->printInfoMessage('Generating Default Actions');

        $routes = [
            [
                'stub' => 'List',
                'name' => 'List' . $models,
                'operation' => 'list' . $models,
                'verb' => 'GET',
                'url' => $url,
                'action' => 'List' . $models . 'Action',
                'request' => 'List' . $models . 'Request',
                'test' => 'List' . $models . 'Test',
            ],
            [
                'stub' => 'View',
                'name' => 'View' . $model,
                'operation' => 'view' . $model,
                'verb' => 'GET',
                'url' => $url . '/{' . $modelRouteKey . '}',
                'action' => 'View' . $model . 'Action',
                'request' => 'View' . $model . 'Request',
                'test' => 'View' . $model . 'Test',
            ],
            [
                'stub' => 'Create',
                'name' => 'Create' . $model,
                'operation' => 'create' . $model,
                'verb' => 'POST',
                'url' => $url,
                'action' => 'Create' . $model . 'Action',
                'request' => 'Create' . $model . 'Request',
                'test' => 'Create' . $model . 'Test',
            ],
            [
                'stub' => 'Update',
                'name' => 'Update' . $model,
                'operation' => 'update' . $model,
                'verb' => 'PATCH',
                'url' => $url . '/{' . $modelRouteKey . '}',
                'action' => 'Update' . $model . 'Action',
                'request' => 'Update' . $model . 'Request',
                'test' => 'Update' . $model . 'Test',
            ],
            [
                'stub' => 'Delete',
                'name' => 'Delete' . $model,
                'operation' => 'delete' . $model,
                'verb' => 'DELETE',
                'url' => $url . '/{' . $modelRouteKey . '}',
                'action' => 'Delete' . $model . 'Action',
                'request' => 'Delete' . $model . 'Request',
                'test' => 'Delete' . $model . 'Test',
            ],
        ];

        foreach ($routes as $route) {
            $this->call('laraneat:generate:route', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => $route['name'],
                '--ui' => $ui,
                '--operation' => $route['operation'],
                '--doctype' => $doctype,
                '--docversion' => $version,
                '--api-prefix' => $apiPrefix,
                '--url' => $route['url'],
                '--verb' => $route['verb'],
            ]);

            $this->call('laraneat:generate:request', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => $route['request'],
                '--ui' => $ui,
                '--stub' => $route['stub'],
                '--model' => $model,
            ]);

            if (isset($route['action'])) {
                $this->call('laraneat:generate:action', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['action'],
                    '--model' => $model,
                    '--stub' => $route['stub'],
                ]);
            }

            if (isset($route['test'])) {
                $urlWithReplacedRouteKey = Str::replace('{' . $modelRouteKey . '}', '{id}', $route['url']);
                $this->call('laraneat:generate:test:functional', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['test'],
                    '--ui' => $ui,
                    '--model' => $model,
                    '--stub' => $route['stub'],
                    '--url' => $apiPrefix . '/v' . $version . '/' . $urlWithReplacedRouteKey,
                ]);
            }
        }

        // finally, generate the controller
        $this->printInfoMessage('Generating Controller to wire everything together');
        $this->call('laraneat:generate:controller', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'Controller',
            '--ui' => $ui,
            '--stub' => 'crud.' . $ui,
        ]);

        $this->printInfoMessage('Generating Composer File');
        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                'package-name' => Str::kebab($this->containerName),
                '_section-name' => $_sectionName,
                'section-name' => $this->sectionName,
                '_container-name' => $_containerName,
                'container-name' => $containerName,
                'class-name' => $this->fileName,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    /**
     * Get the default file name for this component to be generated
     */
    public function getDefaultFileName(): string
    {
        return 'composer';
    }

    public function getDefaultFileExtension(): string
    {
        return 'json';
    }
}
