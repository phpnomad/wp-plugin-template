<?php

namespace PHPNomad\Rest\Interfaces;

interface Validation
{
    /**
     * Validates a given request's aspect (like headers, body, route params).
     *
     * @param string $key The key for the value to validate
     * @param Request $request
     *
     * @return bool Whether the request is valid according to this validator.
     */
    public function isValid(string $key, Request $request): bool;

    /**
     * Returns the error message associated with this validation.
     *
     * @return string The error message.
     */
    public function getErrorMessage(string $key, Request $request): string;

    public function getContext(): array;

    public function getType(): string;
}