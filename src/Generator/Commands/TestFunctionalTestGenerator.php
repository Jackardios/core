<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TestFunctionalTestGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
        ['ui', null, InputOption::VALUE_OPTIONAL, 'The user-interface to generate the Test for.'],
        ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the test applies to.'],
        ['stub', null, InputOption::VALUE_OPTIONAL, 'The stub file to load for this generator.'],
        ['url', 'u', InputOption::VALUE_OPTIONAL, 'The url to be called within this test.'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:test:functional';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Functional Test file.';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Functional Test';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/UI/{user-interface}/Tests/Functional/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'tests/functional/generic.stub';

    public function getUserInputs(): array
    {
        $ui = Str::lower($this->checkParameterOrChoice('ui', 'Select the UI for the Test', ['API', 'WEB', 'CLI'], 0));

        $stub = 'generic';
        if ($ui === 'api' || $ui === 'web') {
            $url = $this->checkParameterOrAsk('url', 'Enter url to be called within this test.');
            $model = $this->checkParameterOrAsk('model', 'Enter the name of the model this action is for.');

            if ($model) {
                $models = Str::plural($model);
                $entity = Str::camel($model);
                $entities = Str::plural($entity);
                $table = Str::snake($entities);
                $stub = Str::lower($this->checkParameterOrChoice(
                    'stub',
                    'Select the Stub you want to load',
                    ['Generic', 'List', 'View', 'Create', 'Update', 'Delete'],
                    0)
                );
            }
        }

        // Set the stub file accordingly
        $this->stubName = 'tests/functional/' . $ui . '/' . $stub . '.stub';

        // We need to generate the TestCase class before
        $this->call('laraneat:generate:test:testcase', [
            '--section' => $this->sectionName,
            '--container' => $this->containerName,
            '--file' => 'TestCase',
            '--ui' => $ui,
        ]);

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
                'user-interface' => Str::upper($ui),
            ],
            'stub-parameters' => [
                '_section-name' => Str::snake($this->sectionName),
                'section-name' => $this->sectionName,
                '_container-name' => Str::snake($this->containerName),
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
                'model' => $model ?? null,
                'models' => $models ?? null,
                'entity' => $entity ?? null,
                'entities' => $entities ?? null,
                'kebab-entities' => isset($entities) ? Str::kebab($entities) : null,
                'table' => $table ?? null,
                'url' => $url ?? null,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    public function getDefaultFileName(): string
    {
        return 'Default' . $this->containerName . 'Test';
    }
}
