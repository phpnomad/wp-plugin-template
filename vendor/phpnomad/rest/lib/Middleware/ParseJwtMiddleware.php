<?php

namespace PHPNomad\Rest\Middleware;

use PHPNomad\Auth\Exceptions\JwtException;
use PHPNomad\Auth\Services\JwtService;
use PHPNomad\Rest\Exceptions\RestException;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class ParseJwtMiddleware implements Middleware
{
    protected string $requestKey;
    protected JwtService $service;

    public function __construct(JwtService $service, string $requestKey = 'jwt')
    {
        $this->service = $service;
        $this->requestKey = $requestKey;
    }

    public function process(Request $request): void
    {
        $jwt = $request->getParam($this->requestKey);
        try {
            $token = $this->service->decodeJwt($jwt);

            $request->setParam('jwt', $token);
        } catch (JwtException $e) {
            throw new RestException('Invalid Token', [], 400);
        }
    }
}