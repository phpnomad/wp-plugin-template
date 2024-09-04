<?php

namespace PHPNomad\Database\Events;

use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Events\Interfaces\Event;

class RecordDeleted implements Event
{
    protected string $type;
    protected array $identity;

    /**
     * @param class-string<DataModel>|string $type
     * @param array $identity
     */
    public function __construct(string $type, array $identity)
    {
        $this->type = $type;
        $this->identity = $identity;
    }

    /**
     * Gets the identity for the record that was deleted.
     *
     * @return array
     */
    public function getIdentity(): array
    {
        return $this->identity;
    }

    /**
     * Gets the model type this record was deleted from.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public static function getId(): string
    {
        return 'record_deleted';
    }
}