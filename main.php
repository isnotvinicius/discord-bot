<?php

require __DIR__ . '/vendor/autoload.php';

use src\discord\Bot;
use src\database\Seeder;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    echo "Error loading .env file: " . $e->getMessage() . PHP_EOL;
    return;
}

Seeder::initializeDatabase();

$bot = new Bot();
$bot->run($_ENV['DISCORD_TOKEN']);
