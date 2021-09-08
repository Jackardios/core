<?php

namespace Laraneat\Core\Traits\TestsTraits;

use Illuminate\Support\Str;

trait TestsUrlHelperTrait
{
    public function addQueryParametersToUrl(string $url, array $queryParameters = []): string
    {
        $queryString = http_build_query($queryParameters);

        if ($queryString) {
            if(Str::contains($url, '?')) {
                $queryString = '&' . $queryString;
            } else {
                $queryString = '?' . $queryString;
            }
        }

        return rtrim($url, '/') . $queryString;
    }

    public function replaceByKeyValues(string $url, array $replaces): string
    {
        foreach ($replaces as $key => $value) {
            $url = Str::replace($key, $value, $url);
        }

        return $url;
    }

    public function trimSlashes($path): string {
        return trim($path, '/');
    }

    public function buildUrl(string $path, array $queryParameters = [], array $replaces = []): string
    {
        if($this->isAbsoluteUrl($path)) {
            return $this->addQueryParametersToUrl($path, $queryParameters);
        }

        $apiDomain = $this->trimSlashes(config('app.url'));
        $url = $apiDomain.'/'.$this->trimSlashes($path);

        if ($replaces) {
            $url = $this->replaceByKeyValues($url, $replaces);
        }

        return $this->addQueryParametersToUrl($url, $queryParameters);
    }

    public function buildApiUrl(string $path, array $queryParameters = [], array $replaces = []): string
    {
        if($this->isAbsoluteUrl($path)) {
            return $this->addQueryParametersToUrl($path, $queryParameters);
        }

        $apiDomain = $this->trimSlashes(config('laraneat.api.domain'));
        $apiPrefix = $this->trimSlashes(config('laraneat.api.prefix'));
        $url = $apiDomain.'/'.$apiPrefix.'/'.$this->trimSlashes($path);

        if ($replaces) {
            $url = $this->replaceByKeyValues($url, $replaces);
        }

        return $this->addQueryParametersToUrl($url, $queryParameters);
    }

    public function isAbsoluteUrl(string $url): bool
    {
        return Str::startsWith($url, ['http://', 'https://']);
    }
}
