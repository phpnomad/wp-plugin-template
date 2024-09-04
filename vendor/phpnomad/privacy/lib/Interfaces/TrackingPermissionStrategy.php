<?php

namespace PHPNomad\Privacy\Interfaces;

interface TrackingPermissionStrategy
{
    /**
     * Checks if the current request can be tracked.
     *
     * @return bool true if tracking is enabled, false otherwise.
     */
    public function canTrack(): bool;
}