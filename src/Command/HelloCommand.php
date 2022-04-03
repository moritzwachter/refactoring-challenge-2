<?php

namespace App\Command;

use BotMan\BotMan\BotMan;

class HelloCommand extends AbstractCommand
{
    public function execute(BotMan $bot, string $parameters): void
    {
        $bot->reply("Hello {$parameters}");
    }

    public function getCommand(): string
    {
        return "!hello";
    }

    public function getDescription(): string
    {
        return "Greetings, Sir!";
    }
}
