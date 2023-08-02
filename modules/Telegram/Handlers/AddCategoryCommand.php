<?php

namespace Modules\Telegram\Handlers;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Models\Category;
use App\Models\User;
use App\Services\AbstractCommandService;

class AddCategoryCommand extends AbstractCommandService
{
    protected Event $event = Event::AddCategory;
    protected const FIELD_ACTION_MAP = [
        'title' => 'inputCategoryTitle',
    ];

    public function rules(): array
    {
        return [
            'title' => 'required'
        ];
    }

    public function execute(MessageEvent $event): void
    {
        $category = Category::create([
            'title' => $event->user->action->fields['title'],
            'user_id' => $event->user->id,
        ]);

        $event->user->action->fields['category_id'] = $category->id;
        unset($event->user->action->fields['title']);
        $event->user->save();

        $this->telegramService->sendMessage(
            $event->user->id,
            'Сохранено',
        );
    }

    protected function inputCategoryTitle(User $user)
    {
        $this->telegramService->sendMessage(
            $user->id,
            'Введите название категории',
        );
    }
}
