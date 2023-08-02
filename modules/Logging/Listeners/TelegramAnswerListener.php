<?php

namespace Modules\Logging\Listeners;

use App\Events\AnswerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Logging\Models\Logging;

class TelegramAnswerListener implements ShouldQueue
{
    public function handle(AnswerEvent $event): void
    {
        Logging::create([
            'user_id' => $event->userId,
            'is_bot' => true,
            'message' => ['message' => $event->message],
        ]);
    }
}
