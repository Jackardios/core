<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
        ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource class for a given Model';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Resource';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/UI/API/Resources/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'resources/resource.stub';

    /**
     * Determine if the command is generating a resource collection.
     *
     * @return bool
     */
    protected function collection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->fileName, 'Collection');
    }

    public function getUserInputs(): array
    {
        if ($this->collection()) {
            $this->stubName = 'resources/resource-collection.stub';
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
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    /**
     * Get the default file name for this component to be generated
     */
    protected function getDefaultFileName(): string
    {
        return $this->containerName . Str::ucfirst($this->fileType);
    }
}
