<?php

namespace Mhhidayat\PhpWebhookDiscord;

use Closure;
use Mhhidayat\PhpWebhookDiscord\Contract\EmbedsContract;
use Mhhidayat\PhpWebhookDiscord\Exception\DiscordWebhookException;

class DiscordWebhook extends CoreDiscordWebhook
{

    /**
     * @return self
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * @param string $setWebhookURL
     * @return self
     */
    public function setWebhookURL(string $setWebhookURL): self
    {
        $this->setWebhookURL = $setWebhookURL;
        return $this;
    }

    /**
     * @param array|Closure $messageHandler
     * @return self
     */
    public function setContent(array|Closure $contentHandler): self
    {
        if ($contentHandler instanceof Closure) {
            $content = $contentHandler();

            if (!is_array($content)) {
                throw new DiscordWebhookException(
                    "The Closure for setContent() must return an array."
                );
            }
        } else if (is_array($contentHandler)) {
            $content = $contentHandler;
        } else {
            throw new DiscordWebhookException(
                "The parameter to setContent() must be an array or a Closure that returns an array."
            );
        }

        $this->content = $content;
        return $this;
    }

    /**
     * @param Closure $embedsHandler
     * @return self
     */
    public function addEmbeds(Closure $embedsHandler): self
    {
        $embeds = new EmbedsContract();
        $embedsHandler($embeds);
        $this->embeds = $embeds->build();
        return $this;
    }

    /**
     * @param string $text
     * @return self
     */
    public function text(string $text): self
    {
        if (strlen($text) > 2000) {
            throw new DiscordWebhookException(
                "The text is too long. Maximum 2000 characters."
            );
        }

        $this->text = $text;
        return $this;
    }

    /**
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $avatarURL
     * @return self
     */
    public function setAvatar(string $avatarURL): self
    {
        $this->avatarURL = $avatarURL;
        return $this;
    }

    /**
     * @return self
     */
    public function allowTTS(): self
    {
        $this->allowTTS = true;
        return $this;
    }

    /**
     * @param array $headers
     * @return self
     */
    public static function withHeaders(array $headers): self
    {
        if (empty($headers)) {
            throw new DiscordWebhookException(
                "withHeaders() requires a valid headers array."
            );
        }

        $instance = new self();
        $instance->headers = $headers;
        return $instance;
    }

    /**
     * By default timeout is 15 seconds
     * @param int $seconds
     * @return self
     */
    public static function timeout(int $seconds): self
    {
        $instance = new self();
        $instance->timeout = $seconds;
        return $instance;
    }

    /**
     * @return self
     */
    public function send(): self
    {
        $this->httpRequestClient();
        return $this;
    }

    /**
     * @param bool|Closure $isSendHandler
     * @return self
     */
    public function sendWhen(bool|Closure $isSendHandler): self
    {
        if ($isSendHandler instanceof Closure) {
            $isSend = $isSendHandler();

            if (!is_bool($isSend)) {
                throw new DiscordWebhookException(
                    "Closure for sendWhen() must return a boolean."
                );
            }
        } else if (is_bool($isSendHandler)) {
            $isSend = $isSendHandler;
        } else {
            throw new DiscordWebhookException(
                "The parameter to sendWhen() must be a boolean or a Closure that returns a boolean."
            );
        }

        if (!$isSend) {
            return $this;
        }

        $this->httpRequestClient();
        return $this;
    }

    /**
     * @return bool
     */
    public function successful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->isSuccessful;
    }

    /**
     * @return string
     */
    public function getResponseJson(): string
    {
        return $this->JSONResponse;
    }
}
