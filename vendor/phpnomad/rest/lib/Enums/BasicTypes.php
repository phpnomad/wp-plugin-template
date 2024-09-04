<?php

namespace PHPNomad\Rest\Enums;

use PHPNomad\Enum\Traits\Enum;

class BasicTypes
{
    use Enum;

    public const Boolean = "boolean";
    public const Integer = "integer";
    public const Float = "double";
    public const String = "string";
    public const Array = "array";
    public const Object = "object";
    public const Null = "null";
}