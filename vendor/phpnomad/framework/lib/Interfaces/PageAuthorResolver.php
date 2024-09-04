<?php

namespace PHPNomad\Framework\Interfaces;

use PHPNomad\Auth\Interfaces\User;

interface PageAuthorResolver
{
    /**
     * Gets the author.
     *
     * @return User|null
     */
    public function getAuthor(int $pageId): ?User;
}