<?php

use Dotenv\Dotenv;
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use PHPUnit\Framework\TestCase;

final class DiscordWebhookTest extends TestCase
{
    private string $webhookURL, $avatarURL;

    protected function setUp(): void
    {
        parent::setUp();
        Dotenv::createImmutable(__DIR__)->load();

        $this->webhookURL = $_ENV["TEST_WEBHOOK_URL"] ?? '';
        $this->avatarURL  = $_ENV["TEST_AVATAR_URL"] ?? '';
    }

    public function testWebhookSuccess()
    {
        $respDiscordWebhook = DiscordWebhook::withHeaders([
            "Content-Type: application/json",
        ])
            ->setWebhookURL($this->webhookURL)
            ->setContent([
                "content" => "Hello Test"
            ])
            ->send();

        $this->assertTrue($respDiscordWebhook->successful());
    }

    public function testWebhookFailed()
    {
        $respDiscordWebhook = DiscordWebhook::withHeaders([
            "Content-Type: application/json",
        ])
            ->setWebhookURL($this->webhookURL)
            ->setContent([
                "content" => "Hello Test"
            ])
            ->sendWhen(function () {
                return 1 == 1;
            });

        $this->assertFalse($respDiscordWebhook->failed());
    }

    public function testWebhookText()
    {
        $respDiscordWebhook = DiscordWebhook::withHeaders([
            "Content-Type: application/json",
        ])
            ->setWebhookURL($this->webhookURL)
            ->setUsername("mhhidayat")
            ->setAvatar($this->avatarURL)
            ->allowTTS()
            ->text("Hello test")
            ->send();

        $this->assertTrue($respDiscordWebhook->successful());
    }
}
