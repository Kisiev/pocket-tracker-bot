<?php

use Modules\Telegram\Services\TelegramService;

use function Pest\Laravel\partialMock;

it('success delete without transfer', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->times(3);

    sendMessage('/start');
    fakeCharges();
    sendMessage('/deletecategory');
    sendCommand('deleteCategory_1');
    sendCommand('deleteCategory_0');

    $this->assertDatabaseMissing('categories', ['id' => 1]);
});

it('success delete with transfer', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->times(3);

    sendMessage('/start');
    fakeCharges();
    fakeCharges();

    $this->assertDatabaseHas('categories', ['id' => 1]);
    $this->assertDatabaseHas('categories', ['id' => 2]);
    $this->assertDatabaseHas('charges', ['id' => 1, 'category_id' => 1]);
    $this->assertDatabaseHas('charges', ['id' => 2, 'category_id' => 2]);

    sendMessage('/deletecategory');
    sendCommand('deleteCategory_2');
    sendCommand('deleteCategory_1');

    $this->assertDatabaseMissing('categories', ['id' => 2]);
    $this->assertDatabaseHas('charges', ['id' => 1, 'category_id' => 1]);
    $this->assertDatabaseHas('charges', ['id' => 2, 'category_id' => 1]);
});
