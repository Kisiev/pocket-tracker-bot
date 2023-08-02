<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Testing\TestResponse;

use function Pest\Laravel\postJson;

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\DatabaseMigrations::class,
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
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

