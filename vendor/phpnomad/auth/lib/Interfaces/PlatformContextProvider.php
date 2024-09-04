<?php

namespace PHPNomad\Auth\Interfaces;

interface PlatformContextProvider
{
    public function getAdminUrl(): ?string;

    public function getPlatformName(): string;

    public function getPlatformLogoSrc(): string;

    public function getSiteUrl(): string;
}