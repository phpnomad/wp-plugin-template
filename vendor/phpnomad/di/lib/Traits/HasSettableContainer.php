<?php

namespace PHPNomad\Di\Traits;

use PHPNomad\Di\Interfaces\InstanceProvider;

trait HasSettableContainer
{
    protected InstanceProvider $container;

    /**
     * @param InstanceProvider $container
     * @return $this
     */
    public function setContainer(InstanceProvider $container)
    {
        $this->container = $container;

        return $this;
    }
}