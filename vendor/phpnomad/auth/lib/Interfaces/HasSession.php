<?php

namespace PHPNomad\Auth\Interfaces;

interface HasSession
{
    /**
     * Get the session.
     *
     * @return Session|null The session object if authenticated, or null if not authenticated.
     */
    public function getSession(): ?Session;

}