<?php

namespace App\Services\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;

final class TelegramBotCommandHandler extends WebhookHandler
{
    /**
     *  Test command to Bot '/hello':
     *
     * @return void
     */
    public function hello(): void
    {
        $this->reply(message: 'Привет от Бота "Endless Profile"!');
    }
}
