<?php

namespace PHPNomad\Framework\Middlewares;

use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class PaginationMiddleware implements Middleware
{
    public function process(Request $request): void
    {
        if (!$request->hasParam('number')) {
            $request->setParam('number', 10);
        }

        if ($request->getParam('number') > 50) {
            $request->setParam('number', 50);
        }

        if (!$request->hasParam('offset')) {
            $request->setParam('offset', 0);
        }
    }
}