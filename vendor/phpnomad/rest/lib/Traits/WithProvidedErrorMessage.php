<?php

namespace PHPNomad\Rest\Traits;

namespace PHPNomad\Rest\Traits;

use PHPNomad\Rest\Interfaces\Request;

trait WithProvidedErrorMessage
{
    /**
     * @param null|callable|string $errorMessage
     */
    protected $errorMessage;

    /**
     * Gets the default error message.
     *
     * @param string $key
     * @param Request $request
     * @return string
     */
    abstract protected function getDefaultErrorMessage(string $key, Request $request): string;

    /**
     * Retrieves the error message based on the provided key and request.
     *
     * @param string $key The key used to retrieve the error message.
     * @param Request $request The current request object.
     * @return string The error message.
     */
    public function getErrorMessage(string $key, Request $request): string
    {
        if(is_string($this->errorMessage)){
            return $this->errorMessage;
        }

        if(is_callable($this->errorMessage)){
            return ($this->errorMessage)($key, $request);
        }

        return $this->getDefaultErrorMessage($key, $request);
    }
}