<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once './comandos.php';
require_once './quest.php';

$connection = new \Phergie\Irc\Connection();

$connection
    ->setServerHostname('irc.chat.twitch.tv')
    ->setServerPort(6667)
    ->setPassword($password)
    ->setNickname($seuBot)
    ->setUsername($seuBot);

$client = new \Phergie\Irc\Client\React\Client();

$client->on('connect.after.each', function ($connection, $write) {
    global $seuCanal;
    $write->ircJoin($seuCanal);
    $write->ircPrivmsg($seuCanal, 'Cheguei? Depende...');
});

$client->on('irc.received', function ($message, $write, $connection, $logger) {
    global $seuCanal;

    if ($message['command'] == 'PRIVMSG') {

        $comando = null;
        if (strripos(strtolower($message['params']['text']), "!") === 0) {
            $comando = explode(" ", strtolower($message['params']['text']))[0];
        }

        if (!is_null($comando)) {
            switch ($comando) {
                case "!ban":
                    ban($message, $write, $seuCanal);
                    break;
                case "!pergunta":
                    perguntas($message, $write, $seuCanal);
                    break;
                case "!social":
                case "!twitter":
                case "!github":
                case "!instagram":
                case "!discord":
                    social($message, $write, $seuCanal);
                    break;
                case "!comandos":
                    comandos($message, $write, $seuCanal);
                    break;
            };
        }
    }
});

$client->run($connection);
