<?php

namespace PHPNomad\Auth\Services;

use PHPNomad\Auth\Exceptions\InvalidSignatureException;
use PHPNomad\Auth\Exceptions\JwtException;
use PHPNomad\Auth\Exceptions\TokenExpiredException;
use PHPNomad\Auth\Interfaces\JwtStrategy;
use PHPNomad\Auth\Interfaces\SecretProvider;

class JwtService
{
    protected SecretProvider $secretProvider;
    protected JwtStrategy $jwtStrategy;

    public function __construct(JwtStrategy $jwtStrategy, SecretProvider $secretProvider)
    {
        $this->jwtStrategy = $jwtStrategy;
        $this->secretProvider = $secretProvider;
    }

    public function encodeJwt(array $payload): string
    {
        return $this->jwtStrategy->encode($payload, $this->secretProvider->getSecret());
    }

    /**
     * @param string $jwt
     * @return array
     * @throws JwtException
     * @throws TokenExpiredException
     * @throws InvalidSignatureException
     */
    public function decodeJwt(string $jwt): array
    {
        return $this->jwtStrategy->decode($jwt, $this->secretProvider->getSecret());
    }
}