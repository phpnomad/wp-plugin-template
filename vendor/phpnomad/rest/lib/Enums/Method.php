<?php

namespace PHPNomad\Rest\Enums;

use PHPNomad\Enum\Traits\Enum;

class Method
{
    use Enum;

    public const Get = 'GET';
    public const Post = 'POST';
    public const Put = 'PUT';
    public const Delete = 'DELETE';
    public const Patch = 'PATCH';
    public const Options = 'OPTIONS';
}