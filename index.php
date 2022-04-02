<?php

use App\Chatbot;
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
    'GITLAB_TOKEN' => $_ENV['GITLAB_TOKEN'],
    'GITLAB_HOST' => 'https://path.to/gitlab',
    'GITLAB_PROJECT_ID' => 1234
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Slack\SlackDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Initialise Chatbot object
$commands = [
    new HelloCommand()
];

$chatbot = new Chatbot($botman, $config, $commands);
$chatbot->configureCommands();
$chatbot->listen();
