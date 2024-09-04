<?php

namespace PHPNomad\Rest\Interfaces;

use PHPNomad\Auth\Interfaces\HasUser;



interface Request extends HasUser
{
    /**
     * Get a specific header value.
     *
     * @param string $name Header name.
     * @return mixed|null Header value or null if the header doesn't exist.
     */
    public function getHeader(string $name);

    /**
     * Set a specific header value.
     *
     * @param string $name Header name.
     * @param mixed $value Header value.
     */
    public function setHeader(string $name, $value): void;

    /**
     * Get all headers.
     *
     * @return array<string, string> An associative array of header names to values.
     */
    public function getHeaders(): array;

    /**
     * Get a specific request parameter.
     *
     * @param string $name Parameter name. Can be dot notated to get sub items in the param.
     * @return mixed|null Parameter value or null if the parameter doesn't exist.
     */
    public function getParam(string $name);

    /**
     * Returns true if the param is set on the request, otherwise false.
     *
     * @param string $name Parameter name. Can be dot notated to get sub items in the param.
     * @return bool
     */
    public function hasParam(string $name): bool;

    /**
     * Set a specific request parameter.
     *
     * @param string $name Parameter name.
     * @param mixed $value Parameter value.
     */
    public function setParam(string $name, $value): void;

    /**
     * Removes a specific request parameter.
     *
     * @param string $name
     * @return void
     */
    public function removeParam(string $name): void;

    /**
     * Get all request parameters.
     *
     * @return array<string, mixed> An associative array of parameter names to values.
     */
    public function getParams(): array;
}