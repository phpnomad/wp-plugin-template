<?php

namespace PHPNomad\Auth\Strategies;

use PHPNomad\Auth\Exceptions\InvalidSignatureException;
use PHPNomad\Auth\Exceptions\JwtException;
use PHPNomad\Auth\Exceptions\TokenExpiredException;
use PHPNomad\Auth\Interfaces\JwtStrategy as JwtStrategyInterface;

final class JwtStrategy implements JwtStrategyInterface
{
    private function base64UrlEncode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @inheritDoc
     */
    public function encode(array $payload, string $secret): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt, string $secret): array
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            throw new JwtException('Invalid token structure');
        }

        $header = json_decode(base64_decode(strtr($parts[0], '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        $signatureProvided = base64_decode(strtr($parts[2], '-_', '+/'));

        // Verify the header is an array and the alg key is present
        if (!is_array($header) || !isset($header['alg'])) {
            throw new JwtException('Invalid header encoding or "alg" missing');
        }

        // Verify the algorithm matches the expected value
        if ($header['alg'] !== 'HS256') {
            throw new JwtException('Unexpected signing algorithm');
        }

        // Verify the signature
        $signatureExpected = hash_hmac('sha256', $parts[0] . '.' . $parts[1], $secret, true);
        if (!hash_equals($signatureExpected, $signatureProvided)) {
            throw new InvalidSignatureException();
        }

        // Verify the expiration if present
        if (isset($payload['exp']) && time() >= $payload['exp']) {
            throw new TokenExpiredException();
        }

        return $payload;
    }
}