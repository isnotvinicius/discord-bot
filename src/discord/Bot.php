<?php

namespace src\discord;

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use src\database\Connection;

class Bot
{
    public function run(string $token)
    {
        $discord = new Discord([
            'token' => $token,
            'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT,
        ]);

        $discord->on('init', function (Discord $discord) {
            echo "Bot is ready!", PHP_EOL;

            $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
                if ($message->author->bot) return;

                $messageContent = preg_replace('/\s*<[^>]*>\s*/', '', $message->content);

                $this->replyMessage($message, $messageContent);
            });
        });

        $discord->run();
    }

    private function replyMessage(Message $message, string $userCommand)
    {
        if ($userCommand === '!comandos') {
            $commands = Connection::listCommands();

            // Format the list of commands as a string with line breaks
            $commandsList = implode("\n", $commands);

            $commandsMessage = "📋 Comandos Disponíveis:\n"
                . $commandsList . "\n"
                . "Execute um dos comandos para receber uma mensagem personalizada!";

            $message->reply(trim($commandsMessage));

            return;
        }

        $command = Connection::getCommand($userCommand);

        $messageToReply = str_replace('{counter}', $command['counter'], $command['message']);

        $message->reply($messageToReply);

        return;
    }
}
