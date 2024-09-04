<?php

namespace PHPNomad\Auth\Interfaces;

interface SecretProvider
{
    public function getSecret(): string;
}