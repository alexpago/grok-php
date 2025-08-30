<?php

namespace Pago\Grok\Tests;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Pago\Grok\Client\GrokImageGeneration;
use Pago\Grok\Responses\ImageGenerationResponse;
use Pago\Grok\Responses\ImageGenerationErrorResponse;
use Pago\Grok\Configs\ImageGenerationConfig;

/**
 * Image generation test.
 */
class ImageGenerationTest extends TestCase
{
    /**
     * The Grok chat service.
     * @var GrokImageGeneration
     */
    private GrokImageGeneration $service;

    /**
     * Load the environment variables.
     */
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    /**
     * Set up the test.
     */
    protected function setUp(): void
    {
        $this->service = new GrokImageGeneration($_ENV['GROK_API_KEY']);
    }

    /**
     * Test the image generation.
     */
    public function testImageGeneration()
    {
        $response = $this->service->generate('A beautiful sunset over a calm ocean', 1);
        $this->assertInstanceOf(ImageGenerationResponse::class, $response);
        $this->assertNotEmpty($response->getImage());
        $this->assertNotEmpty($response->getRevisedPrompt());
    }

    /**
     * Test the timeout client fail.
     */
    public function testImageGenerationFail()
    {
        $client = $this->service;
        $client->setConfig(new ImageGenerationConfig(model: 'invalid_model'));
        $response = $client->generate('A beautiful sunset over a calm ocean', 1);
        $this->assertInstanceOf(ImageGenerationErrorResponse::class, $response);
        $this->assertNotEmpty($response->getMessage());
    }
}