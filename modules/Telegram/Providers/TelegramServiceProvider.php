<?php

namespace Modules\Telegram\Providers;

use App\Events\ChargesExportedEvent;
use App\Events\MessageEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Telegram\Commands\SetTelegramWebhookCommand;
use Modules\Telegram\Listeners\ExportListener;
use Modules\Telegram\Listeners\TelegramCommandListener;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::group([], __DIR__ . '/../Routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->schedule();
        }

        Event::listen(
            MessageEvent::class,
            [TelegramCommandListener::class, 'handle']
        );

        Event::listen(
            ChargesExportedEvent::class,
            [ExportListener::class, 'handle']
        );
    }

    private function schedule(): void
    {
        $this->commands([
            SetTelegramWebhookCommand::class,
        ]);
    }
}
