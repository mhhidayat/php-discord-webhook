<?php

namespace Mhhidayat\PhpDiscordClient\Trait;

use Mhhidayat\PhpDiscordClient\Contract\EmbedsContract;
use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;

/**
 * @requires extends Mhhidayat\PhpDiscordClient\Core\CoreDiscordClient;
 */
trait HasDiscordClient {

    /**
     * @return self
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * @param array $config
     * @return self
     */
    public static function withConfig(array $config): self
    {
        $instance = new self();

        if (isset($config['webhook_url'])) {
            $instance->webhookURL = $config['webhook_url'];
        }
        
        if (isset($config['username'])) {
            $instance->username = $config['username'];
        }
        
        if (isset($config['avatar_url'])) {
            $instance->avatarURL = $config['avatar_url'];
        }
        
        return $instance;
    }

    /**
     * @param array $headers
     * @return self
     */
    public static function withHeaders(array $headers): self
    {
        if (empty($headers)) {
            throw new DiscordClientException(
                "withHeaders() requires a valid headers array."
            );
        }

        return new self(headers: $headers);
    }

    /**
     * By default timeout is 15 seconds
     * @param int $seconds
     * @return self
     */
    public static function timeout(int $seconds): self
    {
        return new self(timeout: $seconds);
    }

    /**
     * @param array|\Closure $messageHandler
     * @return self
     */
    public function setContent(array|\Closure $contentHandler): self
    {
        if ($contentHandler instanceof \Closure) {
            $content = $contentHandler();

            if (!is_array($content)) {
                throw new DiscordClientException(
                    "The \Closure for setContent() must return an array."
                );
            }
        } else if (is_array($contentHandler)) {
            $content = $contentHandler;
        } else {
            throw new DiscordClientException(
                "The parameter to setContent() must be an array or a \Closure that returns an array."
            );
        }

        $this->content = $content;
        return $this;
    }

    /**
     * @param \Closure $embedsHandler
     * @return self
     */
    public function addEmbeds(\Closure $embedsHandler): self
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
            throw new DiscordClientException(
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
        if (strlen($username) > 80) {
            throw new DiscordClientException("Username cannot exceed 80 characters.");
        }
        
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $avatarURL
     * @return self
     */
    public function setAvatar(string $avatarURL): self
    {
        if (!filter_var($avatarURL, FILTER_VALIDATE_URL)) {
            throw new DiscordClientException("Invalid avatar URL format.");
        }
        
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
     * @return self
     */
    public function send(): self
    {
        $this->sendRequest();
        return $this;
    }

    /**
     * @param bool|\Closure $isSendHandler
     * @return self
     */
    public function sendWhen(bool|\Closure $isSendHandler): self
    {
        if ($isSendHandler instanceof \Closure) {
            $isSend = $isSendHandler();

            if (!is_bool($isSend)) {
                throw new DiscordClientException(
                    "\Closure for sendWhen() must return a boolean."
                );
            }
        } else if (is_bool($isSendHandler)) {
            $isSend = $isSendHandler;
        } else {
            throw new DiscordClientException(
                "The parameter to sendWhen() must be a boolean or a \Closure that returns a boolean."
            );
        }

        if (!$isSend) {
            return $this;
        }

        $this->sendRequest();
        return $this;
    }

    /**
     * @return bool
     */
    public function successful(): bool
    {
        return $this->lastResponse?->isSuccessful() ?? false;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->successful();
    }

    /**
     * @return string
     */
    public function getResponseJson(): string
    {
        return $this->lastResponse?->getBody() ?? '';
    }
}