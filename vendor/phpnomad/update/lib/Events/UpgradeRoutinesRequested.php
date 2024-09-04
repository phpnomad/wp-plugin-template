<?php

namespace PHPNomad\Update\Events;

use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Update\Interfaces\UpgradeRoutine;
use PHPNomad\Utils\Helpers\Arr;

class UpgradeRoutinesRequested implements Event
{
    private string $currentVersion;
    private string $targetVersion;
    private array $routines = [];

    /**
     * UpgradeEvent constructor.
     *
     * @param string $currentVersion The current version of the system.
     * @param string $targetVersion The target version for the upgrade.
     */
    public function __construct(string $currentVersion, string $targetVersion) {
        $this->currentVersion = $currentVersion;
        $this->targetVersion = $targetVersion;
    }

    /**
     * Get the current version of the system.
     *
     * @return string The current version.
     */
    public function getCurrentVersion(): string {
        return $this->currentVersion;
    }

    /**
     * Get the target version for the upgrade.
     *
     * @return string The target version.
     */
    public function getTargetVersion(): string {
        return $this->targetVersion;
    }

    /**
     * Conditionally register an upgrade routine.
     *
     * @param class-string<UpgradeRoutine> $routine The upgrade routine to register.
     *
     * @return $this
     */
    public function maybeRegisterRoutines(string $routine, string ...$routines) {
        if (version_compare($routine::getTargetVersion(), $this->currentVersion, '>') &&
            version_compare($routine::getTargetVersion(), $this->targetVersion, '<=')) {
            $this->routines = Arr::merge($this->routines, [$routine], $routines);
        }
        
        return $this;
    }

    /**
     * Get the registered upgrade routines.
     *
     * @return UpgradeRoutine[] An array of registered upgrade routines.
     */
    public function getRoutines(): array {
        return $this->routines;
    }

    /**
     * @inheitDoc
     */
    public static function getId(): string
    {
        return 'update_routines_requested';
    }
}