<?php

namespace src\database;

use Exception;

class Seeder
{
    public static function initializeDatabase()
    {
        try {
            $pdo = Connection::connect();

            $createTableSql = '
                CREATE TABLE IF NOT EXISTS commands (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    command TEXT NOT NULL,
                    message TEXT NOT NULL,
                    counter INTEGER NOT NULL
                );
            ';
            $pdo->exec($createTableSql);

            $commands = [
                '!vinixas' => 'Vinixas já serviu como outdoor {counter} vezes (testona da porra)',
                '!conhem' => 'A fortuna da herança do Conhem está atualmente avaliada em R${counter}',
                '!pesadelo' => 'Pesadelo já carpiu {counter} matos, nosso periquito do governo',
                '!ravisk' => 'Ravisk - vulgo Arauto do Vale - já cabeçeou {counter} torres',
                '!lorenzo' => 'Lorenzetti já queimou a resistência {counter} vezes - lá ele',
                '!sarx' => 'Sarx já entregou {counter} pacotes de dorgas como aviãozinho no RP',
                '!vrilipe' => 'Felipe já comprou {counter} jogos, e não zerou nenhum'
            ];

            $stmt = $pdo->prepare("INSERT OR IGNORE INTO commands (command, message, counter) VALUES (:command, :message, :counter)");

            $pdo->beginTransaction();
            foreach ($commands as $command => $message) {
                $initialCounter = ($command === '!conhem') ? 1000000 : 0;
                $stmt->execute([
                    ':command' => $command,
                    ':message' => $message,
                    ':counter' => $initialCounter
                ]);
            }
            $pdo->commit();

            echo 'Database initialized successfully.' . PHP_EOL;

        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo 'Database initialization failed: ' . $e->getMessage() . PHP_EOL;
            exit;
        }
    }
}
