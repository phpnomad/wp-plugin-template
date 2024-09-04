<?php

namespace PHPNomad\Core\Abstracts;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Events\Interfaces\CanHandle;
use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Utils\Helpers\Arr;
use Siren\Conversions\Core\Models\Conversion;
use Siren\Mappings\Core\Datastores\Mapping\Interfaces\MappingDatastore;

abstract class RemoveDeletedMappingsEvent implements CanHandle
{
    protected MappingDatastore $mappingDatastore;

    public function __construct(MappingDatastore $mappingDatastore)
    {
        $this->mappingDatastore = $mappingDatastore;
    }

    /**
     * Deletes the user mapping and the user account when a collaborator is deleted.
     *
     * @param Event $event
     * @return void
     * @throws DatastoreErrorException
     */
    protected function deleteMapping(Event $event)
    {
        $id = Arr::get($event->getIdentity(), 'id');

        if ($id) {
            $this->mappingDatastore->deleteMappingsForLocalId($id, $this->getLocalType());
        }
    }

    public function handle(Event $event): void
    {
        if (Conversion::class === $event->getType()) {
            $this->deleteMapping($event);
        }
    }

    abstract protected function getLocalType(): string;
}