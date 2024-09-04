<?php

namespace PHPNomad\Integrations\WordPress\Rest;

use PHPNomad\Rest\Interfaces\Response as CoreResponse;
use PHPNomad\Utils\Helpers\Arr;
use WP_REST_Response;

class Response implements CoreResponse
{
    protected WP_REST_Response $response;

    public function __construct()
    {
        $this->response = new WP_REST_Response();
    }

    /** @inheritDoc */
    public function setStatus(int $code)
    {
        $this->response->set_status($code);
        return $this;
    }

    /** @inheritDoc */
    public function setHeader(string $name, string $value)
    {
        $this->response->set_headers([$name => $value] + $this->response->get_headers());
        return $this;
    }

    /** @inheritDoc */
    public function setBody(string $body)
    {
        $data = json_decode($body, true);
        $this->response->set_data($data);
        return $this;
    }

    /** @inheritDoc */
    public function setJson($data)
    {
        $this->response->set_data($data);
        return $this->setHeader('Content-Type', 'application/json');
    }

    /** @inheritDoc */
    public function setError(string $message, int $code = 400)
    {
        return $this->setStatus($code)->setJson(['error' => $message]);
    }

    /**
     * @inheritDoc
     * @return WP_REST_Response
     */
    public function getResponse(): WP_REST_Response
    {
        return $this->response;
    }

    public function getJson(): array
    {
        return Arr::wrap($this->response->get_data());
    }

    public function getBody(): string
    {
        return json_encode($this->getJson());
    }

    public function getStatus(): int
    {
        return $this->response->get_status();
    }

    public function getHeader(string $name)
    {
        $headers = $this->response->get_headers();

        return Arr::get($headers, $name);
    }

    public function getErrorMessage(): ?string
    {
        $error = Arr::get($this->getJson(), 'error');
        if ($error) {
            return $error;
        }

        return null;
    }
}
