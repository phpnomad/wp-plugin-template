<?php

namespace PHPNomad\Di\Interfaces;

interface CanSetContainer
{
    /**
     * @param InstanceProvider $container
     * @return $this
     */
    public function setContainer(InstanceProvider $container);
}