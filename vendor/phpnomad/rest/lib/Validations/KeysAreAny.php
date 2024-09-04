<?php

namespace PHPNomad\Rest\Validations;

use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;
use PHPNomad\Rest\Traits\WithProvidedErrorMessage;
use PHPNomad\Utils\Helpers\Str;

class KeysAreAny implements Validation
{
    use WithProvidedErrorMessage;
    protected array $validItems;

    /**
     * @param BasicTypes::* $type
     * @param null|callable|string $errorMessage
     */
    public function __construct($validItems, $errorMessage = null)
    {
        $this->validItems = $validItems;
        $this->errorMessage = $errorMessage;
    }

    public function isValid(string $key, Request $request): bool
    {
        $items =$request->getParam($key);
        foreach($items as $key => $value){
            if(!in_array($key, $this->validItems)){
                return false;
            }
        }

        return true;
    }

    protected function getDefaultErrorMessage(string $key, Request $request): string
    {
        $param = $request->getParam($key);
        $enumeration = Str::enumerate($this->validItems, 'or', 'either');
        //TODO: Translate this.
        if(null === $param){
            return "keys for $key must be $enumeration, but no value was given";
        }

        $param = implode(',', array_keys($param));

        return "keys for $key must be $enumeration, but was given $param";
    }

    public function getContext(): array
    {
        return [
            'validValues' => $this->validItems
        ];
    }

    public function getType(): string
    {
        return 'REQUIRES_ANY';
    }
}