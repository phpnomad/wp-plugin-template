<?php

namespace PHPNomad\Framework\Middlewares;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Rest\Exceptions\RestException;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class RecordExistsMiddleware implements Middleware
{
    protected Datastore $datastore;
    protected string $key;

    public function __construct(Datastore $datastore, string $key)
    {
        $this->datastore = $datastore;
        $this->key = $key;
    }

    public function process(Request $request): void
    {
        try {
            $this->datastore->findBy($this->key, $request->getParam($this->key));
        } catch (RecordNotFoundException $e) {
            throw new RestException('The specified record does not exist.', [
                'identifier' => $this->key,
                'value' => $request->getParam($this->key)
            ], 404);
        } catch (DatastoreErrorException $e) {
            throw new RestException('Something went wrong when validating the record.', [
                'identifier' => $this->key,
                'value' => $request->getParam($this->key)
            ], 500);
        }
    }
}