<?php

namespace PHPNomad\Framework\Middlewares;

use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class PrepareCheckboxMiddleware implements Middleware
{
    /**
     * @var string[]
     */
    protected array $params;

    public function __construct(string ...$params)
    {
        $this->params = $params;
    }

    public function process(Request $request): void
    {
        foreach($this->params as $param) {
            if($request->hasParam($param) && empty($request->getParam($param))){
                $request->removeParam($param);
            } else{
                $request->setParam($param, []);
            }
        }
    }
}