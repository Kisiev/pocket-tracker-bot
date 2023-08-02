<?php

namespace Modules\Telegram\Handlers;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Models\Category;
use App\Models\Charge;
use App\Models\User;
use App\Services\AbstractCommandService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class InputChargeCommand extends AbstractCommandService
{
    protected Event $event = Event::InputCharge;
    protected const FIELD_ACTION_MAP = [
        'cost'        => 'inputCost',
        'category_id' => 'selectCategoryId',
        'title'       => 'inputChargeTitle',
    ];

    public function rules(): array
    {
        return [
            'cost'        => 'required|numeric',
            'category_id' => 'required|int|exists:categories,id',
            'title'       => 'required'
        ];
    }

    public function execute(MessageEvent $event): void
    {
        Charge::create([
            'cost' => $event->user->action->fields['cost'],
            'title' => $event->user->action->fields['title'],
            'category_id' => $event->user->action->fields['category_id'],
        ]);

        $this->telegramService->sendMessage(
            $event->user->id,
            'Сохранено',
        );
    }

    protected function inputCost(User $user)
    {
        $this->telegramService->sendMessage(
            $user->id,
            'Введите сумму',
        );
    }

    protected function inputChargeTitle(User $user)
    {
        $this->telegramService->sendMessage(
            $user->id,
            'Введите название траты',
        );
    }

    protected function selectCategoryId(User $user)
    {
        $categories = Category::where('user_id', $user->id)->get();
        $keyboard = [];

        foreach ($categories as $category) {
            $keyboard[] = [
                ['text' => $category->title, 'callback_data' => 'selectCategory_' . $category->id],
            ];
        }

        $keyboard[] = [
            ['text' => 'Добавить категорию', 'callback_data' => 'addCategory'],
        ];

        $this->telegramService->sendMessage(
            $user->id,
            'Выберите категорию',
            new InlineKeyboardMarkup($keyboard)
        );
    }
}
