<?php

namespace App\Resource;

use App\Exception\InPostApiException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use JsonException;

abstract readonly class AbstractResource
{
    protected const MAX_ATTEMPTS = 3;
    protected const INITIAL_DELAY_SECONDS = 1;

    public function __construct(protected GuzzleClient $guzzleClient) {}

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
                    if($attempt === self::MAX_ATTEMPTS) throw new InPostApiException("Błąd sieciowy po {$attempt} próbach.", 0, $e);

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }

                $response = $e -> getResponse();
                $statusCode = $response -> getStatusCode();

                if($statusCode >= 400 && $statusCode < 500) {
                    throw new InPostApiException("Błąd klienta API ({$statusCode}): " . $response -> getBody() -> getContents(), $statusCode, $e, $response);
                }

                if($statusCode >= 500) {
                    if($attempt === self::MAX_ATTEMPTS) throw new InPostApiException("Błąd serwera API ({$statusCode}) po {$attempt} próbach.", $statusCode, $e, $response);

                    sleep($delay);
                    $delay *= 2;

                    continue;
                }
                throw new InPostApiException("Nieoczekiwany błąd.", 0, $e, $response);
            } catch (JsonException $e) {
                throw new InPostApiException("Nie udało się zdekodować odpowiedzi JSON.", 0, $e);
            }
        }

        throw new InPostApiException("Nie udało się wykonać zapytania po " . self::MAX_ATTEMPTS . " próbach.");
    }
}
