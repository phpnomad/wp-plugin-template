<?php

namespace PHPNomad\Rest\Middleware;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\Session as SessionInterface;
use PHPNomad\Auth\Models\Action;
use PHPNomad\Auth\Models\Policies\SessionTypePolicy;
use PHPNomad\Auth\Models\Policies\UserCanDoActionPolicy;
use PHPNomad\Auth\Models\Session;
use PHPNomad\Auth\Services\AuthPolicyEvaluatorService;
use PHPNomad\Rest\Exceptions\AuthorizationException;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class AuthorizationMiddleware implements Middleware
{

    protected array $policies;
    protected ?string $errorMessage;
    protected Action $action;
    protected string $context;
    protected AuthPolicyEvaluatorService $authPolicyEvaluatorService;

    /**
     * Initializes a new instance of the class.
     *
     * @param string $context The context for the instance.
     * @param Action $action The action object for the instance.
     * @param array $policies The array of policies for the instance. Leave empty for no auth.
     * @param string|null $errorMessage The optional error message for the instance. Defaults to null.
     */
    public function __construct(AuthPolicyEvaluatorService $authPolicyEvaluatorService, string $context, Action $action, array $policies = [], ?string $errorMessage = null)
    {
        $this->authPolicyEvaluatorService = $authPolicyEvaluatorService;
        $this->policies = $policies;
        $this->errorMessage = $errorMessage;
        $this->action = $action;
        $this->context = $context;
    }

    protected function getPolicies(): array
    {
        return array_merge([
            new SessionTypePolicy(SessionContexts::Rest),
            new UserCanDoActionPolicy()
        ], $this->policies);
    }

    protected function buildSession(): SessionInterface
    {
        return new Session($this->action, $this->context);
    }

    public function process(Request $request): void
    {
        if(!$request->getUser()){
            throw new AuthorizationException($this->errorMessage ?? "You don't have permission to do that.");
        }

        if (!$this->authPolicyEvaluatorService->evaluatePolicies($this->getPolicies(), $request->getUser(), $this->buildSession())) {
            throw new AuthorizationException($this->errorMessage ?? "You don't have permission to do that.");
        }
    }
}