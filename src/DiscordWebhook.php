<?php

namespace Mhhidayat\PhpDiscordClient;

use Mhhidayat\PhpDiscordClient\Core\CoreDiscordClient;
use Mhhidayat\PhpDiscordClient\Interface\DiscordWebhookInterface;
use Mhhidayat\PhpDiscordClient\Trait\HasDiscordClient;

class DiscordWebhook extends CoreDiscordClient implements DiscordWebhookInterface
{
    use HasDiscordClient;
}