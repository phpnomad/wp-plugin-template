<?php

namespace PHPNomad\Auth\Enums;

use PHPNomad\Enum\Traits\Enum;

class SessionContexts
{
    use Enum;

    public const Rest = 'rest';
    public const CommandLine = 'cli';
    public const Web = 'web';
    public const Ajax = 'ajax';
    public const BatchProcess = 'batchProcess';
    public const CronJob = 'cronJob';
    public const XmlRpc = 'xmlrpc';
    public const Admin = 'admin';
}