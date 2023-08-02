<?php

namespace App\Services;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Interfaces\Command;
use App\Models\User;
use Illuminate\Support\Arr;
use Modules\Telegram\Services\TelegramService;

abstract class AbstractCommandService implements Command
{
    protected const FIELD_ACTION_MAP = [];
    protected Event $event;

    public function __construct(
        protected readonly TelegramService $telegramService,
        protected readonly UserActionValidator $validator,
    ) {
    }

    public function rules(): array
    {
        return [];
    }

    public function run(MessageEvent $event): void
    {
        $this->saveCurrentFieldIfExists($event);

        if ($invalidField = $this->getFirstInvalidField($event->user)) {
            $this->saveActionWithHistory($event->user, $invalidField);
            $this->requestedField($event->user, $invalidField);
            return;
        }

        $this->execute($event);

        $this->after($event);
    }

    private function getFirstInvalidField(User $user): ?string
    {
        $errors = $this->validator->validate($this->rules(), $user);

        if (empty($errors)) {
            return null;
        }

        return array_key_first($errors);
    }

    private function saveActionWithHistory(User $user, string $field): void
    {
        $user->action->currentField = $field;
        $user->action->command = $this->event;
        $lastCommand = Arr::last($user->action->commandHistory);

        if ($lastCommand !== $this->event->value) {
            $user->action->commandHistory[] = $this->event->value;
        }

        $user->save();
    }

    private function requestedField(User $user, string $field): void
    {
        if (isset(static::FIELD_ACTION_MAP[$field])) {
            $this->{static::FIELD_ACTION_MAP[$field]}($user);
        }
    }

    private function saveCurrentFieldIfExists(MessageEvent $event)
    {
        $user = $event->user;
        if (!empty($user->action->currentField)) {
            $user->action->fields[$user->action->currentField] = $event->dto->commandParams[0] ?? $event->dto->text;
            $user->save();
        }
    }

    public abstract function execute(MessageEvent $event): void;

    public function after(MessageEvent $event): void
    {
        $event->user->action->popLastCommand();
        if ($lastEvent = Arr::last($event->user->action->commandHistory)) {
            $event->user->action->command = Event::tryFrom($lastEvent);
            $event->user->action->currentField = '';
            $event->user->save();
            event($event);
            return;
        }

        $event->user->action->clear();
        $event->user->save();
    }
}
