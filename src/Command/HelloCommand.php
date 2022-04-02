<?php

namespace App\Command;

use BotMan\BotMan\BotMan;

class HelloCommand implements CommandInterface
{
    public function execute(BotMan $bot, string $parameters): void
    {
        $bot->reply("Hello {$parameters}");
    }

    public function getCommand(): string
    {
        return "hello";
    }

    public function matches(string $command): bool
    {
        return $this->getCommand() === $command;
    }
}
