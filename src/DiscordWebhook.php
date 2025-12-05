<?php

namespace Mhhidayat\PhpWebhookDiscord;

use Closure;

class DiscordWebhook extends CoreDiscordWebhook
{
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
                    "Callable untuk setMessages() harus mengembalikan array."
                );
            }
        } else if (is_array($contentHandler)) {
            $content = $contentHandler;
        } else {
            throw new DiscordWebhookException(
                "Parameter untuk setMessages() harus berupa array atau Closure yang mengembalikan array."
            );
        }

        $this->content = $content;
        return $this;
    }

    /**
     * @param string $text
     * @return self
     */
    public function text(string $text): self
    {
        $this->text = $text;
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
                "withHeaders() membutuhkan array headers yang valid."
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
                    "Callable untuk sendWhen() harus mengembalikan boolean."
                );
            }
        } else if (is_bool($isSendHandler)) {
            $isSend = $isSendHandler;
        } else {
            throw new DiscordWebhookException(
                "Parameter untuk sendWhen() harus berupa boolean atau Closure yang mengembalikan boolean."
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
