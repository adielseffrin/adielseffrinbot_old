<?php
include_once 'env.php';

try{
  $conn = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass);
}catch(PDOException $e){
  die("Erro: " . $e->getMessage());
}