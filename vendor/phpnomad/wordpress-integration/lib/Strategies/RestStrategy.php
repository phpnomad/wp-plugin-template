<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use Exception;
use PHPNomad\Auth\Interfaces\CurrentUserResolverStrategy;
use PHPNomad\Integrations\WordPress\Rest\Request as WordPressRequest;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Rest\Exceptions\RestException;
use PHPNomad\Rest\Interfaces\Controller;
use PHPNomad\Rest\Interfaces\HasInterceptors;
use PHPNomad\Rest\Interfaces\HasMiddleware;
use PHPNomad\Rest\Interfaces\HasRestNamespace;
use PHPNomad\Rest\Interfaces\Interceptor;
use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Rest\Interfaces\Response;
use PHPNomad\Rest\Interfaces\RestStrategy as CoreRestStrategy;
use PHPNomad\Utils\Helpers\Arr;
use WP_REST_Request;
use WP_REST_Response;

//TODO: EXTRACT VALIDATION AND MIDDLEWARE SO THIS LOGIC CAN BE RE-USED IN OTHER SERVICES.
class RestStrategy implements CoreRestStrategy
{
    /**
     * @var HasRestNamespace
     */
    protected HasRestNamespace $restNamespaceProvider;
    protected Response $response;
    protected CurrentUserResolverStrategy $currentUserResolver;
    protected LoggerStrategy $logger;

    public function __construct(HasRestNamespace $restNamespaceProvider, Response $response, CurrentUserResolverStrategy $currentUserResolverStrategy, LoggerStrategy $loggerStrategy)
    {
        $this->logger = $loggerStrategy;
        $this->restNamespaceProvider = $restNamespaceProvider;
        $this->response = $response;
        $this->currentUserResolver = $currentUserResolverStrategy;
    }

    /**
     * @param Controller $controller
     * @param Response $request
     * @return WP_REST_Response
     * @throws RestException
     */
    private function wrapCallback(Controller $controller, Request $request): Response
    {
        // Maybe process middleware.
        if ($controller instanceof HasMiddleware) {
            Arr::each($controller->getMiddleware($request), fn(Middleware $middleware) => $middleware->process($request));
        }

        /** @var \PHPNomad\Integrations\WordPress\Rest\Response $response */
        $response = $controller->getResponse($request);

        // Maybe process interceptors.
        if ($controller instanceof HasInterceptors) {
            Arr::each($controller->getInterceptors($request, $response), fn(Interceptor $interceptor) => $interceptor->process($request, $response));
        }

        return $response;
    }

    /**
     * Converts the endpoint format to match WordPress format.
     *
     * @param string $input
     * @return string
     */
    private function convertEndpointFormat(string $input): string
    {
        return preg_replace('/\{([\w-]+)}/', '(?P<$1>[\w-]+)', $input);
    }

    protected function handleRequest(Controller $controller, Request $request)
    {
        try {
            $response = $this->wrapCallback($controller, $request);
        } catch (RestException $e) {
            $response = $this->response->setStatus($e->getCode())->setJson(
                [
                    'error' => [
                        'message' => $e->getMessage(),
                        'context' => $e->getContext()
                    ],
                ]
            );
        } catch(Exception $e){
            $this->logger->logException($e);
            $response = $this->response->setStatus(500)->setJson(
        		[
        			'error' => [
        				'message' => 'An unexpected error occurred. Sorry, that is all I know.',
        			],
        		]
        	);
        }

        return $response->getResponse();
    }

    /** @inheritDoc */
    public function registerRoute(callable $controllerGetter)
    {
        add_action('rest_api_init', function () use ($controllerGetter) {
            /** @var Controller $controller */
            $controller = $controllerGetter();

            register_rest_route(
                $this->restNamespaceProvider->getRestNamespace(),
                $this->convertEndpointFormat($controller->getEndpoint()),
                [
                    'methods' => $controller->getMethod(),
                    'callback' => fn(WP_REST_Request $request) => $this->handleRequest($controller, new WordPressRequest($request, $this->currentUserResolver->getCurrentUser())),
                    'permission_callback' => '__return_true'
                ]
            );
        });
    }
}
