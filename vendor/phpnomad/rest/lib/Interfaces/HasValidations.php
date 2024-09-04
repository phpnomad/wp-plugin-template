<?php

namespace PHPNomad\Rest\Interfaces;


use PHPNomad\Rest\Factories\ValidationSet;

interface HasValidations
{
    /**
     * @return array<string, ValidationSet> Validation set keyed by the field it validates against.
     */
    public function getValidations(): array;
}