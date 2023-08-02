<?php

namespace Modules\Telegram\Services;

use App\Dto\MessageDto;
use App\Enums\Event;
use App\Events\MessageEvent;
use App\Models\User;

class TelegramMessageAdapter
{
    public function makeEventByMessageData(array $messageData): MessageEvent
    {
        if (isset($messageData['callback_query'])) {
            $dto = $this->getDtoFromCallbackQuery($messageData);
        } else {
            $dto = $this->getDtoFromMessage($messageData);
        }

        $user = User::find($dto->fromUserId);

        return new MessageEvent($dto, $user);
    }

    private function getDtoFromCallbackQuery(array $messageData): MessageDto
    {
        [$commandName, $commandParams] = $this->parseCommand($messageData['callback_query']['data']);

        return new MessageDto(
            updateId: $messageData['update_id'],
            messageId: $messageData['callback_query']['message']['message_id'],
            fromUserId: $messageData['callback_query']['from']['id'],
            fromFirstName: $messageData['callback_query']['from']['first_name'],
            fromLastName: $messageData['callback_query']['from']['last_name'],
            userName: $messageData['callback_query']['from']['username'],
            chatId: $messageData['callback_query']['message']['chat']['id'],
            date: $messageData['callback_query']['message']['date'],
            rawData: $messageData,
            text: $messageData['callback_query']['message']['text'],
            command: Event::tryFrom($commandName),
            commandParams: $commandParams,
        );
    }

    private function getDtoFromMessage(array $messageData): MessageDto
    {
        return new MessageDto(
            updateId: $messageData['update_id'],
            messageId: $messageData['message']['message_id'],
            fromUserId: $messageData['message']['from']['id'],
            fromFirstName: $messageData['message']['from']['first_name'],
            fromLastName: $messageData['message']['from']['last_name'],
            userName: $messageData['message']['from']['username'],
            chatId: $messageData['message']['chat']['id'],
            date: $messageData['message']['date'],
            rawData: $messageData,
            text: $messageData['message']['text'],
        );
    }

    public function parseCommand(string $command): array
    {
        $commandParts = explode('_', $command);
        $commandName = array_shift($commandParts);
        return [$commandName, $commandParts];
    }
}
