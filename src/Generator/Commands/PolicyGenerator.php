<?php

namespace Laraneat\Core\Generator\Commands;

use Laraneat\Core\Generator\GeneratorCommand;
use Laraneat\Core\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;
use LogicException;

class PolicyGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads from the console whenever it's called".
     *
     * @var array
     */
    public array $inputs = [
        ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the policy applies to'],
        ['guard', 'g', InputOption::VALUE_OPTIONAL, 'The guard that the policy relies on'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraneat:generate:policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Policy file for a Container';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Policy';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Policies/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'policies/policy.plain.stub';

    /**
     * Get the model for the guard's user provider.
     *
     * @return string|null
     *
     * @throws \LogicException
     */
    protected function userProviderModel(): ?string
    {
        $config = $this->laravel['config'];

        $guard = $this->option('guard') ?: $config->get('auth.defaults.guard');

        if (is_null($guardProvider = $config->get('auth.guards.'.$guard.'.provider'))) {
            throw new LogicException('The ['.$guard.'] guard is not defined in your "auth" configuration file.');
        }

        return $config->get(
            'auth.providers.'.$guardProvider.'.model'
        );
    }

    public function getUserInputs(): array
    {
        $model = $this->checkParameterOrAsk('model', 'Enter the name of the model that the policy applies to.', $this->containerName);

        if ($model) {
            $this->stubName = 'policies/policy.stub';
        }

        $namespacedUserModel = $this->userProviderModel();
        $userModel = class_basename($namespacedUserModel);
        $userModelVariable = Str::camel($userModel);

        $entity = Str::camel($model);
        $kebabEntities = Str::kebab(Str::plural($model));
        if ($entity === $userModelVariable) {
            $entity = 'model';
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
                'model' => $model,
                'entity' => $entity,
                'kebab-entities' => $kebabEntities,
                'namespaced-user-model' => $namespacedUserModel,
                'user-model' => $userModel,
                'user-entity' => $userModelVariable,
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
        return $this->containerName . 'Policy';
    }
}
