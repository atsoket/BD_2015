<!DOCTYPE html>
<html>
<head>
	<title>Sistema de leilões de Recursos Marítimos</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="normalize.css">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="favicon.png">
</head>
<body>

<?php 
// inicia sessão para passar variaveis entre ficheiros php
session_start();


$username = $_SESSION['username']; 
$pin = $_SESSION['pin']; 
$nif = $_SESSION['nif'];

// Função para limpar os dados de entrada
function test_input($data) {
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 return $data;
}
// Carregamento das variáveis username e pin do form HTML através do metodo POST;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $lid = test_input($_POST["lid"]);
 $quantia = test_input($_POST["quantia"]);
}
// Conexão à BD
$host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
$user="ist169720"; // -> substituir pelo nome de utilizador
$password="gfca6559"; // -> substituir pela password dada pelo mysql_reset
$dbname = $user; // a BD tem nome identico ao utilizador
$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");
//regista a pessoa no leilão. Exemplificativo apenas.....
$sql = "INSERT INTO lance (pessoa,leilao,valor) VALUES ($nif,$lid,$quantia)"; 
$result = $connection->query($sql);

if (!$result) {
 echo'<section class="loginform cf">';
  $error = $connection->errorInfo();
  echo("<center><p> Lance falhado: ");
  $error = preg_replace('/(PROCEDURE ist169720.)/', '', $error[2]);
  $error = preg_replace('/( does not exist)/', '', $error);
  $error = preg_replace('/_/', ' ', $error);
  echo $error;
  echo("</p>");
}else{
    echo'<section class="loginform cf">';
    echo("<center><p> Pessoa ($username), nif ($nif) registada no leilao ($lid) com sucesso</p>");
}

session_destroy();
?>

<form name="login" action="Registo.php" method="post" accept-charset="utf-8" >

					<input type="hidden" name="username" placeholder="nif" value="<?php echo (isset($username) ? $username : ''); ?>">


					<input type="hidden" name="pin" placeholder="pin" value="<?php echo (isset($pin) ? $pin : ''); ?>"></li>

					<input type="submit" value="Voltar Para Vista Geral" class="superbotao" >
				</li>
			

		</form>
		</center></section>

</body>
</html>
