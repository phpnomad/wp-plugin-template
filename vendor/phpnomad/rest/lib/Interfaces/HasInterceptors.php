<?php

namespace PHPNomad\Rest\Interfaces;

use PHPNomad\Rest\Exceptions\RestException;

interface HasInterceptors
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Interceptor[]
     * @throws RestException
     */
    public function getInterceptors(Request $request, Response $response): array;
}