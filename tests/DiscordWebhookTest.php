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
            ->text("Hello test")
            ->send();

        $this->assertTrue($respDiscordWebhook->successful());
    }
}
