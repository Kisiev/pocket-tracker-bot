<?php

namespace App\Events;

use App\Dto\MessageDto;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly MessageDto $dto, public readonly ?User $user = null)
    {
    }
}
