<?php

namespace Mhhidayat\PhpDiscordClient\Contract;

use Mhhidayat\PhpDiscordClient\Interface\ContractInterface;

class EmbedsFieldsContract implements ContractInterface
{
    private array $fieldsData = [];
    private int $currentIndex = -1;

    public function build(): array
    {
        return $this->fieldsData;
    }
}