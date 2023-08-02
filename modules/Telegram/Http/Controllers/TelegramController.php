<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Telegram\Services\TelegramMessageAdapter;

class TelegramController extends Controller
{
    public function __construct(
        private readonly TelegramMessageAdapter $telegramMessageAdapter,
    ) {
    }

    public function index(Request $request)
    {
        $messageEvent = $this->telegramMessageAdapter->makeEventByMessageData($request->all());
        event($messageEvent);
    }
}
