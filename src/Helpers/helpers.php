<?php

if (!function_exists('uncamelize')) {
    /**
     * @param string $word
     * @param string $splitter
     * @param bool $uppercase
     * @return string|string[]|null
     */
    function uncamelize(string $word, string $splitter = " ", bool $uppercase = true)
    {
        $word = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0',
            preg_replace('/(?!^)[[:upper:]]+/', $splitter . '$0', $word));

        return $uppercase ? ucwords($word) : $word;
    }
}
