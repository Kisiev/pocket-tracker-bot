<?php

use Modules\Telegram\Services\TelegramService;

use function Pest\Laravel\partialMock;

it('success add-category command', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->twice();

    sendMessage('/start');
    sendMessage('/addcategory');
    sendMessage('new category');

    $this->assertDatabaseHas('users', ['id' => 1]);
    $this->assertDatabaseHas('categories', ['user_id' => 1, 'title' => 'new category']);
});
