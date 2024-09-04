<?php

namespace PHPNomad\Integrations\WordPress\Interfaces;

interface HasLanguage
{
    /** Gets a language string. */
    public function getLanguage(): ?string;
}