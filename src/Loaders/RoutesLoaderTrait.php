<?php

namespace Laraneat\Core\Loaders;

use Laraneat\Core\Foundation\Facades\Laraneat;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

trait RoutesLoaderTrait
{
    /**
     * Register all the containers routes files in the framework
     */
    public function runRoutesAutoLoader(): void
    {
        $containersPaths = Laraneat::getAllContainerPaths();

        foreach ($containersPaths as $containerPath) {
            $this->loadApiContainerRoutes($containerPath);
            $this->loadWebContainerRoutes($containerPath);
        }
    }

    /**
     * Register the Containers API routes files
     *
     * @param string $containerPath
     */
    private function loadApiContainerRoutes(string $containerPath): void
    {
        // Build the container api routes path
        $apiRoutesPath = $containerPath . '/UI/API/Routes';

        // TODO: ADD DEFAULT NAMESPACE FOR CONTROLLERS
        if (File::isDirectory($apiRoutesPath)) {
            $files = File::allFiles($apiRoutesPath);
            $files = Arr::sort($files, function ($file) {
                return $file->getFilename();
            });
            foreach ($files as $file) {
                $this->loadApiRoute($file);
            }
        }
    }

    private function loadApiRoute(SplFileInfo $file, ?string $controllerNamespace = null): void
    {
        $routeGroupArray = $this->getRouteGroup($file, $controllerNamespace);

        Route::group($routeGroupArray, function () use ($file) {
            require $file->getPathname();
        });
    }

    /**
     * @param SplFileInfo|string $endpointFileOrPrefixString
     * @param string|null $controllerNamespace
     *
     * @return array
     */
    public function getRouteGroup($endpointFileOrPrefixString, ?string $controllerNamespace = null): array
    {
        return [
            'namespace' => $controllerNamespace,
            'middleware' => $this->getMiddlewares(),
            'domain' => $this->getApiDomain(),
            // If $endpointFileOrPrefixString is a file then get the version name from the file name, else if string use that string as prefix
            'prefix' => is_string($endpointFileOrPrefixString) ? $endpointFileOrPrefixString : $this->getApiVersionPrefix($endpointFileOrPrefixString),
        ];
    }

    private function getMiddlewares(): array
    {
        return array_filter([
            'api',
            $this->getRateLimitMiddleware(), // Returns NULL if feature disabled. Null will be removed form the array.
        ]);
    }

    private function getRateLimitMiddleware(): ?string
    {
        $rateLimitMiddleware = null;

        if (config('laraneat.api.throttle.enabled')) {
            $rateLimitMiddleware = 'throttle:' . config('laraneat.api.throttle.attempts') . ',' . config('laraneat.api.throttle.expires');
        }

        return $rateLimitMiddleware;
    }

    private function getApiDomain(): string
    {
        return config('laraneat.api.domain', '');
    }

    private function getApiVersionPrefix(SplFileInfo $file): string
    {
        return config('laraneat.api.prefix') . (config('laraneat.api.enable_version_prefix') ? $this->getRouteFileVersionFromFileName($file) : '');
    }

    private function getRouteFileVersionFromFileName(SplFileInfo $file): string
    {
        $fileNameWithoutExtension = $this->getRouteFileNameWithoutExtension($file);

        $fileNameWithoutExtensionExploded = explode('.', $fileNameWithoutExtension);

        end($fileNameWithoutExtensionExploded);

        $apiVersion = prev($fileNameWithoutExtensionExploded); // get the array before the last one

        // Skip versioning the API's root route
        if ($apiVersion === 'ApisRoot') {
            $apiVersion = '';
        }

        return $apiVersion;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function getRouteFileNameWithoutExtension(SplFileInfo $file): string
    {
        $fileInfo = pathinfo($file->getFileName());

        return $fileInfo['filename'];
    }

    /**
     * Register the Containers WEB routes files
     *
     * @param string $containerPath
     */
    private function loadWebContainerRoutes(string $containerPath): void
    {
        // build the container web routes path
        $webRoutesPath = $containerPath . '/UI/WEB/Routes';

        // TODO: ADD DEFAULT NAMESPACE FOR CONTROLLERS
        if (File::isDirectory($webRoutesPath)) {
            $files = File::allFiles($webRoutesPath);
            $files = Arr::sort($files, function ($file) {
                return $file->getFilename();
            });
            foreach ($files as $file) {
                $this->loadWebRoute($file);
            }
        }
    }

    private function loadWebRoute(SplFileInfo $file, ?string $controllerNamespace = null): void
    {
        Route::group([
            'namespace' => $controllerNamespace,
            'middleware' => ['web'],
        ], function () use ($file) {
            require $file->getPathname();
        });
    }
}
