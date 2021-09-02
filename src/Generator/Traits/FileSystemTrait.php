<?php

namespace Laraneat\Core\Generator\Traits;

use Exception;

trait FileSystemTrait
{
    /**
     * Determine if the file already exists.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function alreadyExists(string $path): bool
    {
        return $this->fileSystem->exists($path);
    }

    /**
     * @param string $filePath
     * @param string $stubContent
     *
     * @return int|bool
     */
    public function generateFile(string $filePath, string $stubContent)
    {
        return $this->fileSystem->put($filePath, $stubContent);
    }

    /**
     * If path is for a directory, create it otherwise do nothing
     *
     * @param string $path
     */
    public function createDirectory(string $path): void
    {
        if ($this->alreadyExists($path)) {
            $this->printErrorMessage($this->fileType . ' already exists');

            // the file does exist - return but NOT exit
            return;
        }

        try {
            if (!$this->fileSystem->isDirectory(dirname($path))) {
                $this->fileSystem->makeDirectory(dirname($path), 0777, true, true);
            }

        } catch (Exception $e) {
            $this->printErrorMessage('Could not create ' . $path);
        }
    }
}
