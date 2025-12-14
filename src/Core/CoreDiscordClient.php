<?php

namespace Mhhidayat\PhpDiscordClient\Core;

use Mhhidayat\PhpDiscordClient\DiscordBot;
use Mhhidayat\PhpDiscordClient\DiscordWebhook;
use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;
use Mhhidayat\PhpDiscordClient\Http\HttpClient;
use Mhhidayat\PhpDiscordClient\Http\HttpResponse;

abstract class CoreDiscordClient
{
    protected string $webhookURL = "";
    protected string $text = "";
    protected string $username = "";
    protected string $avatarURL = "";
    protected string $channelID = "";
    protected string $botToken = "";
    protected array $content = [];
    protected array $embeds = [];
    protected array $headers = [];
    protected bool $allowTTS = false;
    protected int $timeout = 15;
    protected ?HttpResponse $lastResponse = null;
    protected string $discordClientTarget;

    public function __construct(array $headers = [], int $timeout = 15)
    {
        $this->headers = $headers;
        $this->timeout = $timeout;
        $this->getDiscordClientTarget();
    }

    public function getDiscordClientTarget(): void
    {
        $this->discordClientTarget = match(static::class) {
            DiscordBot::class => "bot",
            DiscordWebhook::class => "webhook",
            default => throw new DiscordClientException("Class name unknown.")
        };
    }

    protected function getURL(): string
    {
        if ($this->discordClientTarget == "bot") {
            $channelID = $this->channelID;
            return "https://discord.com/api/v10/channels/{$channelID}/messages";
        }

        if (empty($this->webhookURL)) {
            throw new DiscordClientException(
                "Webhook URL is not set. Use the setWebhookURL() method to set it."
            );
        }
        return $this->webhookURL;
    }

    protected function buildPayload(): array
    {
        if (!empty($this->text)) {
            $payload = ["content" => $this->text];
        } elseif (!empty($this->content)) {
            $payload = $this->content;
        } else {
            throw new DiscordClientException(
                "No content is set. Use the text() or setContent() method to set it."
            );
        }

        if (!empty($this->username)) {
            $payload["username"] = $this->username;
        }
        
        if (!empty($this->avatarURL)) {
            $payload["avatar_url"] = $this->avatarURL;
        }
        
        if ($this->allowTTS) {
            $payload["tts"] = true;
        }

        if (!empty($this->embeds)) {
            $payload["embeds"] = [$this->embeds];
        }

        return $payload;
    }

    protected function getHeaders(): array
    {
        $headers = $this->headers;

        if (!in_array("Content-Type: application/json", $headers)) {
            array_push($headers, "Content-Type: application/json");
        }

        if ($this->discordClientTarget == "bot") {
            array_push($headers, "Authorization: Bot {$this->botToken}");
        }

        return $headers;
    }

    protected function sendRequest(): void
    {
        $url = $this->getURL();
        $headers = $this->getHeaders();
        $payload = json_encode($this->buildPayload());
        
        $this->lastResponse = (new HttpClient($headers, $this->timeout))
            ->post($url, $payload);
    }
}