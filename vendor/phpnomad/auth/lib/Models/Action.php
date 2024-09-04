<?php

namespace PHPNomad\Auth\Models;

use PHPNomad\Auth\Interfaces\Action as ActionInterface;

class Action implements ActionInterface
{
    protected string $targetType;
    protected ?array $targetIdentifier;
    protected string $action;

    public function __construct(string $action, string $targetType, ?array $targetIdentifier = null)
    {
        $this->action = $action;
        $this->targetIdentifier = $targetIdentifier;
        $this->targetType = $targetType;
    }



    /** @inheritDoc */
    public function getTargetIdentifier(): ?array
    {
        return $this->targetIdentifier;
    }

    /** @inheritDoc */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /** @inheritDoc */
    public function getAction(): string
    {
        return $this->action;
    }
}