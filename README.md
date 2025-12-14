# PHP Discord Client

A comprehensive PHP library for interacting with Discord via both webhooks and bot API. Send messages, rich embeds, and manage Discord communications with an elegant, fluent interface.

## Features

- **Webhook Support**: Send messages via Discord webhooks
- **Bot API Support**: Send messages using Discord bot tokens
- **Rich Embeds**: Create beautiful embeds with the fluent builder
- **Flexible Content**: Support for text, embeds, and custom payloads
- **Error Handling**: Comprehensive validation and error reporting
- **Modern PHP**: Built for PHP 8.0+ with type safety and modern features

## Requirements

- PHP 8.0 or higher
- cURL extension enabled
- Composer

## Installation

Install via Composer:

```bash
composer require mhhidayat/php-discord-client
```

## Quick Start

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\DiscordBot;

// Send via webhook (simple setup)
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Hello from webhook!')
    ->send();

// Send via bot (more features)
DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->text('Hello from bot!')
    ->send();
```

## Basic Usage

### Webhook Messages

Send messages using Discord webhooks:

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;

DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('Hello, Discord!')
    ->send();
```

### Bot Messages

Send messages using a Discord bot token:

```php
use Mhhidayat\PhpDiscordClient\DiscordBot;

DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->text('Hello from bot!')
    ->send();
```

### Rich Embeds with Builder

Use the fluent embed builder for creating rich embeds with both webhooks and bots:

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\DiscordBot;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

// Using webhook
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

// Using bot (same embed builder interface)
DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->addEmbeds(function (EmbedsContract $embed) {
        $embed->title('Bot Embed')
              ->description('Sent via bot API')
              ->color(Colors::Green);
    })
    ->send();
```

### Custom Content with Raw Arrays

You can use raw arrays for custom content with both webhooks and bots:

```php
// Using webhook
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

// Using bot
DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->setContent([
        'content' => 'Bot message with embed!',
        'embeds' => [
            [
                'title' => 'Bot Embed',
                'description' => 'Sent via Discord Bot API',
                'color' => 5814783
            ]
        ]
    ])
    ->send();
```

### Customize Username and Avatar

Customize the appearance of your messages (webhooks support username/avatar override, bots use their configured identity):

```php
// Webhook with custom appearance
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->setUsername('Custom Bot Name')
    ->setAvatar('https://example.com/avatar.png')
    ->text('Message with custom appearance')
    ->send();

// Bot messages use the bot's configured username and avatar
DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->text('Message from bot')
    ->send();
```

### Text-to-Speech (TTS)

Enable TTS for your messages with both webhooks and bots:

```php
// Webhook with TTS
DiscordWebhook::make()
    ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
    ->text('This message will be read aloud')
    ->allowTTS()
    ->send();

// Bot with TTS
DiscordBot::make()
    ->setBotToken('YOUR_BOT_TOKEN')
    ->setChannelID('YOUR_CHANNEL_ID')
    ->text('Bot TTS message')
    ->allowTTS()
    ->send();
```

## Webhooks vs Bots

### When to Use Webhooks

Webhooks are perfect for:
- Simple notifications and alerts
- External integrations (CI/CD, monitoring systems)
- One-way communication to Discord
- When you don't need a persistent bot presence
- Quick setup without bot permissions

```php
// Webhook example - great for CI/CD notifications
DiscordWebhook::make()
    ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
    ->setUsername('Deploy Bot')
    ->text('✅ Deployment to production completed successfully!')
    ->send();
```

### When to Use Bots

Bots are ideal for:
- Interactive applications that need to respond to users
- Complex Discord integrations
- When you need advanced Discord features
- Applications requiring persistent presence
- Better rate limiting and API access

```php
// Bot example - better for interactive features
DiscordBot::make()
    ->setBotToken($_ENV['DISCORD_BOT_TOKEN'])
    ->setChannelID($_ENV['DISCORD_CHANNEL_ID'])
    ->text('🤖 Bot is online and ready to help!')
    ->send();
