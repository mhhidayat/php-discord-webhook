<?php

namespace Mhhidayat\PhpDiscordClient\Contract;

use Mhhidayat\PhpDiscordClient\Exception\DiscordClientException;
use Mhhidayat\PhpDiscordClient\Interface\ContractInterface;

class EmbedsFieldsContract implements ContractInterface
{
    private array $fieldsData = [];
    private int $currentIndex = -1;

    /**
     * @param string $name
     * @return self
     */
    public function name(string $name): self
    {
        $this->fieldsData[] = [
            'name' => $name,
        ];

        $this->currentIndex = array_key_last($this->fieldsData);
        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function value(string $value): self
    {
        if ($this->currentIndex === -1) {
            throw new DiscordClientException('Call name() before value()');
        }

        $this->fieldsData[$this->currentIndex]['value'] = $value;
        return $this;
    }

    /**
     * @param bool $inline
     * @return self
     */
    public function inline(bool $inline): self
    {
        if ($this->currentIndex === -1) {
            throw new DiscordClientException('Call name() before inline()');
        }
        
        $this->fieldsData[$this->currentIndex]['inline'] = $inline;
        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return $this->fieldsData;
    }
}