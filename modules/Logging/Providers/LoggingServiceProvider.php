<?php

namespace Modules\Logging\Providers;

use App\Events\AnswerEvent;
use App\Events\MessageEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Logging\Listeners\MessageListener;
use Modules\Logging\Listeners\TelegramAnswerListener;

class LoggingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(
            MessageEvent::class,
            [MessageListener::class, 'handle']
        );

        Event::listen(
            AnswerEvent::class,
            [TelegramAnswerListener::class, 'handle']
        );
    }
}
