<?php

namespace PHPNomad\Events\Interfaces;

interface Event
{
    /**
     * Returns the unique identifier for this object.
     *
     * @return string The unique identifier for this object.
     */
    public static function getId(): string;
}