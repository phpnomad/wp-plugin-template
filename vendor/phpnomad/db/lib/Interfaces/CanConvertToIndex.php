<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Factories\Index;

interface CanConvertToIndex
{
    public function toIndex(): Index;
}