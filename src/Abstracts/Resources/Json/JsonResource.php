<?php

namespace Laraneat\Core\Abstracts\Resources\Json;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource as LaravelJsonResource;

abstract class JsonResource extends LaravelJsonResource
{
    public function created(int $status = 201): JsonResponse
    {
        return $this->response()
                    ->setStatusCode($status);
    }
}
