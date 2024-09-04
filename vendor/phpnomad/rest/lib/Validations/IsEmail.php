<?php

namespace PHPNomad\Rest\Validations;

use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;

class IsEmail implements Validation
{
    protected string $type;

    /** @inheritDoc */
    public function isValid(string $key, Request $request): bool
    {
        return filter_var($request->getParam($key), FILTER_VALIDATE_EMAIL);
    }

    public function getErrorMessage(string $key, Request $request): string
    {
        return "$key must be a valid email address.";
    }

    public function getContext(): array
    {
        return [];
    }

    public function getType(): string
    {
        return 'INVALID_EMAIL';
    }
}