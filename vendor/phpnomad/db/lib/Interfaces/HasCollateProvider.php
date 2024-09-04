<?php

namespace PHPNomad\Database\Interfaces;

interface HasCollateProvider
{
    /**
     * @return string
     */
    public function getCollation(): ?string;
}