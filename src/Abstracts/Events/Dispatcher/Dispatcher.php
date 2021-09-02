<?php

namespace Laraneat\Core\Abstracts\Events\Dispatcher;

use Laraneat\Core\Abstracts\Events\Interfaces\ShouldHandle;
use Laraneat\Core\Abstracts\Events\Interfaces\ShouldHandleNow;
use Laraneat\Core\Abstracts\Events\Jobs\EventJob;
use DateInterval;
use DateTimeInterface;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Illuminate\Foundation\Bus\PendingDispatch as JobDispatcher;

class Dispatcher extends EventDispatcher
{
    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    public function dispatch($event, $payload = [], $halt = false)
    {
        // Handle event Async when ShouldHandle Interface is implemented
        if ($event instanceof ShouldHandle) {
            // Initialize delay & queue variables
            $delay = $event->jobDelay;
            $queue = $event->jobQueue;

            // Create a job & initialize the dispatcher
            $job = new EventJob($event);
            $dispatcher = new JobDispatcher($job);

            // Check if the delay is set and if it has the correct type
            if (isset($delay)
                && (is_numeric($delay)
                    || $delay instanceof DateTimeInterface
                    || $delay instanceof DateInterval
                )
            ) {
                $dispatcher->delay($delay);
            }
            // Check if the queue is set and if it is a string
            if (isset($queue) && is_string($queue)) {
                $dispatcher->onQueue($queue);
            }

        } else if ($event instanceof ShouldHandleNow) {
            $event->handle();
        }

        return parent::dispatch($event, $payload, $halt);
    }
}
