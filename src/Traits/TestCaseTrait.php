<?php

namespace Laraneat\Core\Traits;

trait TestCaseTrait
{
    /**
     * Override default URL subDomain in case you want to change it for some tests
     *
     * @param null $url
     *
     * @return  string|void
     */
    public function overrideSubDomain($url = null)
    {
        if (!property_exists($this, 'subDomain')) {
            return;
        }

        $url = ($url) ?: $this->baseUrl;

        $info = parse_url($url);

        $array = explode('.', $info['host']);

        $withoutDomain = (array_key_exists(count($array) - 2,
                $array) ? $array[count($array) - 2] : '') . '.' . $array[count($array) - 1];

        $newSubDomain = $info['scheme'] . '://' . $this->subDomain . '.' . $withoutDomain;

        return $this->baseUrl = $newSubDomain;
    }
}
