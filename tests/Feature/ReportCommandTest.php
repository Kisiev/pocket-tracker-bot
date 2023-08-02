<?php

use Modules\Telegram\Services\TelegramService;

use function Pest\Laravel\partialMock;

test('success report', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->once();

    sendMessage('/start');
    sendMessage('/report');

    $this->assertDatabaseHas('users', ['id' => 1]);
});
