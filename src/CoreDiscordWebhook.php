<?php

namespace Mhhidayat\PhpDiscordClient;

use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;

class CoreDiscordWebhook
{

    protected string $setWebhookURL = "", $JSONResponse = "", $text = "", $username = "", $avatarURL = "";
    protected array $content, $headers = [
        "Content-Type: application/json",
    ], $embeds = [];
    protected bool $isSuccessful = false, $allowTTS = false;
    protected int $timeout = 15;

    /**
     * @param int $statusCode
     * @return void
     */
    protected function parseResponseStatusCode(int $statusCode): void
    {
        $this->isSuccessful = $statusCode >= 200 && $statusCode < 300;
    }

    /**
     * @return string
     */
    protected function getURL(): string
    {
        if (!$this->setWebhookURL) {
            throw new DiscordClientException(
                "Webhook URL is not set. Use the setWebhookURL() method to set it."
            );
        }
        return $this->setWebhookURL;
    }

    /**
     * @return string
     */
    protected function getContentEncode(): string
    {
        if ($this->text) {
            $content = ["content" => $this->text];

            if ($this->username) $content["username"] = $this->username;
            if ($this->avatarURL) $content["avatar_url"] = $this->avatarURL;
            if ($this->allowTTS) $content["tts"] = $this->allowTTS;

            if (!empty($this->embeds)) {
                $content["embeds"][] = $this->embeds;
            }

            return json_encode($content);
        }

        if (empty($this->content)) {
            throw new DiscordClientException(
                "The content is not set. Use the text() or setContent() method to set it."
            );
        }

        $content = $this->content;
        if (!empty($this->embeds)) {
            $content["embeds"][] = $this->embeds;
        }

        return json_encode($content);
    }

    /**
     * Mengirim request ke Discord webhook
     * @return void
     */
    protected function httpRequestClient(): void
    {
        $url = $this->getURL();
        $reqClient = $this->getContentEncode();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $reqClient);
        $this->JSONResponse = curl_exec($ch);

        $responseStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->parseResponseStatusCode($responseStatusCode);
    }
}
