<?php

namespace PHPNomad\Framework\Events;

use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Framework\Interfaces\MayHaveAssociatedInteractorId;
use PHPNomad\Framework\Traits\WithAssociatedInteractorId;

class SiteVisited implements Event, MayHaveAssociatedInteractorId
{
    use WithAssociatedInteractorId;

    public function __construct(?int $associatedId)
    {
        $this->interactorId = $associatedId;
    }

    public static function getId(): string
    {
        return 'site_visited';
    }
}