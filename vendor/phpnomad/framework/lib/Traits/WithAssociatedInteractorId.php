<?php

namespace PHPNomad\Framework\Traits;

trait WithAssociatedInteractorId
{
    protected ?int $interactorId;

    public function getInteractorId(): ?int
    {
        return $this->interactorId;
    }
}