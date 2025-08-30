<?php

namespace Pago\Grok\Client;

use Pago\Grok\Enums\ImageGeneration\ImageModel;
use Pago\Grok\Configs\ImageGenerationConfig;
use Pago\Grok\Enums\Uri;
use Pago\Grok\Responses\ImageGenerationResponse;    
use Pago\Grok\Responses\ImageGenerationErrorResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


final class GrokImageGeneration 
{
    /**
     * @param string $apiKey
     * @param Client $client
     */
    public function __construct(
        private string $apiKey,
        public ImageGenerationConfig $imageGenerationConfig = new ImageGenerationConfig(),
        private Client $client = new Client([
            'base_uri' => 'https://api.x.ai/',
        ]),
    ) {}

    /**
     * Get the image generation config.
     * @return ImageGenerationConfig
     */
    public function getConfig(): ImageGenerationConfig
    {
        return $this->imageGenerationConfig;
    }

    /**
     * Set the image generation config.
     * @param ImageGenerationConfig $imageGenerationConfig
     * @return self
     */
    public function setConfig(ImageGenerationConfig $imageGenerationConfig): self
    {
        $this->imageGenerationConfig = $imageGenerationConfig;
        return $this;
    }

    /**
     * Generate an image.
     * @param string $prompt The prompt to use for the request.
     * @param int $numberOfImages The number of images to generate. Default: 1.
     * @return ImageGenerationResponse|ImageGenerationErrorResponse
     */
    public function generate(
        string $prompt, 
        int $numberOfImages = 1, 
    ): ImageGenerationResponse|ImageGenerationErrorResponse
    {
        // 1. Get the model.
        $model = $this->imageGenerationConfig->model;
        if ($model instanceof ImageModel) {
            $model = $model->value;
        }

        // 2. Get the payload.
        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'image_format' => $this->imageGenerationConfig->imageFormat,
            'n' => $numberOfImages,
        ];

        // 3. Get the guzzle options.
        $client = $this->client;

        // 4. Send the request.
        try {
            $response = $client->post(Uri::IMAGE_GENERATION->value, $this->getGuzzleOptions($payload));
            $body = $response->getBody()->getContents();
            // Validate response.
            if (! json_validate($body)) {
                return new ImageGenerationErrorResponse('invalid_json', 'Invalid JSON');
            }
            $json = json_decode($body, true);
            // Check if the response is valid.
            if (! isset($json['data'])) {
                return new ImageGenerationErrorResponse(
                    ! empty($json['code']) && is_string($json['code']) ? $json['code'] : 'invalid_response',
                    ! empty($json['message']) && is_string($json['message']) ? $json['message'] : 'Invalid response'
                );
            }
            // Return response.
            return new ImageGenerationResponse($body);
        } catch (GuzzleException $e) {
            // Oops. Something went wrong.
            $code = $e->getCode();
            $message = $e->getMessage();
            return new ImageGenerationErrorResponse($code, $message, true);
        }
    }

    /**
     * Get the headers for the request.
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get the Guzzle options for the request.
     * @param bool $stream
     * @return array
     */
    private function getGuzzleOptions(array $payload): array
    {
        return [
            'json' => $payload,
            'timeout' => $this->getConfig()->timeout,
            'headers' => $this->getHeaders(),
        ];
    }
}