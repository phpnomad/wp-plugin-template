<?php

namespace PHPNomad\Rest\Validations;

use PHPNomad\Rest\Enums\BasicTypes;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;
use PHPNomad\Rest\Traits\WithProvidedErrorMessage;

class IsType implements Validation
{
    use WithProvidedErrorMessage;
    protected $errorMessage;
    protected string $type;

    /**
     * @param BasicTypes::* $type
     * @param null|callable|string $errorMessage
     */
    public function __construct(string $type, $errorMessage = null)
    {
        $this->type = $type;
        $this->errorMessage = $errorMessage;
    }

    /** @inheritDoc */
    public function isValid(string $key, Request $request): bool
    {
        $value = $request->getParam($key);
        switch ($this->type) {
            case BasicTypes::Boolean:
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
            case BasicTypes::Integer:
                return is_numeric($value) && strpos($value, '.') === false;
            case BasicTypes::Float:
                return is_numeric($value) && strpos($value, '.') !== false;
            case BasicTypes::String:
                return true;
            case BasicTypes::Array:
                return is_array($value);
            case BasicTypes::Object:
                return is_object($value);
            case BasicTypes::Null:
                return empty($value);
            default:
                return false;
        }
    }

    protected function getDefaultErrorMessage(string $key, Request $request): string
    {
        $param = $request->getParam($key);
        $type = $this->type;

        //TODO: Translate this.
        if(null === $param){
            return "$key must be a $type, but no value was given";
        }

        return "$key must be a $type, was given " . $param;
    }

    public function getContext(): array
    {
        return [
            'requiredType' => $this->type
        ];
    }

    public function getType(): string
    {
        return 'INVALID_TYPE';
    }
}