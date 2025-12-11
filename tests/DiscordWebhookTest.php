<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Mhhidayat\PhpWebhookDiscord\Enum\Colors;
use Mhhidayat\PhpWebhookDiscord\DiscordWebhook;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;

final class DiscordWebhookTest extends TestCase
{
    private string $webhookURL, $avatarURL, $videoURL;

    protected function setUp(): void
    {
        parent::setUp();
        Dotenv::createImmutable(__DIR__)->load();

        $this->webhookURL = $_ENV["TEST_WEBHOOK_URL"] ?? '';
        $this->avatarURL  = $_ENV["TEST_AVATAR_URL"] ?? '';
        $this->videoURL  = $_ENV["TEST_VIDEO_URL"] ?? '';
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

    public function testWebhookCommon()
    {
        $respDiscordWebhook = DiscordWebhook::make()
            ->setWebhookURL($this->webhookURL)
            ->setUsername("mhhidayat")
            ->setAvatar($this->avatarURL)
            ->allowTTS()
            ->text("Hello test")
            ->sendWhen(function () {
                return 1 == 1;
            });

        $this->assertTrue($respDiscordWebhook->successful());
    }

    public function testWebhookEmbed()
    {
        $fieldsData = [
            [
                "name" => "Test Field",
                "value" => "This is a test field",
                "inline" => false,
            ],
            [
                "name" => "Test Field 2",
                "value" => "This is a test field 2",
                "inline" => false,
            ]
        ];
        $respDiscordWebhook = DiscordWebhook::make()
            ->setWebhookURL($this->webhookURL)
            ->text("Test text embed")
            ->setAvatar($this->avatarURL)
            ->setUsername("mhhidayat")
            ->addEmbeds(function (EmbedsContract $e) use($fieldsData) {
                $e->title("Test embed");
                $e->description("My test description");
                $e->url($this->avatarURL);
                $e->enableTimestamp();
                $e->color(Colors::Green);
                $e->authorName("Mhhidayat");
                $e->authorUrl($this->avatarURL);
                $e->authorIconUrl($this->avatarURL);
                $e->footerText("My footer text");
                $e->footerIconUrl($this->avatarURL);
                $e->fields($fieldsData);
                $e->imageUrl($this->avatarURL);
                $e->imageHeight(1);
                $e->imageWidth(1);
                $e->thumbnailUrl($this->avatarURL);
                $e->thumbnailWidth(1);
                $e->thumbnailHeight(1);
                $e->providerName("Test provider");
                $e->providerUrl($this->avatarURL);
                $e->videoUrl($this->videoURL);
                $e->videoHeight(1);
            })
            ->sendWhen(function () {
                return 1 == 1;
            });

        $this->assertTrue($respDiscordWebhook->successful());
    }

    
    public function testWebhookEmbedError()
    {
        $respDiscordWebhook = DiscordWebhook::make()
            ->setWebhookURL($this->webhookURL)
            ->text("Test text embed not valid")
            ->setAvatar($this->avatarURL)
            ->setUsername("mhhidayat")
            ->addEmbeds(function () {
                return "Not valid closure";
            })
            ->sendWhen(function () {
                return 1 == 1;
            });
        $this->assertTrue($respDiscordWebhook->successful());
    }
}
