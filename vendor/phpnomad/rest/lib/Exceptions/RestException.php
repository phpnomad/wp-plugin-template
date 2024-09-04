<?php

namespace PHPNomad\Rest\Exceptions;

use Exception;

class RestException extends Exception
{
    protected array $context;

    public function __construct(string $message, array $context = [], int $code = 400)
    {
        parent::__construct($message, $code);
        $this->context = $context;
    }

    /**
     * Returns the context of the exception. This gets passed as the error message's "context" array.
     *
     * @return array The context of the object.
     */
    public function getContext(): array
    {
        return $this->context;
    }
}