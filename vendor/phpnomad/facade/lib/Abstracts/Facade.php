<?php

namespace PHPNomad\Facade\Abstracts;

use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

/**
 * @template TAbstraction of object
 */
abstract class Facade implements CanSetContainer
{
    use HasSettableContainer;

    /**
     * @return class-string<TAbstraction>
     */
    abstract protected function abstractInstance(): string;

    /**
     * @return TAbstraction
     */
    protected function getContainedInstance()
    {
        return $this->getContainerInstance($this->abstractInstance());
    }

    protected function getContainerInstance(string $instance)
    {
        try {
            $result = $this->container->get($instance);
        } catch (DiException $e) {
            // Try to log something, if the container happens to have it.
            $this->container->get(LoggerStrategy::class)->critical(
                $e->getMessage(),
                ['container' => $this->container, 'abstract' => $this->abstractInstance()]
            );
            throw $e;
        }

        return $result;
    }
}