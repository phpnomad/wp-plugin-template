<?php

namespace PHPNomad\Auth\Traits;

use PHPNomad\Auth\Interfaces\User;

trait WithUser
{
    protected User $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}