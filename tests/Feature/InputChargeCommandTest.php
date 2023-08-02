<?php

use App\Enums\Event;
use App\Models\User;
use Modules\Telegram\Services\TelegramService;

use function Pest\Laravel\partialMock;

it('success input-charge command', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->times(6);

    sendMessage('/start');
    sendMessage('/addcharge');
    sendMessage('30');

    $this->assertDatabaseHas('users', ['id' => 1]);

    /** @var User $user */
    $user = User::find(1);
    expect($user)->action->command->toBe(Event::InputCharge);

    sendCommand('addCategory');

    $user->refresh();
    expect($user)->action->command->toBe(Event::AddCategory);

    sendMessage('new category');
    sendMessage('new charge');

    $this->assertDatabaseHas('charges', ['id' => 1, 'title' => 'new charge', 'cost' => 30]);
});

it('success input-charge with select category command', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->times(6);

    // create category
    sendMessage('/start');
    sendMessage('/addcategory');
    sendMessage('new category');

    sendMessage('/addcharge');
    sendMessage('300');

    $this->assertDatabaseHas('users', ['id' => 1]);

    /** @var User $user */
    $user = User::find(1);
    expect($user)->action->command->toBe(Event::InputCharge);

    sendCommand('selectCategory_1');

    $user->refresh();
    expect($user)->action->command->toBe(Event::InputCharge);

    sendMessage('new charge');

    $this->assertDatabaseHas('charges', ['id' => 1, 'title' => 'new charge', 'cost' => 300]);
});

it('incorrect category when input-charge', function () {
    partialMock(TelegramService::class)->shouldReceive('sendMessage')->times(6);

    sendMessage('/start');
    sendMessage('/addcharge');
    sendMessage('300');
    sendCommand('selectCategory_1');
    sendCommand('selectCategory_2');
    sendCommand('selectCategory_3');
    sendCommand('selectCategory_4');

    $this->assertDatabaseHas('users', ['id' => 1]);

    /** @var User $user */
    $user = User::find(1);
    expect($user)->action->command->toBe(Event::InputCharge);

    $this->assertDatabaseMissing('charges', ['id' => 1, 'title' => 'new charge', 'cost' => 300]);
});
