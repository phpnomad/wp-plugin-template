<?php

namespace PHPNomad\Rest\Models;

class FetchPayload
{
    protected string $url;
    protected string $method = 'GET';
    protected array $headers = [];
    protected $body;
    protected array $params = [];

    /**
     * Set the URL for the request.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get the URL for the request.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set the HTTP method for the request.
     *
     * @param Method::*&string $method
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get the HTTP method for the request.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set a header for the request.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get a header value by name.
     *
     * @param string $name
     * @return string|null
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get all headers for the request.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the body for the request.
     *
     * @param mixed $body
     * @return $this
     */
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get the body for the request.
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set a request parameter.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam(string $name, $value): self
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Get a request parameter by name.
     *
     * @param string $name
     * @return mixed|null
     */
    public function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * Get all request parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}