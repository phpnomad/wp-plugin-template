<?php

namespace PHPNomad\Core\Facades;

use Exception;
use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Singleton\Traits\WithInstance;

/**
 * @extends Facade<LoggerStrategy>
 */
class Logger extends Facade
{
    use WithInstance;

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     * @throws DiException
     */
    public static function emergency(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->emergency($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function alert(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->alert($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function critical(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->critical($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->error($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->warning($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function notice(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->notice($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->info($message, $context);
    }

    /**
     * @param string $message
     * @param mixed[] $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        static::instance()->getContainedInstance()->debug($message, $context);
    }

    /**
     * Logs an exception.
     *
     * @param Exception $e
     * @param string $message Optional message to prepend to the message.
     * @param array $context Additional context to add. Note the exception is always included in this array as "exception"
     * @return void
     */
    public static function logException(Exception $e, string $message = '', array $context = []): void
    {
        $context['exception'] = $e;
        static::critical(implode(' - ', [$message, $e->getMessage()]), $context);
    }

    protected function abstractInstance(): string
    {
        return LoggerStrategy::class;
    }
}