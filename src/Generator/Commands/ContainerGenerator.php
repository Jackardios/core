<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ContainerGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     */
    public array $inputs = [
        ['ui', null, InputOption::VALUE_OPTIONAL, 'The user-interface to generate the Controller for.'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:container';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Container for laraneat from scratch';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $fileType = 'Container';

    /**
     * The structure of the file path.
     *
     * @var string
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';

    /**
     * The structure of the file name.
     *
     * @var string
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     *
     * @var string
     */
    protected string $stubName = 'composer.stub';

    public function getUserInputs(): array
    {
        $ui = Str::lower($this->checkParameterOrChoice('ui', 'Select the UI for this container', ['API', 'WEB', 'BOTH'], 0));

        // container name as inputted and lower
        $sectionName = $this->sectionName;
        $_sectionName = Str::snake($this->sectionName);

        // container name as inputted and lower
        $containerName = $this->containerName;
        $_containerName = Str::snake($this->containerName);

        if ($ui === 'api' || $ui === 'both') {
            $this->call('laraneat:generate:container:api', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => 'composer'
            ]);
        }

        if ($ui === 'web' || $ui === 'both') {
            $this->call('laraneat:generate:container:web', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => 'composer'
            ]);
        }

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
