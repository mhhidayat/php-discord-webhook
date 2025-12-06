<?php

use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use PHPUnit\Framework\TestCase;

final class DiscordWebhookTest extends TestCase
{
    public function testWebhookSuccess()
    {
        $respDiscordWebhook = DiscordWebhook::withHeaders([
            "Content-Type: application/json",
        ])
            ->setWebhookURL("https://discord.com/api/webhooks/1446513684712132700/WwnU7-koive-XgBRu0lFDawVFzgoUqc-FzEOOKWxCy_IsHYGOLJMf3iix-Joib7KN1WT")
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
            ->setWebhookURL("https://discord.com/api/webhooks/1446513684712132700/WwnU7-koive-XgBRu0lFDawVFzgoUqc-FzEOOKWxCy_IsHYGOLJMf3iix-Joib7KN1WT")
            ->setContent([
                "content" => "Hello Test"
            ])
            ->send();

        $this->assertFalse($respDiscordWebhook->failed());
    }

    public function testWebhookText()
    {
        $respDiscordWebhook = DiscordWebhook::withHeaders([
            "Content-Type: application/json",
        ])
            ->setWebhookURL("https://discord.com/api/webhooks/1446513684712132700/WwnU7-koive-XgBRu0lFDawVFzgoUqc-FzEOOKWxCy_IsHYGOLJMf3iix-Joib7KN1WT")
            ->setUsername("mhhidayat")
            ->setAvatar("https://pikaso.cdnpk.net/private/production/1786532689/upload.png?token=exp=1765238400~hmac=86e6cd793377406c0ed94aa9d01331085903861c075810b3e33304e3a2c841b4")
            ->allowTTS()
            ->text("Hello test")
            ->send();

        $this->assertTrue($respDiscordWebhook->successful());
    }
}
