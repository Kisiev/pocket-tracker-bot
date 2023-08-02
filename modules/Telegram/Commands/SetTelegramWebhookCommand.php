<?php

namespace Modules\Telegram\Commands;

use Illuminate\Console\Command;
use Modules\Telegram\Services\TelegramService;
use TelegramBot\Api\Exception;

class SetTelegramWebhookCommand extends Command
{
    protected $signature = 'telegram:set-webhook {url}';

    protected $description = 'Set webhook';

    /**
     * @throws Exception
     */
    public function handle(TelegramService $telegramService): void
    {
        $url = $this->argument('url');
        $telegramService->setWebHook($url);
    }
}