```

### Setup Requirements

**Webhooks:**
1. Create a webhook in your Discord server settings
2. Copy the webhook URL
3. Start sending messages immediately

**Bots:**
1. Create a bot application in Discord Developer Portal
2. Generate and copy the bot token
3. Invite the bot to your server with appropriate permissions
4. Get the channel ID where you want to send messages
5. Use the bot token and channel ID in your code

## Advanced Usage

### Embed Builder Features

The embed builder provides a fluent interface for creating rich embeds:

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

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
              ->imageUrl('https://example.com/server-chart.png')
              ->imageWidth(400)
              ->imageHeight(200)
              ->thumbnailUrl('https://example.com/status-icon.png')
              ->thumbnailWidth(80)
              ->thumbnailHeight(80)
              ->footerText('Powered by PHP Discord Client')
              ->footerIconUrl('https://example.com/footer-icon.png')
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
use Mhhidayat\PhpDiscordClient\Enums\Colors;

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

### Common Methods (Both DiscordWebhook and DiscordBot)

#### `make(): self`
Create a new instance of the Discord client.

#### `text(string $text): self`
Set a simple text message (max 2000 characters).

#### `setContent(array|Closure $content): self`
Set custom content including embeds. Accepts an array or closure that returns an array.

#### `addEmbeds(Closure $embedsHandler): self`
Add rich embeds using the fluent EmbedsContract builder. The closure receives an EmbedsContract instance.

#### `allowTTS(): self`
Enable text-to-speech for the message.

#### `send(): self`
Send the message to Discord.

#### `sendWhen(bool|Closure $condition): self`
Send the message only if the condition is true.

#### `successful(): bool`
Check if the last request was successful (HTTP 2xx).

#### `failed(): bool`
Check if the last request failed.

#### `getResponseJson(): string`
Get the raw JSON response from Discord.

### Webhook-Specific Methods (DiscordWebhook)

#### `setWebhookURL(string $url): self`
Set the Discord webhook URL (required for webhooks).

#### `setUsername(string $username): self`
Override the default webhook username.

#### `setAvatar(string $avatarURL): self`
Override the default webhook avatar.

### Bot-Specific Methods (DiscordBot)

#### `setBotToken(string $token): self`
Set the Discord bot token (required for bots).

#### `setChannelID(string $channelID): self`
Set the target channel ID where the bot will send messages (required for bots).

### Static Methods (Both Classes)

#### `withHeaders(array $headers): self`
Create instance with custom HTTP headers.

#### `timeout(int $seconds): self`
Create instance with custom timeout (default: 15 seconds).

#### `withConfig(array $config): self`
Create instance with configuration array.

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

#### `imageUrl(string $imageUrl): self`
Set the embed image URL (must be HTTPS).

#### `imageWidth(int $width): self`
Set the embed image width in pixels.

#### `imageHeight(int $height): self`
Set the embed image height in pixels.

#### `thumbnailUrl(string $thumbnailUrl): self`
Set the embed thumbnail URL.

#### `thumbnailWidth(int $width): self`
Set the embed thumbnail width in pixels.

#### `thumbnailHeight(int $height): self`
Set the embed thumbnail height in pixels.

#### `providerName(string $providerName): self`
Set the embed provider name.

#### `providerUrl(string $providerUrl): self`
Set the embed provider URL.

#### `fields(array $fields): self`
Add fields to the embed (max 10 fields). Each field should have 'name', 'value', and optionally 'inline' keys.

## Error Handling

The library throws `DiscordClientException` for validation errors:

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;

try {
    DiscordWebhook::make()
        ->setWebhookURL('https://discord.com/api/webhooks/YOUR_WEBHOOK_URL')
        ->text(str_repeat('a', 2001)) // Too long!
        ->send();
} catch (DiscordClientException $e) {
    echo "Error: " . $e->getMessage();
}
```

