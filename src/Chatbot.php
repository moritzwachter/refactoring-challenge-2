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

    # this is just temporary
    public function configureCommands(): void
    {
        $this->botman->hears('^!(\S+)( .+){0,1}$', function (BotMan $bot, $command, $parameters = '') {
            if ($bot->getMessage()->isFromBot()) {
                return;
            }

            foreach ($this->commands as $commandInstance) {
                if ($commandInstance->matches($command)) {
                    $commandInstance->execute($bot, $parameters);
                }
            }
        });

        $this->botman->hears('!help', function (BotMan $bot) {
            $commands = [
                '!help' => 'List all commands that are available',
                '!hello' => 'Greetings, Sir!',
                '!projectdump' => 'Get the latest DB dump from the pipeline',
            ];

            foreach ($commands as $command => $description) {
                $bot->reply("{$command}: {$description}");
            }
        });
    }

    public function listen(): void
    {
        // Start listening
        $this->botman->listen();
    }
}