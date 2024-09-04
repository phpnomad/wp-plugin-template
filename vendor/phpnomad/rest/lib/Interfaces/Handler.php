<?php

namespace PHPNomad\Rest\Interfaces;

interface Handler
{
    /**
     * Get the response using the provided request.
     *
     * @param Request $request
     * @return Response
     */
    public function getResponse(Request $request): Response;
}