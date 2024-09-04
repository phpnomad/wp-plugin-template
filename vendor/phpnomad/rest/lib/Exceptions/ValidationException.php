<?php

namespace PHPNomad\Rest\Exceptions;

class ValidationException extends RestException
{
    /**
     * @var array<string, string[]>
     */
    protected array $failures;

    /**
     * Fetches the failed validation cases.
     *
     * @param $failures array<string, string[]> Collection of fields that did not pass validation. Each entry includes
     * the field name as a key, and their corresponding validation failure messages as an array of strings.
     */
    public function __construct(string $message, array $failures, int $code = 400)
    {
        parent::__construct($message, ['type' => 'VALIDATION_FAILED', 'failedValidations' => $failures], $code);
    }
}