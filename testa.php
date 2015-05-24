<?php 

$host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
$user="ist169720"; // -> substituir pelo nome de utilizador
$password="gfca6559"; // -> substituir pela password dada pelo mysql_reset
$dbname = $user; // a BD tem nome identico ao utilizador

$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");
// obtem o pin da tabela pessoa
$sql = "SELECT * FROM pessoa WHERE nif=" . $username; 
$result = $connection->query($sql);

?>
