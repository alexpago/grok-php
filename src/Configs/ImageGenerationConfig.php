<?php

namespace Pago\Grok\Configs;

use Pago\Grok\Enums\ImageGeneration\ImageModel;
use Pago\Grok\Enums\ImageGeneration\ImageOutputFormat;

/**
 * Image generation config.
 */
final class ImageGenerationConfig
{
    /**
     * Warning: Grok is currently returns only a URL as output format, 
     * but according to the documentation it should also be possible to use base64.
     * @param ImageModel|string|null $model The model to use for the request.
     * @param ImageOutputFormat|string|null $imageFormat The output format to use for the request.
     * @param int $timeout The timeout of the request.
     * @return void
     */
    public function __construct(
        public ImageModel|string|null $model = ImageModel::GROK_2_IMAGE,
        public ImageOutputFormat|string|null $imageFormat = ImageOutputFormat::URL,
        public int $timeout = 30
    ) {}

    /**
     * Get the model.
     * @return ImageModel|string
     */
    public function getModel(): ImageModel|string
    {
        return $this->model;
    }

    /**
     * Set the model.
     * @param ImageModel|string $model
     * @return self
     */
    public function setModel(ImageModel|string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get the image format.
     * @return ImageOutputFormat|string
     */
    public function getImageFormat(): ImageOutputFormat|string
    {
        return $this->imageFormat;
    }

    /**
     * Set the image format.
     * @param ImageOutputFormat|string $imageFormat
     * @return self
     */
    public function setImageFormat(ImageOutputFormat|string $imageFormat): self
    {
        $this->imageFormat = $imageFormat;
        return $this;
    }

    /**
     * Get the timeout.
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set the timeout.
     * @param int $timeout
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }
}
