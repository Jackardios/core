<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class SeederGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model this action is for.'],
        ['stub', null, InputOption::VALUE_OPTIONAL, 'The stub file to load for this generator.'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Seeder class';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Seeder';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Data/Seeders/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'seeders/generic.stub';

    public function getUserInputs(): array
    {
        $stub = Str::lower($this->checkParameterOrChoice(
            'stub',
            'Select the Stub you want to load',
            ['Generic', 'Permissions'],
            0)
        );

        if ($stub === 'permissions') {
            $this->stubName = 'seeders/' . $stub . '.stub';
            $model = $this->checkParameterOrAsk('model', 'Enter the name of the model this action is for.', $this->containerName);

            $entity = Str::camel($model);
            $entities = Str::plural($entity);
        }

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name' => Str::snake($this->sectionName),
                'section-name' => $this->sectionName,
                '_container-name' => Str::snake($this->containerName),
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
                'model' => $model ?? null,
                'entity' => $entity ?? null,
                'entities' => $entities ?? null,
                'kebab-entities' => isset($entities) ? Str::kebab($entities) : null,
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
        return $this->containerName . 'Seeder';
    }
}
