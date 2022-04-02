<?php

namespace App\Command;

abstract class AbstractCommand implements CommandInterface
{
    public function matches(string $command): bool
    {
        return $this->getCommand() === $command;
    }
}