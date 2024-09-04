<?php

namespace PHPNomad\Rest\Interfaces;

interface Response
{
    /**
     * Set the HTTP status code for the response.
     *
     * @param int $code HTTP status code.
     * @return Response
     */
    public function setStatus(int $code);

    /**
     * Gets the HTTP status for the response
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Add a header to the response.
     *
     * @param string $name Header name.
     * @param string $value Header value.
     * @return Response
     */
    public function setHeader(string $name, string $value);

    /**
     * @param string $name
     * @return mixed
     */
    public function getHeader(string $name);

    /**
     * Set the body content of the response.
     *
     * @param string $body The body content.
     * @return Response
     */
    public function setBody(string $body);

    /**
     * Set the body content as JSON.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @return Response
     */
    public function setJson($data);

    /**
     * Gets the body content as JSON.
     *
     * @return array
     */
    public function getJson(): array;

    /**
     * Gets the body as a string.
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Set an error message as the body content.
     *
     * @param string $message Error message.
     * @param int $code HTTP status code (default is 400).
     * @return Response
     */
    public function setError(string $message, int $code = 400);

    /**
     * @return ?string
     */
    public function getErrorMessage(): ?string;

    /**
     * Gets the response object.
     *
     * @return object
     */
    public function getResponse();
}