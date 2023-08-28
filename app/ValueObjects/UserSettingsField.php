<?php

namespace App\ValueObjects;

class UserSettingsField
{
    public function __construct(
        public ?string $notionSecret = null,
        public ?string $notionBlockId = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new UserSettingsField(
            $data['notion_secret'] ?? null,
            $data['notion_block_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'notion_secret'   => $this->notionSecret ?? null,
            'notion_block_id' => $this->notionBlockId ?? null,
        ];
    }
}
