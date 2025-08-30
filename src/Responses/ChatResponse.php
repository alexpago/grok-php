<?php

namespace Pago\Grok\Responses;

use Pago\Grok\Responses\Success\SuccessResponse;

/**
 * Grok chat response.
 */
final class ChatResponse extends SuccessResponse
{
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
}