<?php

namespace PHPNomad\Framework\Middlewares;

use PHPNomad\Registry\Interfaces\CanGet;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class FieldResolverMiddleware implements Middleware
{
    protected CanGet $fieldResolverRegistry;

    public function __construct(CanGet $registry)
    {
        $this->fieldResolverRegistry = $registry;
    }

    public function process(Request $request): void
    {
        //TODO: IT WOULD BE NICE TO PROVIDE WARNINGS HERE WHEN THERE'S A FAILURE INSTEAD OF IGNORING INVALID FIELDS
        // IN CSV MIDDLEWARE.
        (new ConvertCsvMiddleware($this->fieldResolverRegistry->getKeys(), 'fields'))->process($request);
    }
}