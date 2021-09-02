<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class RequestGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     */
    public array $inputs = [
        ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the request applies to'],
        ['ui', null, InputOption::VALUE_OPTIONAL, 'The user-interface to generate the Request for.'],
        ['stub', null, InputOption::VALUE_OPTIONAL, 'The stub file to load for this generator.']
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Request class';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Request';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/UI/{user-interface}/Requests/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'requests/generic.stub';

    public function getUserInputs(): array
    {
        $ui = Str::lower($this->checkParameterOrChoice('ui', 'Select the UI for the controller', ['API', 'WEB'], 0));
        $model = $this->checkParameterOrAsk('model', 'Enter the name of the model that the request applies to.', $this->containerName);
        if ($model) {
            $stub = $this->option('stub');

            // Load a new stub-file if generating container otherwise use generic
            $this->stubName = $stub ? 'requests/' . Str::lower($stub) . '.stub' : $this->stubName;
        }

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
                'model' => $model,
                'entity' => Str::camel($model),
                'user-interface' => Str::upper($ui)
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }
}
