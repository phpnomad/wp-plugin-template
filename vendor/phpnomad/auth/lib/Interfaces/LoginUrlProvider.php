<?php

namespace PHPNomad\Auth\Interfaces;

interface LoginUrlProvider
{
    public function getRegistrationUrl(): string;
}