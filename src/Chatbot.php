<?php

namespace App;

use App\Command\CommandInterface;
use BotMan\BotMan\BotMan;

class Chatbot
{
    private BotMan $botman;

    /** @var CommandInterface[] $commands */
    private array $commands;

    public function __construct(BotMan $bot, array $commands)
    {
        $this->botman = $bot;
        $this->commands = $commands;
    }

    public function configureCommands(): void
    {
        $this->botman->hears('^(!\S+)( .+){0,1}$', function (BotMan $bot, $command, $parameters = '') {
            if ($bot->getMessage()->isFromBot()) {
                return;
            }

            foreach ($this->commands as $commandInstance) {
                if ($commandInstance->matches($command)) {
                    $commandInstance->execute($bot, $parameters);
                }
            }
        });
    }

    public function listen(): void
    {
        $this->botman->listen();
    }
}
