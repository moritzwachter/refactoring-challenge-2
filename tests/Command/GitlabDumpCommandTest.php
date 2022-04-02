<?php

namespace App\Tests\Command;

use App\Command\GitlabDumpCommand;
use BotMan\BotMan\BotMan;
use Gitlab\Client;
use PHPUnit\Framework\TestCase;

class GitlabDumpCommandTest extends TestCase
{
    public function testExecutionWithProperConfig(): void
    {
        $bot = $this->createMock(BotMan::class);
        $config = [
            'GITLAB_HOST' => 'gitlab.com',
            'GITLAB_PROJECT_ID' => '12345',
            'GITLAB_DUMP_FILE' => 'dump.sql.gz',
            'GITLAB_JOB_NAME' => 'dump',
            'GITLAB_BRANCH' => 'main'
        ];
        $command = new GitlabDumpCommand($config);

        $bot->expects($this->once())
            ->method('reply')
            ->with('https://gitlab.com/api/v4/projects/12345/jobs/artifacts/main/raw/dump.sql.gz?job=dump');

        $command->execute($bot, '');
    }
}
