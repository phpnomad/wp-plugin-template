<?php

namespace PHPNomad\Auth\Interfaces;

interface HashStrategy
{
    public function generateHash(int $characterCount = 32): string;
}