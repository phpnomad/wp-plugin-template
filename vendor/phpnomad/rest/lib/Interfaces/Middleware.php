<?php

namespace PHPNomad\Rest\Interfaces;

use PHPNomad\Rest\Exceptions\RestException;

interface Middleware
{
    /**
     * Process the request.
     *
     * @param Request $request
     * @return void
     * @throws RestException
     */
    public function process(Request $request): void;
}