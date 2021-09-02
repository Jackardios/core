<?php

namespace Laraneat\Core\Generator\Traits;

trait ParserTrait
{
    /**
     * Replaces the variables in the path structure with defined values
     *
     * @param string $path
     * @param array $data
     *
     * @return string
     */
    public function parsePathStructure(string $path, array $data): string
    {
        return str_replace(
            array(array_map([$this, 'maskPathVariables'], array_keys($data)), '*'),
            array(array_values($data), $this->parsedFileName),
            $path
        );
    }

    /**
     * Replaces the variables in the file structure with defined values
     *
     * @param string $filename
     * @param array $data
     *
     * @return string
     */
    public function parseFileStructure(string $filename, array $data): string
    {
        return str_replace(
            array_map([$this, 'maskFileVariables'], array_keys($data)),
            array_values($data),
            $filename
        );
    }

    /**
     * Replaces the variables in the stub file with defined values
     *
     * @param string $stub
     * @param array $data
     *
     * @return string
     */
    public function parseStubContent(string $stub, array $data): string
    {
        return str_replace(array_map([$this, 'maskStubVariables'], array_keys($data)), array_values($data), $stub);
    }

    private function maskPathVariables(string $key): string
    {
        return '{' . $key . '}';
    }

    private function maskFileVariables(string $key): string
    {
        return '{' . $key . '}';
    }

    private function maskStubVariables(string $key): string
    {
        return '{{' . $key . '}}';
    }
}
