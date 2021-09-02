<?php

namespace Laraneat\Core\Abstracts\Requests;

use Laraneat\Core\Traits\SanitizerTrait;
use Laraneat\Core\Traits\StateKeeperTrait;
use Illuminate\Foundation\Http\FormRequest as LaravelRequest;

abstract class Request extends LaravelRequest
{
    use StateKeeperTrait;
    use SanitizerTrait;
}
