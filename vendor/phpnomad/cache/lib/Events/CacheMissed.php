<?php

namespace PHPNomad\Cache\Events;

use PHPNomad\Events\Interfaces\Event;

class CacheMissed implements Event
{
    public function __construct($operation, $context, $result)
    {
        $this->operation = $operation;
        $this->context = $context;
        $this->result = $result;
    }

    public static function getId(): string
    {
        return 'cache_missed';
    }
}