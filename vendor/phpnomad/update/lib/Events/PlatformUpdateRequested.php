<?php

namespace PHPNomad\Update\Events;

use PHPNomad\Events\Interfaces\Event;

class PlatformUpdateRequested implements Event
{

    protected string $currentVersion;
    protected string $targetVersion;

    public function __construct(string $currentVersion, string $targetVersion)
    {
        $this->currentVersion = $currentVersion;
        $this->targetVersion = $targetVersion;
    }

    public function getCurrentVersion(): string
    {
        return $this->currentVersion;
    }

    public function getTargetVersion(): string
    {
        return $this->targetVersion;
    }

    public static function getId(): string
    {
        return 'platform_update_requested';
    }
}