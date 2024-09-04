<?php

namespace PHPNomad\Rest\Traits;

use PHPNomad\Rest\Interfaces\HasEndpointBase;

trait WithEndpointBase
{
    protected HasEndpointBase $endpointBaseConfigProvider;

    /** @inheritDoc */
    public function getEndpoint(): string
    {
        return $this->endpointBaseConfigProvider->getEndpointBase() . $this->getEndpointTail();
    }

    abstract protected function getEndpointTail(): string;
}