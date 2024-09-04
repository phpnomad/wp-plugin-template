<?php

namespace PHPNomad\Rest\Interfaces;


use PHPNomad\Rest\Models\FetchPayload;

interface FetchStrategy{

    /**
     * @param FetchPayload $payload
     * @return Response
     */
    public function fetch(FetchPayload $payload): Response;
}