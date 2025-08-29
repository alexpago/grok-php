<?php

namespace Pago\Grok\Responses;

/**
 * Grok chat error response.
 */
final class ChatErrorResponse
{
    /**
     * @param string $code The error code.
     * @param string $message The error message.
     */
    public function __construct(
        public string $code,
        public string $message,
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
}