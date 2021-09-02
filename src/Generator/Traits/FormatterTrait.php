<?php

namespace Laraneat\Core\Generator\Traits;

use Illuminate\Support\Str;

trait FormatterTrait
{
    /**
     * @param string $string
     *
     * @return string
     */
    protected function trimString(string $string): string
    {
        return trim($string);
    }

    /**
     * @param string $word
     *
     * @return string
     */
    public function capitalize(string $word): string
    {
        return ucfirst($word);
    }

    /**
     * @param string $operation
     * @param string $class
     *
     * @return string
     */
    public function prependOperationToName(string $operation, string $class): string
    {
        $className = ($operation === 'list') ? Str::plural($class) : $class;

        return $operation . $this->capitalize($className);
    }
}
