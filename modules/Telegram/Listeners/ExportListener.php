<?php

namespace Modules\Telegram\Listeners;

use App\Enums\Event;
use App\Events\ChargesExportedEvent;
use App\Events\MessageEvent;
use App\Interfaces\Command;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Telegram\Handlers\AddCategoryCommand;
use Modules\Telegram\Handlers\DeleteCategoryCommand;
use Modules\Telegram\Handlers\InputChargeCommand;
use Modules\Telegram\Handlers\ReportCommand;
use Modules\Telegram\Services\TelegramService;

class ExportListener implements ShouldQueue
{
    public function __construct(private readonly TelegramService $telegramService)
    {

    }

    /**
     * @throws Exception
     */
    public function handle(ChargesExportedEvent $event): void
    {
        $this->telegramService->sendMessage($event->user->id, $event->message);
    }
}
