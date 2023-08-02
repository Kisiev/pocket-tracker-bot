<?php

namespace App\ValueObjects;

use App\Enums\Event;

class UserActionField
{
    public function __construct(
        public ?Event $command = null,
        public ?array $fields = [],
        public ?string $currentField = '',
        public ?array $commandHistory = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        $command = Event::tryFrom($data['command'] ?? '') ?? null;
        return new UserActionField(
            $command,
            $data['fields'] ?? [],
            $data['currentField'] ?? '',
            $data['commandHistory'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'command'        => $this->command?->value,
            'fields'         => $this->fields,
            'currentField'   => $this->currentField,
            'commandHistory' => $this->commandHistory,
        ];
    }

    public function clear(): void
    {
        $this->command = null;
        $this->fields = [];
        $this->currentField = '';
        $this->commandHistory = [];
    }

    public function popLastCommand(): ?Event
    {
        $command = array_pop($this->commandHistory);
        return Event::tryFrom($command);
    }
}
