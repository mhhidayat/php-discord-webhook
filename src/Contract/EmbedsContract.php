<?php

namespace Mhhidayat\PhpWebhookDiscord\Contract;

use Mhhidayat\PhpWebhookDiscord\Enum\Colors;
use Mhhidayat\PhpWebhookDiscord\Exception\DiscordWebhookException;
use Mhhidayat\PhpWebhookDiscord\Interface\GeneralInterface;

class EmbedsContract implements GeneralInterface
{
    private array $embedsData = [];

    /**
     * @param string $title
     * @return self
     */
    public function title(string $title): self
    {
        $this->embedsData["title"] = $title;
        return $this;
    }

    /**
     * @param string $description
     * @return self
     */
    public function description(string $description): self
    {
        $this->embedsData["description"] = $description;
        return $this;
    }

    /**
     * @param string $url
     * @return self
     */
    public function url(string $url): self
    {
        $this->embedsData["url"] = $url;
        return $this;
    }

    /**
     * @return self
     */
    public function enableTimestamp(): self
    {
        $this->embedsData["timestamp"] = date("c");
        return $this;
    }

    /**
     * @param Colors|int $color
     * @return self
     */
    public function color(Colors|int $color): self
    {
        if ($color instanceof Colors) {
            $this->embedsData["color"] = $color->value;
        } else {
            $this->embedsData["color"] = $color;
        }
        return $this;
    }

    /**
     * @param string $authorName
     * @return self
     */
    public function authorName(string $authorName): self
    {
        $this->embedsData["author"]["name"] = $authorName;
        return $this;
    }

    /**
     * @param string $authorUrl
     * @return self
     */
    public function authorUrl(string $authorUrl): self
    {
        $this->embedsData["author"]["url"] = $authorUrl;
        return $this;
    }

    /**
     * @param string $authorIconUrl
     * @return self
     */
    public function authorIconUrl(string $authorIconUrl): self
    {
        $this->embedsData["author"]["icon_url"] = $authorIconUrl;
        return $this;
    }

    /**
     * @param string $footerText
     * @return self
     */
    public function footerText(string $footerText): self
    {
        $this->embedsData["footer"]["text"] = $footerText;
        return $this;
    }

    /**
     * @param string $footerIconUrl
     * @return self
     */
    public function footerIconUrl(string $footerIconUrl): self
    {
        $this->embedsData["footer"]["icon_url"] = $footerIconUrl;
        return $this;
    }

    /**
     * @param string $imageUrl
     * @return self
     */
    public function imageUrl(string $imageUrl): self
    {
        if (!str_starts_with($imageUrl, "https")) {
            throw new DiscordWebhookException("Source url of image only support https");
        }

        $this->embedsData["image"]["url"] = $imageUrl;
        return $this;
    }
    
    /**
     * @param array $fields
     * @return self
     */
    public function fields(array $fields): self
    {

        if (count($fields) > 10) {
            throw new DiscordWebhookException("You can only have 10 fields in an embed");
        }

        $allowedKeys = array_flip([
            "name",
            "value",
            "inline"
        ]);
        
        $this->embedsData["fields"] = array_map(
            fn($field) => array_intersect_key($field, $allowedKeys),
            $fields
        );
        
        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return $this->embedsData;
    }
}