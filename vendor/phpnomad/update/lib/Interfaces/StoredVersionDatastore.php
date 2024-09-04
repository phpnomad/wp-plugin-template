<?php

namespace PHPNomad\Update\Interfaces;

interface StoredVersionDatastore
{
    public function getStoredVersion(): ?string;

    public function updateStoredVersion(string $version): void;
}