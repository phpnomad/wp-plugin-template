<?php

namespace PHPNomad\Rest\Interfaces;

use PHPNomad\Rest\Enums\Method;

interface Controller
{
    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * Get the response using the provided request.
     *
     * @param Request $request
     * @return Response
     */
    public function getResponse(Request $request): Response;

    /**
     * @return Method::*
     */
    public function getMethod(): string;
}