<?php

namespace PHPNomad\Events\Interfaces;

/**
 * @template T of Event
 */
interface CanHandle
{
    /**
     * Listen for events.
     *
     * @param T $event
     * @return void
     */
    public function handle(Event $event): void;
}