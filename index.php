<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
require_once './comandos.php';
require_once './debugando.php';
require_once './Twitter.class.php';

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
    global $debugando;
    global $twitter;
    global $twitter_keys;

    $write->ircJoin($seuCanal);
    $write->ircPrivmsg($seuCanal, 'Cheguei? Depende...');

    $debugando = new Debugando();
    $twitter = new Twitter($twitter_keys);

});

$client->on('irc.received', function ($message, $write, $connection, $logger) {
    global $seuCanal;
    global $debugando;
    global $twitter;

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
                case "!debug":
                    $debugando->handleCommand($message, $write, $seuCanal);
                    break;
				case "!rt":
                    $write->ircPrivmsg($seuCanal, "O QUEEE? Ainda não deu o RT? Ajuda lá que não custa nada pois esses bugs não vão se espalhar sozinhos! https://twitter.com/adielseffrin/status/".$twitter->getUltimoTweet());
                    break;
				


            };
        }
    }
});

$client->run($connection);
