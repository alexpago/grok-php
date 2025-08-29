<?php

namespace Pago\Grok\Tests;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Responses\ChatResponse;
use Pago\Grok\Responses\ChatErrorResponse;
use Pago\Grok\Enums\Role;

/**
 * Grok chat test.
 */
class GrokChatTest extends TestCase
{
    /**
     * The Grok chat service.
     * @var GrokChat
     */
    private GrokChat $service;

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
        $this->service = new GrokChat($_ENV['GROK_API_KEY']);
    }

    /**
     * Test the simple chat.
     */
    public function testSimpleChatSuccess()
    {
        $message = $this->service->query('Hello, world!')->run();
        $this->assertInstanceOf(ChatResponse::class, $message);
        $this->assertNotEmpty($message->getContent());
    }

    /**
     * Test the multiple chat.
     */
    public function testMultipleChatSuccess()
    {
        $response = $this
            ->service
            ->query('2+2')
            ->query('4', Role::SYSTEM)
            ->query('write again only number', Role::USER)
            ->run();
        if ($response instanceof ChatErrorResponse) {
            $this->fail('Error: ' . $response->getMessage());
        }
        $this->assertEquals(4, (int)$response->getContent());
    }

    /**
     * Test the send function.
     * Answer should be text with content "hello".
     */
    public function testSendFunctionSuccess()
    {
        $response = $this->service->query('Write me: hello')->send();
        $this->assertStringContainsStringIgnoringCase('hello', $response);
    }

    /**
     * Test the timeout client fail.
     */
    public function testTimeoutClientFail()
    {
        $response = $this->service->setTimeout(1)->query('Hello')->run();
        $this->assertInstanceOf(ChatErrorResponse::class, $response);
    }
}
