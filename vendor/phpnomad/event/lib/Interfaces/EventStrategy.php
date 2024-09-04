<?php

namespace PHPNomad\Events\Interfaces;
interface EventStrategy
{
    public function broadcast(Event $event): void;

    public function attach(string $event, callable $action, ?int $priority = null): void;

    public function detach(string $event, callable $action, ?int $priority = null): void;
}