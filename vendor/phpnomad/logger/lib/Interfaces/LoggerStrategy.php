<?php

namespace PHPNomad\Logger\Interfaces;

use Exception;
use PHPNomad\Logger\Enums\LoggerLevel;

interface LoggerStrategy
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void;

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void;

    /**
     * Logs an exception.
     *
     * @param Exception $e
     * @param string $message
     * @param array $context
     * @param LoggerLevel::*&string $level
     * @return mixed
     */
    public function logException(Exception $e, string $message = '', array $context = [], string $level = null);
}