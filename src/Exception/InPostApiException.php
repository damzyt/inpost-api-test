<?php

declare(strict_types=1);

namespace App\Exception;

use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;

/**
 * Class InPostApiException
 * Custom exception for handling InPost API errors.
 * 
 * Extends the base Exception class to provide additional functionality
 * for handling HTTP responses and API-specific error information.
 * 
 * @package App\Exception
 */
class InPostApiException extends Exception
{
    private ?ResponseInterface $response;

    /**
     * Constructor for InPostApiException.
     * 
     * Creates a new API exception with an optional HTTP response object
     * for additional error context and debugging information.
     * 
     * @param string $message The exception message
     * @param int $code The exception code (typically HTTP status code)
     * @param Throwable|null $previous The previous exception for exception chaining
     * @param ResponseInterface|null $response The HTTP response object for additional context
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, ?ResponseInterface $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    /**
     * Get the HTTP response object associated with this exception.
     * 
     * Returns the response object that caused this exception, if available.
     * Useful for accessing response headers, status codes, and body content.
     * 
     * @return ResponseInterface|null The HTTP response object or null if not available
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the response body content as a string.
     * 
     * Extracts and returns the body content from the HTTP response.
     * Rewinds the response body stream before reading to ensure complete content.
     * 
     * @return string The response body content or empty string if no response available
     */
    public function getResponseBodyAsString(): string
    {
        if ($this->response) {
            $this->response->getBody()->rewind();
            return $this->response->getBody()->getContents();
        }

        return '';
    }
}
