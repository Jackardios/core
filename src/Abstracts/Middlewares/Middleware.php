<?php

namespace Laraneat\Core\Abstracts\Middlewares;

use Closure;
use Illuminate\Http\Request;

abstract class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    abstract public function handle(Request $request, Closure $next);
}
