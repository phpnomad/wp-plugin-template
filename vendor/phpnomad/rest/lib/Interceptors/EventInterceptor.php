<?php

namespace PHPNomad\Rest\Interceptors;

use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\Rest\Interfaces\Interceptor;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Response;

class EventInterceptor implements Interceptor
{
    protected EventStrategy $eventStrategy;

    /**
     * @var callable(): Event
     */
    protected $eventGetter;

    public function __construct(callable $eventGetter, EventStrategy $eventStrategy)
    {
        $this->eventGetter = $eventGetter;
        $this->eventStrategy = $eventStrategy;
    }

    public function process(Request $request, Response $response): void
    {
        $this->eventStrategy->broadcast(($this->eventGetter)());
    }
}