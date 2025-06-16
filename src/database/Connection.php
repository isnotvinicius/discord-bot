<?php

namespace src\database;

use PDO;
use Exception;

class Connection
{
    public static function connect(): PDO
    {
        try {
            $databasePath = __DIR__ . '/../../db.sqlite';
            $pdo = new PDO('sqlite:' . $databasePath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $e) {
            echo 'Database connection failed: ' . $e->getMessage() . PHP_EOL;
            exit;
        }
    }

    public static function getCommand($command)
    {
        try {
            $pdo = Connection::connect();

            $updateStmt = $pdo->prepare('UPDATE commands SET counter = counter + 1 WHERE command = :command');
            $updateStmt->execute([':command' => $command]);

            $selectStmt = $pdo->prepare('SELECT * FROM commands WHERE command = :command');
            $selectStmt->execute([':command' => $command]);

            return $selectStmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            echo "Failed to retrieve command from database: " . $e->getMessage();
            exit();
        }
    }

    public static function listCommands()
    {
        try {
            $pdo = Connection::connect();

            $selectStmt = $pdo->prepare('SELECT DISTINCT command FROM commands');

            $selectStmt->execute();

            $commands = $selectStmt->fetchAll(PDO::FETCH_COLUMN);

            return $commands;
        } catch (Exception $e) {
            echo "Failed to retrieve commands from database: " . $e->getMessage();
            exit();
        }
    }
}
