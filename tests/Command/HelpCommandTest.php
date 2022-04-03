<?php

namespace App\Tests\Command;

use App\Command\GitlabDumpCommand;
use App\Command\HelloCommand;
use App\Command\HelpCommand;
use BotMan\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class HelpCommandTest extends TestCase
{
    public function testListMultipleCommands(): void
    {
        $commands = [
            new HelloCommand(),
            new GitlabDumpCommand([])
        ];

        $helpCommand = new HelpCommand();
        $helpCommand->setCommands($commands);

        $line = '!hello: Greetings, Sir!' . PHP_EOL;
        $line .= '!projectdump: Get the latest DB dump from the pipeline' . PHP_EOL;

        $botMock = $this->createMock(BotMan::class);
        $botMock->expects($this->once())
            ->method('reply')
            ->with($line);

        $helpCommand->execute($botMock, '');
    }

    public function testListOneCommand(): void
    {
        $helpCommand = new HelpCommand();
        $commands = [$helpCommand];
        $helpCommand->setCommands($commands);

        $line = '!help: List all commands that are available' . PHP_EOL;

        $botMock = $this->createMock(BotMan::class);
        $botMock->expects($this->once())
            ->method('reply')
            ->with($line);

        $helpCommand->execute($botMock, '');
    }

    public function testListNoCommand(): void
    {
        $helpCommand = new HelpCommand();

        $botMock = $this->createMock(BotMan::class);
        $botMock->expects($this->once())->method('reply')->with('');

        $helpCommand->execute($botMock, '');
    }
}
