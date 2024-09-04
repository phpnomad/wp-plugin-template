<?php

namespace PHPNomad\Rest\Middleware;

use PHPNomad\Rest\Enums\BasicTypes;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class SetTypeMiddleware implements Middleware
{
    protected string $type;
    protected string $field;

    /**
     * @param string $field
     * @param BasicTypes::* $type
     */
    public function __construct(string $field, string $type)
    {
        $this->field = $field;
        $this->type = $type;
    }

    public function process(Request $request): void
    {
        $param = $request->getParam($this->field);

        if ($param !== null) {
            settype($param, $this->type);
            $request->setParam($this->field, $param);
        }
    }
}