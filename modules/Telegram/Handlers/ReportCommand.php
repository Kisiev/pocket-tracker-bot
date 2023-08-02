<?php

namespace Modules\Telegram\Handlers;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Models\Category;
use App\Services\AbstractCommandService;

class ReportCommand extends AbstractCommandService
{
    protected Event $event = Event::Report;

    public function execute(MessageEvent $event): void
    {
        $message = [];
        $totalCost = 0;

        $categories = Category::with('charges')->get();

        foreach ($categories as $category) {
            $message[] = "<b>{$category->title}</b>";

            foreach ($category->charges as $charge) {
                $totalCost += $charge->cost;
                $message[] = "   🏷 <b>{$charge->cost}</b> - {$charge->title}";
            }
        }

        $message[] = "<b>Итого</b>: {$totalCost}";

        $this->telegramService->sendMessage(
            $event->user->id,
            implode("\n", $message),
        );
    }
}
