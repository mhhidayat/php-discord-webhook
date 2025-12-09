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

### Rich Embeds with Builder (New!)

Use the fluent embed builder for creating rich embeds easily:

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Check out this embed!')
    ->addEmbeds(function (EmbedsContract $embed) {
        $embed->title('Embed Title')
              ->description('This is an embed description')
              ->color(Colors::Blue)
              ->url('https://example.com')
              ->enableTimestamp()
              ->fields([
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
              ]);
    })
    ->send();
```

### Custom Content with Embeds (Legacy)

You can still use raw arrays for custom content:

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

### Embed Builder Features

The embed builder provides a fluent interface for creating rich embeds:

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Server Status Update')
    ->setUsername('Status Bot')
    ->setAvatar('https://example.com/bot-avatar.png')
    ->addEmbeds(function (EmbedsContract $embed) {
        $embed->title('System Status')
              ->description('All systems are operational')
              ->color(Colors::Green)
              ->url('https://status.example.com')
              ->enableTimestamp()
              ->authorName('System Monitor')
              ->authorUrl('https://example.com')
              ->authorIconUrl('https://example.com/icon.png')
              ->footerText('Powered by PHP Discord Webhook')
              ->footerIconUrl('https://example.com/footer-icon.png')
              ->imageUrl('https://example.com/image.png');
              ->imageHeight(10);
              ->imageWidth(10);
              ->thumbnailUrl('https://example.com/thumbnail.png');
              ->thumbnailWidth(10);
              ->thumbnailHeight(10);
              ->fields([
                  [
                      'name' => 'CPU Usage',
                      'value' => '45%',
                      'inline' => true
                  ],
                  [
                      'name' => 'Memory',
                      'value' => '2.1GB / 8GB',
                      'inline' => true
                  ],
                  [
                      'name' => 'Uptime',
                      'value' => '15 days',
                      'inline' => true
                  ]
              ]);
    })
    ->send();
```

### Available Colors Enum

The library includes a comprehensive Colors enum with Discord's official colors:

```php
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

// Discord Embed Colors
Colors::Default
Colors::Aqua
Colors::DarkAqua
Colors::Green
Colors::DarkGreen
Colors::Blue
Colors::DarkBlue
Colors::Purple
Colors::DarkPurple
Colors::LuminousVividPink
Colors::DarkVividPink
Colors::Gold
Colors::DarkGold
Colors::Orange
Colors::DarkOrange
Colors::Red
Colors::DarkRed
Colors::Grey
Colors::DarkGrey
Colors::DarkerGrey
Colors::LightGrey
Colors::Navy
Colors::DarkNavy
Colors::Yellow

// Official Discord Palette
Colors::White
Colors::Greyple
Colors::Black
Colors::DarkButNotBlack
Colors::Blurple
Colors::DiscordYellow
Colors::Fuchsia

// You can also use custom integer colors
->color(16711680) // Custom red color
```

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

#### `addEmbeds(Closure $embedsHandler): self`
Add rich embeds using the fluent EmbedsContract builder. The closure receives an EmbedsContract instance.

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

### Embed Builder Methods

The EmbedsContract provides these methods for building rich embeds:

#### `title(string $title): self`
Set the embed title.

#### `description(string $description): self`
Set the embed description.

#### `url(string $url): self`
Set the embed URL (makes the title clickable).

#### `color(Colors|int $color): self`
Set the embed color using the Colors enum or a custom integer.

#### `enableTimestamp(): self`
Add the current timestamp to the embed.

#### `authorName(string $authorName): self`
Set the author name.

#### `authorUrl(string $authorUrl): self`
Set the author URL (makes the author name clickable).

#### `authorIconUrl(string $authorIconUrl): self`
Set the author icon URL.

#### `footerText(string $footerText): self`
Set the footer text.

#### `footerIconUrl(string $footerIconUrl): self`
Set the footer icon URL.

#### `fields(array $fields): self`
Add fields to the embed (max 10 fields). Each field should have 'name', 'value', and optionally 'inline' keys.

## Error Handling

The library throws `DiscordWebhookException` for validation errors:

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Exception\DiscordWebhookException;

try {
    DiscordWebhook::make()
        ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
        ->text(str_repeat('a', 2001)) // Too long!
        ->send();
} catch (DiscordWebhookException $e) {
    echo "Error: " . $e->getMessage();
}
```

Common validation errors:
- Text exceeding 2000 characters
- More than 10 fields in an embed
- Missing webhook URL
- Missing content (no text or setContent called)

## Examples

### Notification System

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

function sendNotification($message, $level = 'info') {
    $colors = [
        'info' => Colors::Blue,
        'success' => Colors::Green,
        'warning' => Colors::Gold,
        'error' => Colors::Red
    ];
    
    $icons = [
        'info' => 'ℹ️',
        'success' => '✅',
        'warning' => '⚠️',
        'error' => '🚨'
    ];
    
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->addEmbeds(function (EmbedsContract $embed) use ($message, $level, $colors, $icons) {
            $embed->title($icons[$level] . ' ' . strtoupper($level))
                  ->description($message)
                  ->color($colors[$level] ?? Colors::Blue)
                  ->enableTimestamp();
        })
        ->send();
}

sendNotification('User registration completed', 'success');
sendNotification('Database backup failed', 'error');
```

### Error Logging

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

function logError($exception) {
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->setUsername('Error Logger')
        ->addEmbeds(function (EmbedsContract $embed) use ($exception) {
            $embed->title('🚨 Error Occurred')
                  ->description('An exception was thrown in the application')
                  ->color(Colors::Red)
                  ->enableTimestamp()
                  ->fields([
                      [
                          'name' => 'Message',
                          'value' => $exception->getMessage(),
                          'inline' => false
                      ],
                      [
                          'name' => 'File',
                          'value' => $exception->getFile(),
                          'inline' => true
                      ],
                      [
                          'name' => 'Line',
                          'value' => (string) $exception->getLine(),
                          'inline' => true
                      ]
                  ])
                  ->footerText('Error Logger v1.0');
        })
        ->send();
}
```

### Deployment Notifications

```php
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;

function notifyDeployment($version, $environment, $author) {
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->setUsername('Deploy Bot')
        ->addEmbeds(function (EmbedsContract $embed) use ($version, $environment, $author) {
            $embed->title('🚀 New Deployment')
                  ->description("Version $version has been deployed to $environment")
                  ->color(Colors::Blurple)
                  ->enableTimestamp()
                  ->authorName($author)
                  ->fields([
                      [
                          'name' => 'Version',
                          'value' => $version,
                          'inline' => true
                      ],
                      [
                          'name' => 'Environment',
                          'value' => $environment,
                          'inline' => true
                      ]
                  ])
                  ->footerText('Automated Deployment System');
        })
        ->send();
}

notifyDeployment('v2.1.0', 'production', 'John Doe');
```

## License

See LICENSE.md for details.

## Author

mhhidayat - mhhidayat811@gmail.com
