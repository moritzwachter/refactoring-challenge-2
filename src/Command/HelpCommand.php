<?php

namespace App\Command;

use BotMan\BotMan\BotMan;

class HelpCommand extends AbstractCommand
{
    /** @var CommandInterface[] */
    protected array $commands = [];

    public function setCommands(array $commands): void
    {
        $this->commands = $commands;
    }

    public function execute(BotMan $bot, string $parameters): void
    {
        $response = '';
        foreach ($this->commands as $command) {
            $response .= "{$command->getCommand()}: {$command->getDescription()}" . PHP_EOL;
        }

        $bot->reply($response);
    }

    public function getCommand(): string
    {
        return '!help';
    }

    public function getDescription(): string
    {
        return 'List all commands that are available';
    }
}