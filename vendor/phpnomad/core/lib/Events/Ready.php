<?php

namespace PHPNomad\Core\Events;

use PHPNomad\Events\Interfaces\Event;

class Ready implements Event
{
    public static function getId(): string
    {
        return 'ready';
    }
}