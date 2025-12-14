<?php

use Dotenv\Dotenv;
use Mhhidayat\PhpDiscordClient\DiscordBot;
use PHPUnit\Framework\TestCase;

final class DiscordBotTest extends TestCase {
    private string $webhookURL, $avatarURL, $videoURL, $botToken, $channelID;

    protected function setUp(): void
    {
        parent::setUp();
        Dotenv::createImmutable(__DIR__)->load();

        $this->webhookURL = $_ENV["TEST_WEBHOOK_URL"] ?? '';
        $this->avatarURL  = $_ENV["TEST_AVATAR_URL"] ?? '';
        $this->videoURL  = $_ENV["TEST_VIDEO_URL"] ?? '';
        $this->botToken  = $_ENV["TEST_BOT_TOKEN"] ?? '';
        $this->channelID = $_ENV["TEST_CHANNEL_ID"] ?? '';
    }

    public function testBotSuccess()
    {
        $respDiscordBot = DiscordBot::make()
            ->setBotToken($this->botToken)
            ->setChannelID($this->channelID)
            ->text("From Bot Test")
            ->send();
        
        $this->assertTrue($respDiscordBot->successful());
    }
}