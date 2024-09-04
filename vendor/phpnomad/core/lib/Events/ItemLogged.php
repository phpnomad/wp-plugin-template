<?php

namespace PHPNomad\Core\Events;

use PHPNomad\Events\Interfaces\Event;

class ItemLogged implements Event
{
    public const EMERGENCY = 7;
    public const ALERT = 6;
    public const CRITICAL = 5;
    public const ERROR = 4;
    public const WARNING = 3;
    public const NOTICE = 2;
    public const INFO = 1;
    public const DEBUG = 0;

    /**
     * @var int&static::*
     */
    protected int $severity;

    protected string $message;

    /**
     * @var array<mixed>
     */
    protected array $context;

    /**
     * @param int&static::* $severity
     * @param string $message
     * @param array<mixed> $context
     */
    public function __construct(int $severity, string $message, array $context = [])
    {
        $this->severity = $severity;
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * Gets the severity
     *
     * @return int
     */
    public function getSeverity(): int
    {
        return $this->severity;
    }

    /**
     * Gets the message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Gets the context data for this logged item.
     *
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Checks the severity based on the provided level.
     *
     * @param string $comparison
     * @param int&static::* $severity
     * @return bool
     */
    public function severityIs(string $comparison, int $severity): bool
    {
        switch ($comparison) {
            case '>':
                return $this->severity > $severity;
            case '>=':
                return $this->severity >= $severity;
            case '<':
                return $this->severity < $severity;
            case '<=':
                return $this->severity <= $severity;
            case '=':
                return $this->severity === $severity;
            default:
                return false;
        }
    }

    public static function getId(): string
    {
        return 'event_logged';
    }
}
