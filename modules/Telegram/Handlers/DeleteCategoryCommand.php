<?php

namespace Modules\Telegram\Handlers;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Models\Category;
use App\Models\Charge;
use App\Models\User;
use App\Services\AbstractCommandService;
use Illuminate\Database\Eloquent\Builder;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class DeleteCategoryCommand extends AbstractCommandService
{
    protected Event $event = Event::DeleteCategory;
    protected const FIELD_ACTION_MAP = [
        'category_id'          => 'selectCategory',
        'transfer_category_id' => 'selectTransferCategory',
    ];

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id'
        ];
    }

    public function execute(MessageEvent $event): void
    {
        $category = Category::find($event->user->action->fields['category_id']);

        if ($category->charges->count() > 0 && ($event->user->action->fields['transfer_category_id'] ?? null) === null) {
            $this->saveActionWithHistory($event->user, 'transfer_category_id');
            $this->requestField($event->user, 'transfer_category_id');
            return;
        }

        if ($transferCategoryId = ($event->user->action->fields['transfer_category_id'] ?? null)) {
            Charge::where('category_id', $event->user->action->fields['category_id'])->update(['category_id' => $transferCategoryId]);
        }

        $category->delete();

        $this->telegramService->sendMessage(
            $event->user->id,
            'Удалено',
        );

        $this->after($event);
    }

    protected function selectCategory(User $user): void
    {
        $keyboard = $this->getCategoriesLikeKeyboard($user, Event::DeleteCategory->value);

        $this->telegramService->sendMessage(
            $user->id,
            'Выберите категорию',
            new InlineKeyboardMarkup($keyboard)
        );
    }

    protected function selectTransferCategory(User $user): void
    {
        $keyboard = $this->getCategoriesLikeKeyboard($user, Event::SelectTransferCategory->value);

        $keyboard[] = [
            ['text' => 'Не перемещать (удалить)', 'callback_data' => Event::SelectTransferCategory->value . '_0'],
        ];

        $this->telegramService->sendMessage(
            $user->id,
            'Куда перемесить',
            new InlineKeyboardMarkup($keyboard)
        );
    }

    protected function getCategoriesLikeKeyboard(User $user, string $eventName): array
    {
        $categories = Category::where('user_id', $user->id)
            ->when(($user->action->fields['category_id'] ?? null), function (Builder $builder) use ($user) {
                $builder->whereNot('id', $user->action->fields['category_id']);
            })
            ->get();
        $keyboard = [];

        foreach ($categories as $category) {
            $keyboard[] = [
                ['text' => $category->title, 'callback_data' => $eventName . '_' . $category->id],
            ];
        }

        return $keyboard;
    }
}
