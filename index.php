<?php

use App\Chatbot;
use App\Command\GitlabDumpCommand;
use App\Command\HelloCommand;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.local');
$dotenv->safeLoad();

$config = [
    "slack" => [
        "token" => $_ENV['SLACK_TOKEN']
    ],
    'GITLAB_HOST' => $_ENV['GITLAB_HOST'],
    'GITLAB_PROJECT_ID' => $_ENV['GITLAB_PROJECT_ID'],
    'GITLAB_BRANCH' => $_ENV['GITLAB_BRANCH'],
    'GITLAB_DUMP_FILE' => $_ENV['GITLAB_DUMP_FILE'],
    'GITLAB_JOB_NAME' => $_ENV['GITLAB_JOB_NAME'],
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Slack\SlackDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Initialise Chatbot object
$commands = [
    new HelloCommand(),
    new GitlabDumpCommand($config),
];

$chatbot = new Chatbot($botman, $commands);
$chatbot->configureCommands();
$chatbot->listen();
