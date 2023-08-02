<?php

namespace Modules\Telegram\Services;

use App\Enums\Event;
use App\Events\MessageEvent;

class CommandAdapter
{
    public function run(MessageEvent $event)
    {
        return $this->getCommandEvent($event) ??
            $this->getMessageEvent($event);
    }

    private function getCommandEvent(MessageEvent $event): ?Event
    {
        if (empty($event->dto->command)) {
            return null;
        }

        return Event::tryFrom($event->dto->command);
    }

    private function getMessageEvent(MessageEvent $event)
    {
    }
}
