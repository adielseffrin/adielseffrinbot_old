<?php
//require_once 'conn.php';


function quest($message, $write, $canal,$conn){

  

  $mesagemLower = strtolower($message['params']['text']);
  $stack = explode(" ",$mesagemLower);

  switch(count($stack)){
    case 1:
      $username = str_replace("@","",$message['user']);
      $id = buscaUser($username, $conn);
     $write->ircPrivmsg($canal, "@$username oi....$id");
    break;
    case 2:
      $username = str_replace("@","",$stack[1]);
      $write->ircPrivmsg($canal, "@$username foi {$retiradas[rand(0,count($retiradas) -1)]} {$motivos[rand(0,count($motivos) -1)]}");
    break;
    default:  
      $username = str_replace("@","",$stack[1]);
      $motivo = join(" ", array_slice($stack,2));
      $write->ircPrivmsg($canal, "@$username foi banido por $motivo");
  }
}

function buscaUser($username, $conn){
  $sql = "SELECT id FROM jogador WHERE user = :user";
  $parametros = [
    ':user' => $username
  ];

  var_dump($conn);
  $userId = $conn->query($sql);

  return $userId;

}
