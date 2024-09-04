<?php

namespace PHPNomad\Database\Events;

use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Events\Interfaces\Event;

class RecordCreated implements Event
{
    protected DataModel $record;

    public function __construct(DataModel $record)
    {
        $this->record = $record;
    }

    public function getRecord(): DataModel
    {
        return $this->record;
    }

    public static function getId(): string
    {
        return 'record_created';
    }
}