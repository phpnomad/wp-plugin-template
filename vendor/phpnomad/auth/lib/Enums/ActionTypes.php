<?php

namespace PHPNomad\Auth\Enums;

use PHPNomad\Enum\Traits\Enum;

class ActionTypes
{
    use Enum;

    public const Create = 'create';
    public const Read = 'read';
    public const Update = 'update';
    public const Delete = 'delete';
}