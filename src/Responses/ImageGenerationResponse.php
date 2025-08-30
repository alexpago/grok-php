<?php

namespace Pago\Grok\Responses;

use Pago\Grok\Responses\Success\SuccessResponse;

/**
 * Grok image generation response.
 */
final class ImageGenerationResponse extends SuccessResponse
{
    /**
     * Get the image URL.
     * @param int $number The number of the image.
     * @return string|null
     */
    public function getImage(int $number = 1): ?string
    {
        return $this->data['data'][$number - 1]['url'] ?? null;
    }

    /**
     * Get the revised prompt.
     * @param int $number The number of the image.
     * @return string|null
     */
    public function getRevisedPrompt(int $number = 1): ?string
    {
        return $this->data['data'][$number - 1]['revised_prompt'] ?? null;
    }
}