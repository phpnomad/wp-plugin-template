<?php
namespace PHPNomad\Framework\Traits;

use PHPNomad\Database\Exceptions\TableCreateFailedException;
use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Database\Interfaces\TableCreateStrategy;
use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Di\Interfaces\InstanceProvider;
use PHPNomad\Logger\Interfaces\LoggerStrategy;

trait CanInstallTables{

    protected InstanceProvider $container;

    /**
     * @return Table[]
     */
    abstract protected function getTablesToInstall():array;

    /**
     * @return TableCreateStrategy
     * @throws DiException
     */
    protected function getTableCreateStrategy(): TableCreateStrategy
    {
        return $this->container->get(TableCreateStrategy::class);
    }

    /**
     * @param class-string<Table> $instance
     * @return Table
     * @throws DiException
     */
    protected function getTableInstance(string $instance): Table
    {
        return $this->container->get($instance);
    }

    /**
     * Attempt to log the exception.
     *
     * @return void
     */
    protected function tryToLogException($e, $table)
    {
        try {
            $this->container->get(LoggerStrategy::class)->logException($e, 'Failed to install', ['table' => $table]);
        } catch (DiException $e) {
        }
    }

    /**
     * @return void
     */
    protected function createTables(): void
    {
        foreach ($this->getTablesToInstall() as $table) {
            try {
                $this->getTableCreateStrategy()->create($this->getTableInstance($table));
            } catch (DiException|TableCreateFailedException $e) {
                $this->tryToLogException($e, $table);
            }
        }
    }

}