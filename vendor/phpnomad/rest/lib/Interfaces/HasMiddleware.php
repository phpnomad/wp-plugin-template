<?php

namespace PHPNomad\Rest\Interfaces;

use PHPNomad\Rest\Exceptions\RestException;

interface HasMiddleware
{
    /**
     * @param Request $request
     * @return Middleware[]
     * @throws RestException
     */
    public function getMiddleware(Request $request): array;
}