<?php

namespace PHPNomad\Rest\Factories;

use Closure;
use PHPNomad\Cache\Traits\WithInstanceCache;
use PHPNomad\Rest\Exceptions\ValidationException;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;

class ValidationSet
{
    use WithInstanceCache;

    protected array $validations = [];
    protected bool $isRequired = false;

    /**
     * @param Closure<Validation> $validationGetter
     * @return $this
     */
    public function addValidation(Closure $validationGetter)
    {
        $this->validations[] = $validationGetter;
        return $this;
    }

    public function setRequired(bool $isRequired = true)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    private function get(int $id)
    {
        return $this->getFromInstanceCache($id, $this->validations[$id]);
    }

    /**
     * Retrieve the error messages for a given key and request.
     *
     * @param string $key The key to check for errors.
     * @param Request $request The request object.
     * @return array An array of error messages.
     */
    public function getValidationFailures(string $key, Request $request): array
    {
        $failures = [];

        // If this isn't required and the field isn't set, don't validate.
        if(!$this->isRequired && !$request->hasParam($key)){
            return $failures;
        }

        if($this->isRequired){
            if (! $request->hasParam($key)) {
        		$failures[] = ['field' => $key, 'message' => "$key is required.", 'type' => 'REQUIRED'];
        	}
        }

        foreach (array_keys($this->validations) as $validationKey) {
            $validation = $this->get($validationKey);
            if (!$validation->isValid($key, $request)) {
                $failures[] = [
                    'field' => $key,
                    'message' => $validation->getErrorMessage($key, $request),
                    'type' => $validation->getType(),
                    'context' => $validation->getContext()
                ];
            }
        }

        return $failures;
    }
}
