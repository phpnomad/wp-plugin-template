<?php

namespace PHPNomad\Logger\Traits;

use Exception;
use PHPNomad\Logger\Enums\LoggerLevel;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

trait CanLogException
{
    public function logException(Exception $e, string $message = '', array $context = [], $level = null)
    {
        if(!$level){
            $level = LoggerLevel::Critical;
        }

        $this->$level(implode(' - ', [$message, $e->getMessage()]), $context);
    }
}