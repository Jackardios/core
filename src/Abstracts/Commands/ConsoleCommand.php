<?php

namespace Laraneat\Core\Abstracts\Commands;

use Illuminate\Console\Command as LaravelCommand;

abstract class ConsoleCommand extends LaravelCommand
{
    /**
     * UI type. This will be accessibly mirrored in the Actions.
     * Giving each Action the ability to modify it's internal business logic based on the UI type that called it.
     */
    public string $ui = 'cli';
}
