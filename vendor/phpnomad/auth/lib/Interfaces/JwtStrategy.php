<?php

namespace PHPNomad\Auth\Interfaces;

use PHPNomad\Auth\Exceptions\JwtException;

interface JwtStrategy
{
    /**
     * Encodes the given payload into a JWT.
     *
     * @param array $payload The payload data to encode.
     * @param string $secret The secret key used for signing the token.
     * @return string The encoded JWT.
     */
    public function encode(array $payload, string $secret): string;

    /**
     * Decodes the given JWT and returns the payload.
     *
     * @param string $jwt The JWT to decode.
     * @param string $secret The secret key used for verifying the token's signature.
     * @return array The decoded payload.
     * @throws JwtException If the token is invalid or the signature does not match.
     */
    public function decode(string $jwt, string $secret): array;
}