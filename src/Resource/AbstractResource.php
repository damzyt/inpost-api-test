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

    public function __construct(protected GuzzleClient $guzzleClient) 
    {}

    protected function postRequest(string $uri, array $data = []): array
    {
        return $this -> executeRequest('POST', $uri, ['json' => $data]);
    }

    protected function getRequest(string $uri, array $data = []): array
    {
        return $this -> executeRequest('GET', $uri, ['query' => $data]);
    }

    protected function executeRequest(string $method, string $uri, array $options = []): array
    {
        $delay = self::INITIAL_DELAY_SECONDS;

        for($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $response = $this -> guzzleClient -> request($method, $uri, $options);
                $responseBody = $response -> getBody() -> getContents();

                if(empty($responseBody)) {
                    return [];
                }

                return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
            } catch (RequestException $e) {
                if(!$e -> hasResponse()) {
                    if($attempt === self::MAX_ATTEMPTS) {
                        throw new InPostApiException("Error after {$attempt} attempts: " . $e -> getMessage(), 0, $e);
                    }

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }

                $response = $e -> getResponse();
                $statusCode = $response -> getStatusCode();

                if($statusCode >= 400 && $statusCode < 500) {
                    throw new InPostApiException("API client error ({$statusCode}): " . $response -> getBody() -> getContents(), $statusCode, $e, $response);
                }

                if($statusCode >= 500) {
                    if($attempt === self::MAX_ATTEMPTS) {
                        throw new InPostApiException("Server error ({$statusCode}) after {$attempt} attempts: " . $response -> getBody() -> getContents(), $statusCode, $e, $response);
                    }

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }
                throw new InPostApiException("Unexpected error: " . $e -> getMessage(), 0, $e, $response);
            } catch (JsonException $e) {
                throw new InPostApiException("Failed to decode JSON response.", 0, $e);
            }
        }

        throw new InPostApiException("Failed to execute request after " . self::MAX_ATTEMPTS . " attempts.");
    }
}
