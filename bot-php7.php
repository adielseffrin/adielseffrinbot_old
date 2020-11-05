<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
//require_once "./conn.php";
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
//    global $conn;
    $write->ircJoin($seuCanal);
    $write->ircPrivmsg($seuCanal, 'Cheguei? Depende...');
});

$client->on('irc.received', function ($message, $write, $connection, $logger) {
    global $seuCanal;
//    global $conn;

    if ($message['command'] == 'PRIVMSG') {

        $comando = null;
        if (strripos(strtolower($message['params']['text']), "!") === 0)
            $comando = explode(" ", strtolower($message['params']['text']))[0];

        if (!is_null($comando))
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
                    social($message, $write, $seuCanal);
                    break;
                case "!comandos":
                    comandos($message, $write, $seuCanal);
                    break;
                case "!discord":
                    discord($message, $write, $seuCanal);
                    break;
                    //case "!quest":
                    //     quest($message, $write, $seuCanal,$conn);
                    //break;
                    //case "!errou":
                    //    errou($message, $write, $seuCanal);
                    //break;

            };


        //if ((strpos(strtolower($message['params']['text']), '!novobot') === 0)) {
        //    $write->ircPrivmsg($seuCanal, 'Cheguei!'); //substituir pelo nome da sua live exemplo #pokemaobr
        //}

    }
});

$client->run($connection);
