<?php

namespace PHPNomad\Integrations\WordPress\Interfaces;

interface HasDefaultLanguage extends HasLanguage
{
    /** Gets a language string. */
    public function getLanguage(): string;
}