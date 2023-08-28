<?php

namespace Modules\Telegram\Listeners;

use App\Enums\Event;
use App\Events\MessageEvent;
use App\Interfaces\Command;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Telegram\Handlers\AddCategoryCommand;
use Modules\Telegram\Handlers\DeleteCategoryCommand;
use Modules\Telegram\Handlers\ExportCommand;
use Modules\Telegram\Handlers\InputChargeCommand;
use Modules\Telegram\Handlers\ReportCommand;

class TelegramCommandListener implements ShouldQueue
{
    private const COMMAND_MAP = [
        '/addcharge' => InputChargeCommand::class,
        '/addcategory' => AddCategoryCommand::class,
        '/deletecategory' => DeleteCategoryCommand::class,
        '/report' => ReportCommand::class,
        '/export' => ExportCommand::class,
    ];

    private function getHandlerFromCommand(Event $event): Command
    {
        $hanlerMap = [
            Event::InputCharge->value => InputChargeCommand::class,
            Event::AddCategory->value => AddCategoryCommand::class,
            Event::SelectCategory->value => InputChargeCommand::class,
            Event::DeleteCategory->value => DeleteCategoryCommand::class,
            Event::SelectTransferCategory->value => DeleteCategoryCommand::class,
        ];

        if (isset($hanlerMap[$event->value])) {
            return app($hanlerMap[$event->value]);
        }

        throw new Exception('handler not found');
    }

    public function __construct()
    {

    }

    /**
     * @throws Exception
     */
    public function handle(MessageEvent $event): void
    {
        if (isset(self::COMMAND_MAP[$event->dto->text])) {
            $event->user->action->clear();
            $event->user->save();

            app(self::COMMAND_MAP[$event->dto->text])->run($event);
            return;
        }

        $command = $event->dto->command ?? $event->user->action->command;
        if ($command) {
            $handler = $this->getHandlerFromCommand($command);
            $handler->run($event);
        }
    }

    public function shouldQueue(MessageEvent $event): bool
    {
        return !empty($event->user);
    }
}
