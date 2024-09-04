<?php

namespace PHPNomad\Database\Interfaces;

interface HasCharsetProvider
{
    /**
     * Gets the specified character set.
     *
     * @return ?string
     */
    public function getCharset(): ?string;
}