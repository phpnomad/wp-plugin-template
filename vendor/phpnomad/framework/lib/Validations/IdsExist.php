<?php

namespace PHPNomad\Framework\Validations;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Interfaces\DatastoreHasPrimaryKey;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Rest\Exceptions\RestException;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Validation;
use PHPNomad\Utils\Helpers\Arr;
use Siren\Collaborators\Core\Models\Program;

class IdsExist implements Validation
{
    protected DatastoreHasPrimaryKey $datastore;
    protected LoggerStrategy $logger;
    protected array $invalidIds = [];

    public function __construct(DatastoreHasPrimaryKey $datastore, LoggerStrategy $loggerStrategy)
    {
        $this->logger = $loggerStrategy;
        $this->datastore = $datastore;
    }

    /**
     * @inheritDoc
     * @throws RestException
     */
    public function isValid(string $key, Request $request): bool
    {
        try {
            $found = $this->datastore->findMultiple($request->getParam($key));
            $this->invalidIds = Arr::process($found)
                ->map(fn(Program $program) => $program->getId())
                ->diff($request->getParam($key))
                ->toArray();

            return empty($this->invalidIds);
        } catch (DatastoreErrorException $e) {
            $this->logger->logException($e);
            throw new RestException('Something went wrong when validating IDs',[
                'ids' => $request->getParam($key)
            ], 500);
        }
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(string $key, Request $request): string
    {
        return "Key $key is invalid because some of the provided IDs cannot be found.";
    }

    /**
     * @inheritDoc
     */
    public function getContext(): array
    {
        return [
            'invalidProgramIds' => $this->invalidIds
        ];
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'INVALID_IDS';
    }
}