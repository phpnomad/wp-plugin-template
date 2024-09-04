<?php

namespace PHPNomad\Rest\Interfaces;

interface HasRestNamespace
{
    /**
     * @return string
     */
    public function getRestNamespace(): string;
}