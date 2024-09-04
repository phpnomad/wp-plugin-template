<?php

namespace PHPNomad\Singleton\Traits;

trait WithInstance
{
    protected static $instance;

    /**
     * Returns the current instance.
     * @return $this
     */
    public static function instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
