<?php

namespace PHPNomad\Framework\Traits;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Datastore\Interfaces\ModelAdapter;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Rest\Enums\Method;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Response;

trait CreateController
{
    protected Response $response;
    protected Datastore $datastore;
    protected ModelAdapter $adapter;
    protected LoggerStrategy $logger;

    /**
     * Builds the attributes used to create the record
     *
     * @param Request $request
     * @return array
     */
    abstract protected function buildAttributes(Request $request): array;

    /** @inheritDoc */
    public function getResponse(Request $request): Response
    {
        try {
            $record = $this->datastore->create($this->buildAttributes($request));
            $this->response
                ->setJson($this->adapter->toArray($record))
                ->setStatus(201);
        } catch (DuplicateEntryException $e) {
            $this->response
                ->setError('Duplicate entry.', 409);
        } catch (DatastoreErrorException $e) {
            $this->logger->logException($e);

            $this->response
                ->setError('Something went wrong when trying to complete the request.', 500);
        }

        return $this->response;
    }

    public function getMethod(): string
    {
        return Method::Post;
    }
}