Common validation errors:
- Text exceeding 2000 characters
- More than 10 fields in an embed
- Missing webhook URL
- Missing content (no text or setContent called)
- Image URLs that don't use HTTPS protocol

## Examples

### Notification System

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\DiscordBot;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

function sendNotification($message, $level = 'info', $useBot = false) {
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
    
    $client = $useBot 
        ? DiscordBot::make()
            ->setBotToken($_ENV['DISCORD_BOT_TOKEN'])
            ->setChannelID($_ENV['DISCORD_CHANNEL_ID'])
        : DiscordWebhook::make()
            ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL']);
    
    $client->addEmbeds(function (EmbedsContract $embed) use ($message, $level, $colors, $icons) {
        $embed->title($icons[$level] . ' ' . strtoupper($level))
              ->description($message)
              ->color($colors[$level] ?? Colors::Blue)
              ->enableTimestamp();
    })->send();
}

sendNotification('User registration completed', 'success');
sendNotification('Database backup failed', 'error', true); // Use bot
```

### Error Logging

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\DiscordBot;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

function logError($exception, $useBot = false) {
    if ($useBot) {
        DiscordBot::make()
            ->setBotToken($_ENV['DISCORD_BOT_TOKEN'])
            ->setChannelID($_ENV['DISCORD_ERROR_CHANNEL_ID'])
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
                      ->footerText('Error Logger v2.0 (Bot)');
            })
            ->send();
    } else {
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
                      ->footerText('Error Logger v2.0 (Webhook)');
            })
            ->send();
    }
}
```

### Deployment Notifications

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

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

### Rich Media Embeds

Showcase images and thumbnails in your embeds:

```php
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Enums\Colors;

function shareScreenshot($title, $imageUrl, $description = '') {
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->setUsername('Screenshot Bot')
        ->addEmbeds(function (EmbedsContract $embed) use ($title, $imageUrl, $description) {
            $embed->title($title)
                  ->description($description)
                  ->color(Colors::Purple)
                  ->imageUrl($imageUrl)
                  ->imageWidth(800)
                  ->imageHeight(600)
                  ->thumbnailUrl('https://example.com/app-icon.png')
                  ->thumbnailWidth(64)
                  ->thumbnailHeight(64)
                  ->enableTimestamp()
                  ->footerText('Shared via Screenshot Bot');
        })
        ->send();
}

shareScreenshot(
    'New Feature Preview', 
    'https://example.com/screenshots/new-feature.png',
    'Here\'s a preview of our upcoming feature!'
);
```

### Product Showcase

Perfect for e-commerce or product announcements:

```php
function showcaseProduct($name, $price, $imageUrl, $description) {
    DiscordWebhook::make()
        ->setWebhookURL($_ENV['DISCORD_WEBHOOK_URL'])
        ->addEmbeds(function (EmbedsContract $embed) use ($name, $price, $imageUrl, $description) {
            $embed->title($name)
                  ->description($description)
                  ->color(Colors::Gold)
                  ->imageUrl($imageUrl)
                  ->imageWidth(500)
                  ->imageHeight(500)
                  ->thumbnailUrl('https://example.com/store-logo.png')
                  ->fields([
                      [
                          'name' => '💰 Price',
                          'value' => $price,
                          'inline' => true
                      ],
                      [
                          'name' => '📦 Stock',
                          'value' => 'In Stock',
                          'inline' => true
                      ]
                  ])
                  ->enableTimestamp()
                  ->footerText('Online Store');
        })
        ->send();
}

showcaseProduct(
    'Premium Headphones',
    '$299.99',
    'https://example.com/products/headphones.jpg',
    'High-quality wireless headphones with noise cancellation'
);
```

## License

See [LICENSE](./LICENSE.md) for details.

## Author

mhhidayat - mhhidayat811@gmail.com
