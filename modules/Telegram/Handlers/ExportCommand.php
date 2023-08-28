<?php

namespace Modules\Telegram\Handlers;

use App\Enums\Event;
use App\Events\ExportChargesEvent;
use App\Events\MessageEvent;
use App\Models\Category;
use App\Services\AbstractCommandService;

class ExportCommand extends AbstractCommandService
{
    protected Event $event = Event::Export;

    public function execute(MessageEvent $event): void
    {
        event(new ExportChargesEvent($event->user));
    }
}
