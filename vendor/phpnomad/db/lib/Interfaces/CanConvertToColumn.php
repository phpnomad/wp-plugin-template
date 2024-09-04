<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Factories\Column;

interface CanConvertToColumn
{
    public function toColumn(): Column;
}