<?php

namespace PHPNomad\Framework\Traits;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Datastore\Interfaces\ModelAdapter;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Rest\Enums\Method;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Response;
use Siren\Collaborators\Core\Datastores\Collaborator\Interfaces\CollaboratorDatastore;
use Siren\Collaborators\Core\Models\Adapters\CollaboratorAdapter;

trait UpdateController
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
            $attributes = $this->buildAttributes($request);

            // Updating a record when there's nothing to update throws an exception.
            // This ensures that the request continues even if nothing updates.
            if(!empty($attributes)) {
                $this->datastore->update($request->getParam('id'), $this->buildAttributes($request));
            }

            $this->response->setStatus(200);
        } catch (RecordNotFoundException $e) {
            $this->response
                ->setError('No record found with that ID.', 404);
        } catch (DatastoreErrorException $e) {
            $this->logger->logException($e);

            $this->response
                ->setError('Something went wrong when trying to complete the request.', 500);
        }

        return $this->response;
    }

    public function getMethod(): string
    {
        return Method::Put;
    }
}