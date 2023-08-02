<?php

namespace Modules\Telegram\Services;

use App\Events\AnswerEvent;
use Exception;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidArgumentException;

class TelegramService
{
    private BotApi $bot;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->bot = new BotApi(env('TELEGRAM_BOT_TOKEN'));
    }

    /**
     * @throws \TelegramBot\Api\Exception
     * @throws InvalidArgumentException
     */
    public function sendMessage(int $telegramId, string $message, $keyboard = null): void
    {
        $this->bot->sendMessage($telegramId, $message,'html', false, null, $keyboard);
        event(new AnswerEvent($telegramId, $message));
    }

    /**
     * @param string $url
     * @return void
     * @throws \TelegramBot\Api\Exception
     */
    public function setWebHook(string $url): void
    {
        $this->bot->setWebhook($url);
    }

    /**
     * @throws \TelegramBot\Api\Exception
     */
    public function deleteWebHook(): void
    {
        $this->bot->deleteWebhook();
    }
}
