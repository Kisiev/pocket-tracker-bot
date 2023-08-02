<?php

namespace App\Listeners;

use App\Events\MessageEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateUserListener implements ShouldQueue
{
    public function __construct()
    {

    }

    public function handle(MessageEvent $event): void
    {
        User::create([
            'id'         => $event->dto->fromUserId,
            'first_name' => $event->dto->fromFirstName,
            'last_name'  => $event->dto->fromLastName,
            'username'   => $event->dto->userName,
            'action'     => []
        ]);
    }

    public function shouldQueue(MessageEvent $event): bool
    {
        return empty($event->user);
    }
}
