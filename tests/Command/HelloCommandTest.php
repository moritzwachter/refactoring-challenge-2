<?php

namespace App\Tests\Command;

use App\Command\HelloCommand;
use BotMan\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class HelloCommandTest extends TestCase
{
    public function testMatches(): void
    {
        $cmd = new HelloCommand();

        $this->assertTrue($cmd->matches('!hello'));
        $this->assertFalse($cmd->matches('!goodbye'));
    }

    public function testExecute(): void
    {
        $cmd = new HelloCommand();

        $botMock = $this->createMock(BotMan::class);
        $botMock->expects($this->once())
            ->method('reply')
            ->with("Hello World")
        ;

        $cmd->execute($botMock, "World");
    }
}
