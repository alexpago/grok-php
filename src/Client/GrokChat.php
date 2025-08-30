<?php

namespace Pago\Grok\Client;

use GuzzleHttp\Client;
use Pago\Grok\Configs\ChatConfig;
use Pago\Grok\Enums\Model;
use Pago\Grok\Enums\Temperature;
use Pago\Grok\Enums\Role;
use Pago\Grok\Traits\Clientable;
use GuzzleHttp\Exception\GuzzleException;
use Pago\Grok\Responses\ChatResponse;
use Pago\Grok\Responses\ChatErrorResponse;
use Pago\Grok\Enums\Uri;
use Pago\Grok\Enums\ImageDetail;

/**
 * Grok chat.
 */
final class GrokChat
{
    use Clientable;

    /**
     * @var array<int, array{role: Role, content: string}>
     */
    private array $messages = [];

    /**
     * @param string $apiKey
     * @param string $baseUrl
     * @param ChatConfig $chatConfig
     * @param Client $client
     */
    public function __construct(
        #[\SensitiveParameter]
        private string $apiKey,
        public ChatConfig $chatConfig = new ChatConfig(),
        private Client $client = new Client([
            'base_uri' => 'https://api.x.ai/',
        ]),
    ) {}

    /**
     * Add a message to the chat.
     * @param string $content
     * @param Role|string $role
     * @return self
     */
    public function query(string $content, Role|string $role = Role::USER): self
    {
        $this->messages[] = [
            'role' => $role instanceof Role ? $role->value : $role,
            'content' => $content,
        ];
        return $this;
    }

    /**
     * Query an image.
     * @param string $image External URL of the image or base64-encoded image data. PNG or JPEG only. Maximum size: 20MB.
     * @param ImageDetail $imageDetail Optional image detail level (default: MEDIUM).
     * @param string $text Optional text to accompany the image (example: 'Describe the image').
     * @param Role|string $role Optional role (default: USER).
     * @return self
     */
    public function queryImage(
        string $image,
        ImageDetail $imageDetail = ImageDetail::MEDIUM,
        string $text = '',
        Role|string $role = Role::USER
    ): self {
        $message = [
            'role' => $role instanceof Role ? $role->value : $role,
            'content' => [
                [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => $image,
                        'detail' => $imageDetail->value,
                    ]
                ]
            ],
        ];
        if ($text) {
            $message['content'][] = [
                'type' => 'text',
                'text' => $text,
            ];
        }
        $this->messages[] = $message;
        return $this;
    }

    /**
     * Get the messages.
     * @return array<int, array{role: Role, content: string}>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Send the request to the API.
     * @return ChatResponse|ChatErrorResponse
     */
    public function run(): ChatResponse|ChatErrorResponse
    {
        return $this->request();
    }

    /**
     * Send the request to the API and return the message.
     * @return string|null
     */
    public function send(): string|null
    {
        $response = $this->request();
        return $response instanceof ChatResponse ? $response->getMessageContent() : null;
    }

    /**
     * Stream the response from the API.
     * @param callable $streamHandler
     * @return ChatResponse|ChatErrorResponse|null
     */
    public function stream(callable $streamHandler): ChatResponse|ChatErrorResponse|null
    {
        return $this->streamRequest($streamHandler);
    }

    /**
     * Send the request to the API.
     * @param callable|null $streamHandler
     * @return ChatResponse|ChatErrorResponse|null
     */
    private function request(): ChatResponse|ChatErrorResponse
    {
        // 1. Get the client.
        $client = $this->client;

        // 2. Send the request.
        try {
            $response = $client->post(Uri::CHAT->value, $this->getGuzzleOptions());
            $body = $response->getBody()->getContents();
            // Validate response.
            if (! json_validate($body)) {
                return new ChatErrorResponse('invalid_json', 'Invalid JSON');
            }
            // Return response.
            return new ChatResponse($body);
        } catch (GuzzleException $e) {
            // Oops. Something went wrong.
            $code = $e->getCode();
            $message = $e->getMessage();
            return new ChatErrorResponse($code, $message, true, $e);
        }
    }

    /**
     * Stream the response from the API.
     * @param callable $streamHandler The stream handler. Should include Psr\Http\Message\StreamInterface as first argument.
     * @return null|ChatErrorResponse
     */
    private function streamRequest(callable $streamHandler): null|ChatErrorResponse
    {
        // 1. Get the client.
        $client = $this->client;

        // 2. Send the request.
        try {
            $response = $client->post(Uri::CHAT->value, $this->getGuzzleOptions(true));
            $body = $response->getBody();
            while (! $body->eof()) {
                call_user_func($streamHandler, $body);
            }
            return null;
        } catch (GuzzleException $e) {
            // Oops. Something went wrong.
            $code = $e->getCode();
            $message = $e->getMessage();
            return new ChatErrorResponse($code, $message);
        }
    }

    /**
     * Get the payload for the request.
     * @param bool $stream
     * @return array
     */
    private function getPayload(bool $stream = false): array
    {
        $model = $this->getConfig()->model;
        if ($model instanceof Model) {
            $model = $model->value;
        }
        $temperature = $this->getConfig()->temperature;
        if ($temperature instanceof Temperature) {
            $temperature = $temperature->value;
        }
        return [
            'messages' => $this->messages,
            'model' => $model,
            'temperature' => (float)$temperature,
            'stream' => $stream,
        ];
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
    private function getGuzzleOptions(bool $stream = false): array
    {
        return [
            'json' => $this->getPayload($stream),
            'timeout' => $this->getConfig()->timeout,
            'headers' => $this->getHeaders(),
            'stream' => $stream,
        ];
    }
}