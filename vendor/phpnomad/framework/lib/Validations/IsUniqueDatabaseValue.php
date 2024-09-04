<?php

namespace PHPNomad\Framework\Validations;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Rest\Exceptions\ValidationException;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;

class IsUniqueDatabaseValue implements Validation
{
    protected Datastore $datastore;
    protected ?string $existingKey;

    /**
     * @param Datastore $datastore
     * @param string|null $existingKey If an existing key is specified, this will return true if the existing record currently has the tested value.
     */
    public function __construct(Datastore $datastore, ?string $existingKey = null)
    {
        $this->datastore = $datastore;
        $this->existingKey = $existingKey;
    }

    /**
     * @param string $key
     * @param Request $request
     * @return bool
     * @throws ValidationException
     */
    public function isValid(string $key, Request $request): bool
    {
        if ($this->existingKey) {
            try {
                $existing = $this->datastore->findBy($this->existingKey, $request->getParam($this->existingKey));
            } catch (DatastoreErrorException $e) {
                throw new ValidationException('Something went wrong when validating the uniqueness of a field: failed to find existing record.', [], 500);
            }
        }

        try {
            $found = $this->datastore->findBy($key, $request->getParam($key));

            // If this is the same record, this is valid.
            if (isset($existing) && $found->getIdentity() === $existing->getIdentity()) {
                return true;
            }

            return false;
        } catch (RecordNotFoundException $e) {
            return true;
        } catch (DatastoreErrorException $e) {
            throw new ValidationException('Something went wrong when validating the uniqueness of a field.', [], 500);
        }
    }

    public function getErrorMessage(string $key, Request $request): string
    {
        return "$key already exists.";
    }

    public function getType(): string
    {
        return 'DUPLICATE_ENTRY';
    }

    public function getContext(): array
    {
        return [];
    }
}