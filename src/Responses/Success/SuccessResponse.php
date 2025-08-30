<?php

namespace Pago\Grok\Responses\Success;

/**
 * Grok success response.
 */
class SuccessResponse
{
    /**
     * @var array
     */
    public array $data;

    /**
     * @param string $json The JSON response.
     */
    public function __construct(string $json)
    {
        // 1. Decode the JSON.
        $decoded = json_decode($json, true);
        if (false === $decoded) {
            throw new \Exception('Invalid JSON: ' . json_last_error_msg());
        }

        // 2. Set the data.
        $this->data = $decoded;
    }

    /**
     * Get the response as an array.
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Get the response as a JSON string.
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}