<?php

namespace PHPNomad\Rest\Interfaces;

interface CanConvertToRequest
{
    public function toRequest($input): Request;
}