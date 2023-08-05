<?php


use App\Models\Category;
use App\Models\Charge;
use Illuminate\Testing\TestResponse;

use function Pest\Laravel\postJson;

function fakeCharges(): void
{
    $category = Category::create([
        'title' => 'category_1',
        'user_id' => 1,
    ]);

    Charge::create([
        'title' => 'charge',
        'cost' => 30,
        'category_id' => $category->id,
    ]);
}

function sendMessage(string $text): TestResponse
{
    $response = postJson(
        '/telegram/webhook',
        [
            'update_id' => 70372470,
            'message'   => [
                'message_id' => 209,
                'from'       => [
                    'id'            => 1,
                    'is_bot'        => 0,
                    'first_name'    => 'ИВАНОВ',
                    'last_name'     => 'ИВАН',
                    'username'      => 'Иван',
                    'language_code' => 'ru'
                ],
                'chat'       => [
                    'id'         => 1,
                    'first_name' => 'ИВАНОВ',
                    'last_name'  => 'ИВАН',
                    'username'   => 'Иван',
                    'type'       => 'private'
                ],
                'date'       => 1690882279,
                'text'       => $text
            ]
        ]
    );

    $response->assertStatus(200);
    return $response;
}

function sendCommand(string $text): TestResponse
{
    $response = postJson(
        '/telegram/webhook',
        [
            'update_id'      => 70372472,
            'callback_query' => [
                'id'            => 472217191374094510,
                'from'          => [
                    'id'            => 1,
                    'is_bot'        => null,
                    'first_name'    => 'ИВАНОВ',
                    'last_name'     => 'ИВАН',
                    'username'      => 'Иван',
                    'language_code' => 'ru',
                ],
                'message'       => [
                    'message_id'   => 213,
                    'from'         => [
                        'id'         => 5947827748,
                        'is_bot'     => 1,
                        'first_name' => 'moneyBot',
                        'username'   => 'бот',
                    ],
                    'chat'         => [
                        'id'         => 1,
                        'first_name' => 'ИВАНОВ',
                        'last_name'  => 'ИВАН',
                        'username'   => 'Иван',
                        'type'       => 'private',
                    ],
                    'date'         => 1690882302,
                    'text'         => 'Выберите категорию',
                    'reply_markup' => [
                        'inline_keyboard' => [
                            0 => [
                                0 => [
                                    'text'          => 'asd',
                                    'callback_data' => 'selectCategory_3',
                                ],
                            ],
                            1 => [
                                0 => [
                                    'text'          => 213,
                                    'callback_data' => 'selectCategory_4',
                                ],
                            ],
                            2 => [
                                0 => [
                                    'text'          => 'sad',
                                    'callback_data' => 'selectCategory_5',
                                ],
                            ],
                            3 => [
                                0 => [
                                    'text'          => 'Добавить категорию',
                                    'callback_data' => 'addCategory',
                                ],
                            ],
                        ],
                    ],
                ],
                'chat_instance' => 1716392257380859241,
                'data'          => $text,
            ],
        ]
    );

    $response->assertStatus(200);
    return $response;
}
