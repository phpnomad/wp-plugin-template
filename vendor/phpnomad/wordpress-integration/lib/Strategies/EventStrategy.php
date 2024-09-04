<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Events\Interfaces\EventStrategy as CoreEventStrategy;
use PHPNomad\Events\Interfaces\Event;

class EventStrategy implements CoreEventStrategy
{
    /**
     * @param Event|class-string<Event> $event
     * @return string
     */
    protected function getActionName($event): string
    {
        return 'phpnomad/' . $event::getId();
    }

    /**
     * Broadcasts an event.
     *
     * @param Event $event
     * @return void
     */
    public function broadcast(Event $event): void
    {
        do_action($this->getActionName($event), $event);
    }

    /**
     * Attaches an action to an event.
     *
     * @param class-string<Event> $event
     * @param callable $action
     * @param int|null $priority
     * @return void
     */
    public function attach(string $event, callable $action, ?int $priority = null): void
    {
        add_action($this->getActionName($event), $action, is_null($priority) ? 10 : $priority, 1);
    }

    /**
     * Detaches an action from an event.
     *
     * @param class-string<Event> $event
     * @param callable $action
     * @param int|null $priority
     * @return void
     */
    public function detach(string $event, callable $action, ?int $priority = null): void
    {
        remove_action($this->getActionName($event), $action, is_null($priority) ? 10 : $priority);
    }
}
