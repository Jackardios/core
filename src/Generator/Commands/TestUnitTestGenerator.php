<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Str;

class TestUnitTestGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:test:unit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Unit Test file.';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Unit Test';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Tests/Unit/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'tests/unit/generic.stub';

    public function getUserInputs(): array
    {
        // We need to generate the TestCase class before
        $this->call('laraneat:generate:test:testcase', [
            '--section' => $this->sectionName,
            '--container' => $this->containerName,
            '--file' => 'TestCase',
            '--ui' => 'generic',
        ]);

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

    public function getDefaultFileName(): string
    {
        return 'DefaultTest';
    }
}
