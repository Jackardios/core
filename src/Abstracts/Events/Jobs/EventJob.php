<?php

namespace Laraneat\Core\Abstracts\Events\Jobs;

use Laraneat\Core\Abstracts\Events\Interfaces\ShouldHandle;
use Laraneat\Core\Abstracts\Jobs\Job;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventJob extends Job implements ShouldQueue
{
    public ShouldHandle $handler;

    public function __construct(ShouldHandle $handler)
    {
        $this->handler = $handler;
    }

    public function handle(): void
    {
        $this->handler->handle();
    }
}
