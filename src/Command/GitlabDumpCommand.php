<?php

namespace App\Command;

use BotMan\BotMan\BotMan;
use Gitlab\Client;

class GitlabDumpCommand extends AbstractCommand
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function execute(BotMan $bot, string $parameters): void
    {
        $url = sprintf("https://%s/api/v4/projects/%d/jobs/artifacts/%s/raw/%s?job=%s",
            $this->config['GITLAB_HOST'],
            $this->config['GITLAB_PROJECT_ID'],
            $this->config['GITLAB_BRANCH'],
            $this->config['GITLAB_DUMP_FILE'],
            $this->config['GITLAB_JOB_NAME']
        );

        $bot->reply($url);
    }

    public function getDescription(): string
    {
        return "Get the latest DB dump from the pipeline";
    }

    public function getCommand(): string
    {
        return "!projectdump";
    }
}
