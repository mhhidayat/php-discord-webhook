<?php

namespace Mhhidayat\PhpDiscordClient\Interface;

use Closure;

interface DiscordWebhookInterface {
    public static function make(): self;
    public static function withHeaders(array $headers): self;
    public static function timeout(int $seconds): self;
    public static function withConfig(array $config): self;
    public function setWebhookURL(string $setWebhookURL): self;
    public function setContent(array|Closure $contentHandler): self;
    public function addEmbeds(Closure $embedsHandler): self;
    public function text(string $text): self;
    public function setUsername(string $username): self;
    public function setAvatar(string $avatarURL): self;
    public function allowTTS(): self;
    public function send(): self;
    public function sendWhen(bool|Closure $isSendHandler): self;
    public function successful(): bool;
    public function failed(): bool;
    public function getResponseJson(): string;
}