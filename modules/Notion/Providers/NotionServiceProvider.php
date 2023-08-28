<?php

namespace Modules\Notion\Providers;

use App\Events\ExportChargesEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Notion\Listeners\ExportListener;

class NotionServiceProvider extends ServiceProvider
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
        Event::listen(
            ExportChargesEvent::class,
            [ExportListener::class, 'handle']
        );
    }

    private function schedule(): void
    {

    }
}
