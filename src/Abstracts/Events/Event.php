<?php

namespace Laraneat\Core\Abstracts\Events;

use Laraneat\Core\Abstracts\Events\Traits\JobProperties;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use JobProperties;
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
