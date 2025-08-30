<?php

namespace Pago\Grok\Responses\Error;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Grok error response.
 */
class ErrorResponse
{
    /**
     * @param string $code The error code.
     * @param string $message The error message.
     */
    public function __construct(
        public string $code,
        public string $message,
        public bool $requestError = false,
        public ?GuzzleException $requestException = null,
    ) {}

    /**
     * Get the error response as an array.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }

    /**
     * Get the error code.
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the error message.
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the request error.
     * @return bool
     */
    public function isRequestError(): bool
    {
        return $this->requestError;
    }

    /**
     * Get the request exception.
     * @return ?GuzzleException
     */
    public function getRequestException(): ?GuzzleException
    {
        return $this->requestException;
    }
}
