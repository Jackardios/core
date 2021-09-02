<?php

namespace Laraneat\Core\Abstracts\Controllers;

use Laraneat\Core\Traits\ResponseTrait;

abstract class ApiController extends Controller
{
    use ResponseTrait;

    /**
     * UI type. This will be accessibly mirrored in the Actions.
     * Giving each Action the ability to modify it's internal business logic based on the UI type that called it.
     */
    public string $ui = 'api';
}
