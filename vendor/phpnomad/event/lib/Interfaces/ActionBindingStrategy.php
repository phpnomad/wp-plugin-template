<?php

namespace PHPNomad\Events\Interfaces;

interface ActionBindingStrategy
{
    public function bindAction(string $eventClass, string $actionToBind, ?callable $transformer = null);
}