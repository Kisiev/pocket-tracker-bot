<?php

namespace Modules\Notion\Listeners;

use App\Events\ExportChargesEvent;
use App\Events\MessageEvent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notion\Services\ExportChargeService;

class ExportListener implements ShouldQueue
{
    public function __construct(private readonly ExportChargeService $exportChargeService)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(ExportChargesEvent $event): void
    {
        $this->exportChargeService->export($event->user);
    }

    public function shouldQueue(ExportChargesEvent $event): bool
    {
        return !empty($event->user->settings->notionSecret)
            && !empty($event->user->settings->notionBlockId);
    }
}
