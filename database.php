<?php
$host = "db";
$database = "cinemapp";
$puerto = "3306";
$usuario = "root";
$password = "root";

try{
  $conn = new PDO("mysql:host=$host;port=$puerto;dbname=$database", $usuario, $password);
}catch(PDOexception $e){
  die("PDO connection error: " . $e-> getMessage());
}
?>
