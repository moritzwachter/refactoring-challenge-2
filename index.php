<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Gitlab\Client;

require_once 'vendor/autoload.php';

$config = [
    "slack" => [
        "token" => '1234-5678-9ABC'
    ],
    'GITLAB_TOKEN' => "asdf1234",
    'GITLAB_HOST' => 'https://path.to/gitlab',
    'GITLAB_PROJECT_ID' => 1234
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Slack\SlackDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
$botman->hears('!hello', function (BotMan $bot) {
    $bot->reply('Hello yourself.');
});

$botman->hears('!projectdump', function (BotMan $bot) use ($config) {
    $searchedJob = 'dump';

    $client = new Gitlab\Client();
    $client->authenticate($config['GITLAB_TOKEN'], Client::AUTH_OAUTH_TOKEN);

    $pipelines = $client->projects()->pipelines($config['GITLAB_PROJECT_ID'], [
        'scope' => 'finished',
        'status' => 'success',
        'order_by' => 'id',
        'sort' => 'desc'
    ]);

    foreach ($pipelines as $pipeline) {
        $pipelineId = $pipeline['id'];
        $jobs = $client->jobs()->pipelineJobs($config['GITLAB_PROJECT_ID'], $pipelineId);

        foreach ($jobs as $job) {
            $jobId = $job['id'];

            if ($searchedJob === $job['name']) {
                $latestDumpId = $jobId;
                break;
            }
        }
    }

    if (!$config['GITLAB_HOST'] || !$latestDumpId) {
        return 'No dump could be found';
    }

    $backupFolderUrl = "https:///{$config['GITLAB_HOST']}/-/jobs/{$latestDumpId}/artifacts/browse/backups/";
    $downloadUrl = "https://{$config['GITLAB_HOST']}/-/jobs/{$latestDumpId}/artifacts/file/dump.sql.gz";
    $urls = [$backupFolderUrl, $downloadUrl];

    $bot->reply($downloadUrl);
});

$botman->hears('!help', function (BotMan $bot) {
    $commands = [
        '!help' => 'List all commands that are available',
        '!hello' => 'Greetings, Sir!',
        '!projectdump' => 'Get the latest DB dump from the pipeline',
    ];

    foreach ($commands as $command => $description) {
        $bot->reply("{$command}: {$description}");
    }
});

// Start listening
$botman->listen();
