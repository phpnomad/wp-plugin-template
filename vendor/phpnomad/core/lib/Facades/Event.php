<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Events\Interfaces\Event as EventObject;
use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\Singleton\Traits\WithInstance;

/**
 * @extends Facade<EventStrategy>
 */
class Event extends Facade
{
    use WithInstance;

    /**
     * Broadcast a single event.
     *
     * @param EventObject $event The event to broadcast.
     * @return void
     */
    public static function broadcast(EventObject $event): void
    {
        static::instance()->getContainedInstance()->broadcast($event);
    }

    /**
     * Attach the specified callback to the event.
     *
     * @param class-string<EventObject> $event The event name.
     * @param callable $action The callback to fire when this event is broadcast.
     * @param int|null $priority The priority for the event. If null, this will fall to the default.
     * @return void
     */
    public static function attach(string $event, callable $action, ?int $priority = null): void
    {
        try {
            static::instance()->getContainedInstance()->attach($event, $action, $priority);
        } catch (DiException $e) {
            //TODO: CATCH THIS
        }
    }

    /**
     * @param class-string<EventObject> $event The event name.
     * @param callable $action The callback to detach.
     * @param int|null $priority The priority the event was attached.
     * @return void
     */
    public static function detach(string $event, callable $action, ?int $priority = null): void
    {
        try {
            static::instance()->getContainedInstance()->detach($event, $action, $priority);
        } catch (DiException $e) {
            //TODO: CATCH THIS
        }
    }

    /** @inheritDoc */
    protected function abstractInstance(): string
    {
        return EventStrategy::class;
    }
}
