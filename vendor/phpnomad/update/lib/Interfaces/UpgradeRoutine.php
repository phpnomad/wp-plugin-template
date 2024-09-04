<?php

namespace PHPNomad\Update\Interfaces;

interface UpgradeRoutine
{
    /**
     * Get the target version for the routine.
     *
     * @return string The target version.
     */
    public static function getTargetVersion(): string;

    /**
     * Execute the upgrade routine.
     *
     * @return void
     */
    public function up(): void;
}