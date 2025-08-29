<?php

namespace Pago\Grok\Responses;

/**
 * Grok chat response.
 */
final class ChatResponse
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
     * Get the ID response.
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->data['id'] ?? null;
    }

    /**
     * Get the model response.
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->data['model'] ?? null;
    }

    /**
     * Get the message response.
     * @return array|null
     */
    public function getMessage(): ?array
    {
        return $this->data['choices'][0]['message'] ?? null;
    }

    /**
     * Alias for getMessage.
     * @return string|null
     */
    public function getMessageContent(): ?string
    {
        return $this->getMessage()['content'] ?? null;
    }

    /**
     * Alias for getMessageContent.
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getMessageContent();
    }

    /**
     * Get the reasoning content response.
     * @return string|null
     */
    public function getMessageReasoningContent(): ?string
    {
        return $this->data['choices'][0]['message']['reasoning_content'] ?? null;
    }

    /**
     * Get the usage response.
     * @return array|null
     */
    public function getUsage(): ?array
    {
        return $this->data['usage'] ?? null;
    }

    /**
     * Get the response as an array.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'      => $this->getId(),
            'model'   => $this->getModel(),
            'message' => $this->getMessage(),
            'usage'   => $this->getUsage(),
        ];
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