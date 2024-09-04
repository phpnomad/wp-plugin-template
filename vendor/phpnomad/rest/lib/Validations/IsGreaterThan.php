<?php

namespace PHPNomad\Rest\Validations;

use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;

class IsGreaterThan implements Validation
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function isValid(string $key, Request $request): bool
    {
        return $request->getParam($key) > $this->value;
    }

    public function getErrorMessage(string $key, Request $request): string
    {
        if($request->hasParam($key)){
            $amount = $request->getParam($key);
            return "$key must be greater than $this->value. Was given " . $amount;
        }

        return "$key must be greater than $this->value, but no value was given.";
    }

    public function getContext(): array
    {
        return [
            'minimumValue' => $this->value
        ];
    }

    public function getType(): string
    {
        return 'VALUE_TOO_SMALL';
    }
}