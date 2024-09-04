<?php

namespace PHPNomad\Rest\Middleware;

use PHPNomad\Rest\Exceptions\ValidationException;
use PHPNomad\Rest\Factories\ValidationSet;
use PHPNomad\Rest\Interfaces\HasValidations;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Utils\Helpers\Arr;

class ValidationMiddleware implements Middleware, HasValidations
{
    protected array $validations;

    /**
     * @param ValidationSet[]|HasValidations $validationProvider
     */
    public function __construct($validationProvider)
    {
        $this->validations = $validationProvider instanceof HasValidations ? $validationProvider->getValidations() : $validationProvider;
    }

    /**
     * Validates the request using the provided validations.
     *
     * @param HasValidations $validations
     * @param Request $request
     * @return array<string, string[]> array of failed keys with failure messages.
     */
    protected function validate(Request $request): array
    {
        $failures = [];
        foreach ($this->getValidations() as $key => $validationSet) {
            $newFailures = $validationSet->getValidationFailures($key, $request);
            if (!empty($newFailures)) {
                $failures = Arr::merge($failures, $newFailures);
            }
        }

        return $failures;
    }

    /** @inheritDoc */
    public function process(Request $request): void
    {
        $failures = $this->validate($request);

        if (!empty($failures)) {
            throw new ValidationException('Validations failed.', $failures);
        }
    }

    /** @inheritDoc */
    public function getValidations(): array
    {
        return $this->validations;
    }
}