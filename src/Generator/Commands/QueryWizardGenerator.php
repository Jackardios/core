<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class QueryWizardGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads from the console whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
        ['type', 't', InputOption::VALUE_OPTIONAL, 'The QueryWizard type ("eloquent" or "scout").'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:query-wizard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a QueryWizard file for a Container';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'QueryWizard';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/UI/API/QueryWizards/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'query-wizards/eloquent.stub';

    public function getUserInputs(): array
    {
        $type = Str::lower($this->checkParameterOrChoice('type', 'Select the QueryWizard type', ['Eloquent', 'Scout'], 'Eloquent'));
        $this->stubName = "query-wizards/$type.stub";

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
        return $this->containerName . 'QueryWizard';
    }
}
