<?php

namespace PHPNomad\Cache\Enums;

use PHPNomad\Enum\Traits\Enum;

class Operation
{
    use Enum;

    public const Create = 'create';
    public const Read = 'read';
    public const Update = 'update';
    public const Delete = 'delete';

    /**
     * @param string $operation
     * @return bool
     */
    public static function isInvalidatingOperation(string $operation): bool
    {
        return in_array($operation, [static::Update, static::Delete]);
    }
}