<?php

namespace App\Command;

use BotMan\BotMan\BotMan;

interface CommandInterface
{
    public function execute(BotMan $bot, string $parameters): void;

    public function getCommand(): string;

    public function matches(string $command): bool;

    public function getDescription(): string;
}
