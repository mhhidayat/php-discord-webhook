<?php

namespace Mhhidayat\PhpDiscordClient\Http;

use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;

class HttpClient
{
    private array $headers;
    private int $timeout;

    public function __construct(array $headers = [], int $timeout = 15)
    {
        $this->headers = $headers;
        $this->timeout = $timeout;
    }

    public function post(string $url, string $data): HttpResponse
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new DiscordClientException("cURL error: " . $error);
        }
        
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return new HttpResponse($response, $statusCode);
    }
}