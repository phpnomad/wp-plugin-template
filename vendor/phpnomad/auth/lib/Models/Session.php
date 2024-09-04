<?php

namespace PHPNomad\Auth\Models;

use DateTimeImmutable;
use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Exceptions\SessionParamNotFound;
use PHPNomad\Auth\Interfaces\Action;
use PHPNomad\Auth\Interfaces\Session as SessionInterface;

class Session implements SessionInterface
{
    protected array $params;
    protected DateTimeImmutable $startTime;
    protected Action $action;
    protected string $context;

    /**
     * @param Action $action
     * @param SessionContexts::* $context
     * @param array $initialParams
     */
    public function __construct(Action $action, string $context, array $initialParams = [])
    {
        $this->action = $action;
        $this->context = $context;
        $this->params = $initialParams;
        $this->startTime = new DateTimeImmutable();
    }

    /**
     * @inheritDoc
     */
    public function getParam(string $name)
    {
        if (!isset($this->params[$name])) {
            throw new SessionParamNotFound("Could not find the provided param $name");
        }

        return $this->params[$name];
    }

    /** @inheritDoc */
    public function setParam(string $name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /** @inheritDoc */
    public function getParams(): array
    {
        return $this->params;
    }

    /** @inheritDoc */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /** @inheritDoc */
    public function getDuration(): int
    {
        $now = new DateTimeImmutable();
        $interval = $this->startTime->diff($now);
        $durationInSeconds = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400;

        return $durationInSeconds * 1000;
    }

    /** @inheritDoc */
    public function getIntendedAction(): Action
    {
        return $this->action;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}