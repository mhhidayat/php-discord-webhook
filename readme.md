# PHP Discord Webhook

A simple and elegant PHP library for sending messages to Discord via webhooks.

## Requirements

- PHP 8.0 or higher
- cURL extension enabled
- Composer

## Installation

Install via Composer:

```bash
composer require mhhidayat/php-webhook-discord
```

## Basic Usage

### Simple Text Message

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;

DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Hello, Discord!')
    ->send();
```

### Custom Content with Embeds

```php
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->setContent([
        'content' => 'Check out this embed!',
        'embeds' => [
            [
                'title' => 'Embed Title',
                'description' => 'This is an embed description',
                'color' => 3447003,
                'fields' => [
                    [
                        'name' => 'Field 1',
                        'value' => 'Value 1',
                        'inline' => true
                    ],
                    [
                        'name' => 'Field 2',
                        'value' => 'Value 2',
                        'inline' => true
                    ]
                ]
            ]
        ]
    ])
    ->send();
```

### Customize Username and Avatar

```php
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->setUsername('Custom Bot Name')
    ->setAvatar('https://example.com/avatar.png')
    ->text('Message with custom appearance')
    ->send();
```

### Text-to-Speech (TTS)

```php
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('This message will be read aloud')
    ->allowTTS()
    ->send();
```

## Advanced Usage

### Conditional Sending

Send messages only when a condition is met:

```php
$webhook = DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Error occurred!')
    ->sendWhen($errorOccurred); // boolean

// Or with a closure
$webhook = DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Error occurred!')
    ->sendWhen(function() {
        return someCondition();
    });
```

### Custom Headers

```php
DiscordWebhook::withHeaders([
    'Content-Type: application/json',
    'User-Agent: MyApp/1.0'
])
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Message with custom headers')
    ->send();
```

### Custom Timeout

Default timeout is 15 seconds. You can customize it:

```php
DiscordWebhook::timeout(30)
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Message with 30 second timeout')
    ->send();
```

### Dynamic Content with Closures

```php
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->setContent(function() {
        return [
            'content' => 'Dynamic message at ' . date('Y-m-d H:i:s'),
            'embeds' => [
                [
                    'title' => 'Server Status',
                    'description' => 'All systems operational'
                ]
            ]
        ];
    })
    ->send();
```

### Check Response Status

```php
$webhook = DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Test message')
    ->send();

if ($webhook->successful()) {
    echo "Message sent successfully!";
}

if ($webhook->failed()) {
    echo "Failed to send message";
    echo $webhook->getResponseJson();
}
```

## API Reference

### Methods

#### `make(): self`
Create a new instance of DiscordWebhook.

#### `setWebhookURL(string $url): self`
Set the Discord webhook URL (required).

#### `text(string $text): self`
Set a simple text message (max 2000 characters).

#### `setContent(array|Closure $content): self`
Set custom content including embeds. Accepts an array or closure that returns an array.

#### `setUsername(string $username): self`
Override the default webhook username.

#### `setAvatar(string $avatarURL): self`
Override the default webhook avatar.

#### `allowTTS(): self`
Enable text-to-speech for the message.

#### `send(): self`
Send the webhook message.

#### `sendWhen(bool|Closure $condition): self`
Send the message only if the condition is true.

#### `successful(): bool`
Check if the last request was successful (HTTP 2xx).

#### `failed(): bool`
Check if the last request failed.

#### `getResponseJson(): string`
Get the raw JSON response from Discord.

### Static Methods

#### `withHeaders(array $headers): self`
Create instance with custom HTTP headers.

#### `timeout(int $seconds): self`
Create instance with custom timeout (default: 15 seconds).

## Error Handling

The library throws `DiscordWebhookException` for validation errors:

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\DiscordWebhookException;

try {
    DiscordWebhook::make()
        ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
        ->text(str_repeat('a', 2001)) // Too long!
        ->send();
} catch (DiscordWebhookException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Examples

### Notification System

```php
function sendNotification($message, $level = 'info') {
    $colors = [
        'info' => 3447003,
        'success' => 3066993,
        'warning' => 15844367,
        'error' => 15158332
    ];
    
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->setContent([
            'embeds' => [
                [
                    'title' => strtoupper($level),
                    'description' => $message,
                    'color' => $colors[$level] ?? $colors['info'],
                    'timestamp' => date('c')
                ]
            ]
        ])
        ->send();
}

sendNotification('User registration completed', 'success');
```

### Error Logging

```php
function logError($exception) {
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->setUsername('Error Logger')
        ->setContent([
            'embeds' => [
                [
                    'title' => '🚨 Error Occurred',
                    'color' => 15158332,
                    'fields' => [
                        [
                            'name' => 'Message',
                            'value' => $exception->getMessage()
                        ],
                        [
                            'name' => 'File',
                            'value' => $exception->getFile()
                        ],
                        [
                            'name' => 'Line',
                            'value' => $exception->getLine()
                        ]
                    ],
                    'timestamp' => date('c')
                ]
            ]
        ])
        ->send();
}
```

## License

See LICENSE.md for details.

## Author

mhhidayat - mhhidayat811@gmail.com
