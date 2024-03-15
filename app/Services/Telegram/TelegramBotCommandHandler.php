<?php

namespace App\Services\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class TelegramBotCommandHandler extends WebhookHandler
{
    /**
     *  Test command to Bot '/hello':
     *
     * @return void
     */
    public function hello(): void
    {
        $this->reply('Привет от Бота "Endless Profile"!');
    }
}
