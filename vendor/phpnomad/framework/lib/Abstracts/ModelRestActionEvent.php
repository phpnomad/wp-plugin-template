<?php

namespace PHPNomad\Framework\Abstracts;

use PHPNomad\Auth\Enums\ActionTypes;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Response;
use PHPNomad\Rest\Interfaces\RestActionEvent;

abstract class ModelRestActionEvent implements RestActionEvent
{
    protected Response $response;
    protected static string $model;
    protected string $action;
    protected Request $request;

    /**
     * @param ActionTypes::*&string $action
     * @param Request $request
     * @param Response $response
     */
    public function __construct(string $action, Request $request, Response $response)
    {
        $this->action = $action;
        $this->request = $request;
        $this->response = $response;
    }

    public static function getId(): string
    {
        return static::$model . '_model_rest_action_initiated';
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}