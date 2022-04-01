<?php

namespace App;

use BotMan\BotMan\BotMan;
use Gitlab\Client;

class Chatbot
{
    private array $config;
    private BotMan $botman;

    public function __construct(BotMan $bot, array $config)
    {
        $this->botman = $bot;
        $this->config = $config;
    }

    # this is just temporary
    public function configureCommands(): void
    {
        $this->botman->hears('!hello', function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        $this->botman->hears('!projectdump', function (BotMan $bot) {
            $searchedJob = 'dump';

            $client = new Client();
            $client->authenticate($this->config['GITLAB_TOKEN'], Client::AUTH_OAUTH_TOKEN);

            $pipelines = $client->projects()->pipelines($this->config['GITLAB_PROJECT_ID'], [
                'scope' => 'finished',
                'status' => 'success',
                'order_by' => 'id',
                'sort' => 'desc'
            ]);

            foreach ($pipelines as $pipeline) {
                $pipelineId = $pipeline['id'];
                $jobs = $client->jobs()->pipelineJobs($this->config['GITLAB_PROJECT_ID'], $pipelineId);

                foreach ($jobs as $job) {
                    $jobId = $job['id'];

                    if ($searchedJob === $job['name']) {
                        $latestDumpId = $jobId;
                        break;
                    }
                }
            }

            if (!$this->config['GITLAB_HOST'] || !$latestDumpId) {
                return 'No dump could be found';
            }

            $backupFolderUrl = "https:///{$this->config['GITLAB_HOST']}/-/jobs/{$latestDumpId}/artifacts/browse/backups/";
            $downloadUrl = "https://{$this->config['GITLAB_HOST']}/-/jobs/{$latestDumpId}/artifacts/file/dump.sql.gz";
            $urls = [$backupFolderUrl, $downloadUrl];

            $bot->reply($downloadUrl);
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