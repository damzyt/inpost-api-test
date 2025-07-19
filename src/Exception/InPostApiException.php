<?php

declare(strict_types=1);

namespace App\Exception;

use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;

/**
 * Class InPostApiException
 * Custom exception for handling InPost API errors.
 */
class InPostApiException extends Exception
{
    private ?ResponseInterface $response;

    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, ?ResponseInterface $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this -> response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this -> response;
    }

    public function getResponseBodyAsString(): string
    {
        if($this -> response) {
            $this -> response -> getBody() -> rewind();
            return $this -> response -> getBody() -> getContents();
        }

        return '';
    }
}
