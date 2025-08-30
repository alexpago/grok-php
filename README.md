# GrokChat PHP Library

**GrokChat** is a PHP library for interacting with the [Grok API](https://x.ai/).  
It provides a simple, object-oriented interface for sending chat messages, managing multi-turn conversations, handling responses, and streaming results.

---

## ✨ Features
- Supports: grok-3, grok-3-mini, grok-4, grok-2-image (and other models)
- Simple API for sending chat messages:
  ```php
  $chat->query('Hello world')->run();
  ```

- Multi-turn conversations:

  ```php
  $chat->query('Hello. I am Lucas')
       ->query('Who are you?')
       ->run();
  ```

- Supports image understanding
```php
  $chat->queryImage(
    image: '<external URL or base64>', 
    text: 'Describe the image'
  )
```

- Supports image generation
```php
  $generate->generate('A beautiful sunset over a calm ocean')->getImage()
```

- Streaming responses with custom handlers:

  ```php
  $chat->query('Let\'s talk about science')
       ->stream(function ($chunk) { ... });
  ```
- Supports roles (`Role::USER`, `Role::SYSTEM`)
- Configurable (temperature, model, roles, etc.)
- PSR-4 autoloading via Composer

---

## 📦 Installation

Install via Composer:

```bash
composer require alexpago/grok-php
```

---

## 🚀 Quick Start

> **Tip:**
> * Use `send()` when you only need the **message text** (string or `null`).
> * Use `run()` when you need the **full response object** (`ChatResponse` or `ChatErrorResponse`).

---

### 1. Start a Simple Chat

```php
use Pago\Grok\Client\GrokChat;

$chat = new GrokChat('apikey');
$text = $chat->query('Hello! How are you?')->send();
```

> `send()` returns the text response (`string|null`).

---

### 2. Chat with Multiple Messages

```php
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Enums\Role;

$chat = new GrokChat('apikey');
$text = $chat
    ->query('Hello! I am Lucas')
    ->query('Hello. I am Grok. How are you?', Role::SYSTEM)
    ->send();
```

> Available roles: `Role::USER` (default) and `Role::SYSTEM`.

---

### 3. Get the Full Response

```php
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Responses\ChatErrorResponse;
use Pago\Grok\Responses\ChatResponse;

$chat = new GrokChat('apikey');
$response = $chat->query('Hello!')->run();

if ($response instanceof ChatErrorResponse) {
    throw new RuntimeException(
        $response->getMessage(),
        $response->getCode()
    );
}

// Grok's answer
$text = $response->getContent();

// Full response as array
$responseData = $response->toArray();
```

---

### 4. Chat with Options

```php
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Enums\Model;

$chat = new GrokChat('apikey');
$response = $chat
    ->query('2+2')
    ->setTemperature(0.3)   // accepts float or Temperature enum
    ->setModel(Model::GROK_4) // default: "grok-4"
    ->run();

if ($response instanceof ChatErrorResponse) {
    throw new RuntimeException(
        $response->getMessage(),
        $response->getCode()
    );
}

$text = $response->getContent();
$responseData = $response->toArray();
```

> **Temperature:**
> Lower = focused & reliable
> Higher = creative & diverse
> (Regular ChatGPT default ≈ `1.3`)

---

### 5. Image understanding

You can use the `queryImage` method to use "Grok Understanding". First argument accepts an external image URL or a base64-encoded string.
Support only JPEG and PNG formats are supported. Maximum size is 20 MB.

```php
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Enums\ImageDetail;

$chat = new GrokChat('apikey');
$chat = $chat->queryImage(
    'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png', 
    ImageDetail::LOW,
    'Describe the image'
);
$describe = $chat->send();
```

---

### 6. Image generation

To generate an image, use `GrokImageGeneration` class instead of GrokChat.

```php
use Pago\Grok\Client\GrokImageGeneration;

$chat = new GrokImageGeneration('apikey');
$image = $generate->generate('A beautiful sunset over a calm ocean')->getImage();
```

---

### 7. Streaming Results

Use a custom callback to process streaming chunks:

```php
use Pago\Grok\Client\GrokChat;
use Pago\Grok\Enums\Role;
use Psr\Http\Message\StreamInterface;

$chat = new GrokChat('apikey');
$chat
    ->query('Hello! I am Lucas')
    ->query('Hello. I am Grok. How are you?', Role::SYSTEM)
    ->stream(function (StreamInterface $body) {
        // Read chunks from the response
        $line = trim($body->read(1024));
        if ($line === '' || !str_starts_with($line, 'data: ')) {
            return;
        }
        if ($line === 'data: [DONE]') {
            return;
        }

        // Remove prefix "data: "
        $json = substr($line, 6);

        // Convert to array
        $chunk = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        if (!empty($chunk['choices'][0]['delta']['content'])) {
            $piece = $chunk['choices'][0]['delta']['content'];
            echo $piece;
            ob_flush();
            flush();
        }
    });
```

---

## ⚙️ Requirements

* PHP 8.1+
* Composer

---

## 📜 License

MIT License.