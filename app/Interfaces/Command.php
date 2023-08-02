<?php

namespace App\Interfaces;

use App\Events\MessageEvent;

interface Command
{
    public function run(MessageEvent $event): void;
}
