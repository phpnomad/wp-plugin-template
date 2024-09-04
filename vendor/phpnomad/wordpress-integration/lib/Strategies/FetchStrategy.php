<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Integrations\WordPress\Rest\Response;
use PHPNomad\Rest\Enums\Method;
use PHPNomad\Rest\Interfaces\FetchStrategy as FetchStrategyInterface;
use PHPNomad\Rest\Models\FetchPayload;

class FetchStrategy implements FetchStrategyInterface
{
    public function fetch(FetchPayload $payload): Response
    {
        $url = $payload->getUrl();

        // Initialize a new WordPress HTTP request
        $args = [
            'method' => $payload->getMethod(),
            'headers' => $payload->getHeaders(),
            'body' => $payload->getBody(),
            'data' => $payload->getParams(),
        ];

        $params = $payload->getParams();
        if (!empty($params)) {
            $url = add_query_arg($params, $url);
        }

        // Use WordPress's wp_remote_request function to make the HTTP request
        $response = wp_remote_request($url, $args);

        // Initialize a Response object
        $wpResponse = new Response();

        // Check for errors
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            return $wpResponse->setError($error_message, 500);
        }

        // Set the response status code
        $wpResponse->setStatus(wp_remote_retrieve_response_code($response));

        // Set the response headers
        $headers = wp_remote_retrieve_headers($response);
        if (is_array($headers)) {
            foreach ($headers as $name => $value) {
                $wpResponse->setHeader($name, $value);
            }
        }

        // Set the response body
        $body = wp_remote_retrieve_body($response);
        $wpResponse->setBody($body);

        return $wpResponse;
    }
}