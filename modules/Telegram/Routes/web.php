<?php

use Illuminate\Support\Facades\Route;
use Modules\Telegram\Http\Controllers\TelegramController;

Route::match(['POST', 'GET'], '/telegram/webhook', [TelegramController::class, 'index']);
