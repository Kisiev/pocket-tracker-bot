<?php

namespace Modules\Logging\Listeners;

use App\Events\MessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Logging\Models\Logging;

class MessageListener implements ShouldQueue
{
    public function handle(MessageEvent $event): void
    {
        Logging::create([
            'user_id' => $event->user?->id ?? $event->dto->fromUserId,
            'message' => $event->dto->toArray(),
        ]);
    }
}
