<?php

declare(strict_types=1);

namespace App\Resource;

use App\Exception\InPostApiException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use JsonException;

/**
 * Class AbstractResource
 * Base class for all resources interacting with the InPost API.
 * Provides common functionality for making API requests with retry logic.
 * 
 * @package App\Resource
 */
class AbstractResource
{
    protected const MAX_ATTEMPTS = 3;
    protected const INITIAL_DELAY_SECONDS = 1;

    /**
     * Constructor for AbstractResource.
     * 
     * Initializes the resource with a Guzzle HTTP client instance.
     * 
     * @param GuzzleClient $guzzleClient The Guzzle HTTP client for making API requests
     */
    public function __construct(protected GuzzleClient $guzzleClient) 
    {}

    /**
     * Execute a POST request to the InPost API.
     * 
     * Sends a POST request with JSON data to the specified URI endpoint.
     * Uses the executeRequest method with retry logic.
     * 
     * @param string $uri The API endpoint URI to send the request to
     * @param array $data The data to send as JSON in the request body
     * @return array The decoded JSON response from the API
     * @throws InPostApiException When the API request fails
     */
    protected function postRequest(string $uri, array $data = []): array
    {
        return $this->executeRequest('POST', $uri, ['json' => $data]);
    }

    /**
     * Execute a GET request to the InPost API.
     * 
     * Sends a GET request with query parameters to the specified URI endpoint.
     * Uses the executeRequest method with retry logic.
     * 
     * @param string $uri The API endpoint URI to send the request to
     * @param array $data The data to send as query parameters
     * @return array The decoded JSON response from the API
     * @throws InPostApiException When the API request fails
     */
    protected function getRequest(string $uri, array $data = []): array
    {
        return $this->executeRequest('GET', $uri, ['query' => $data]);
    }

    /**
     * Execute an HTTP request with retry logic and error handling.
     * 
     * Implements exponential backoff retry strategy for handling temporary failures.
     * Retries up to MAX_ATTEMPTS times with increasing delays between attempts.
     * Handles different HTTP status codes appropriately:
     * - 4xx: Client errors (no retry)
     * - 5xx: Server errors (retry with backoff)
     * 
     * @param string $method The HTTP method (GET, POST, etc.)
     * @param string $uri The API endpoint URI
     * @param array $options The Guzzle request options (headers, body, etc.)
     * @return array The decoded JSON response from the API
     * @throws InPostApiException When the request fails after all retry attempts or encounters client errors
     */
    protected function executeRequest(string $method, string $uri, array $options = []): array
    {
        $delay = self::INITIAL_DELAY_SECONDS;

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $response = $this->guzzleClient->request($method, $uri, $options);
                $responseBody = $response->getBody()->getContents();

                if (empty($responseBody)) {
                    return [];
                }

                return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
            } catch (RequestException $e) {
                if (!$e->hasResponse()) {
                    if ($attempt === self::MAX_ATTEMPTS) {
                        throw new InPostApiException("Error after {$attempt} attempts: " . $e->getMessage(), 0, $e);
                    }

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }

                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();

                if ($statusCode >= 400 && $statusCode < 500) {
                    throw new InPostApiException("API client error ({$statusCode}): " . $response->getBody()->getContents(), $statusCode, $e, $response);
                }

                if ($statusCode >= 500) {
                    if ($attempt === self::MAX_ATTEMPTS) {
                        throw new InPostApiException("Server error ({$statusCode}) after {$attempt} attempts: " . $response->getBody()->getContents(), $statusCode, $e, $response);
                    }

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }
                throw new InPostApiException("Unexpected error: " . $e->getMessage(), 0, $e, $response);
            } catch (JsonException $e) {
                throw new InPostApiException("Failed to decode JSON response.", 0, $e);
            }
        }

        throw new InPostApiException("Failed to execute request after " . self::MAX_ATTEMPTS . " attempts.");
    }
}
