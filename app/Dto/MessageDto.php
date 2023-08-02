<?php

namespace App\Dto;

use App\Enums\Event;

class MessageDto
{
    public function __construct(
        public readonly int $updateId,
        public readonly int $messageId,
        public readonly int $fromUserId,
        public readonly string $fromFirstName,
        public readonly string $fromLastName,
        public readonly string $userName,
        public readonly int $chatId,
        public readonly int $date,
        public readonly ?array $rawData = [],
        public readonly ?string $text = null,
        public readonly ?Event $command = null,
        public readonly ?array $commandParams = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'update_id'       => $this->updateId,
            'message_id'      => $this->messageId,
            'from_user_id'    => $this->fromUserId,
            'from_first_name' => $this->fromFirstName,
            'from_last_name'  => $this->fromLastName,
            'user_name'       => $this->userName,
            'chat_id'         => $this->chatId,
            'date'            => $this->date,
            'text'            => $this->text,
            'raw_data'        => $this->rawData,
            'command'         => $this->command?->value,
            'command_params'  => $this->commandParams,
        ];
    }
}
