<?php

namespace PHPNomad\Auth\Interfaces;

use PHPNomad\Auth\Interfaces\User;

interface CanConvertToAuthUser
{
    public function getAuthUser(): User;
}