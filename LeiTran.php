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

function test_input($data) {
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $lid = $_POST["ids"];
}

// Conexão à BD
$host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
$user="ist169720"; // -> substituir pelo nome de utilizador
$password="gfca6559"; // -> substituir pela password dada pelo mysql_reset
$dbname = $user; // a BD tem nome identico ao utilizador
$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


try {

    if(empty($lid)){
        echo("Não seleccionou leilões.");
    }else{
        $connection->beginTransaction();
        
        $N = count($lid); 
        for($i=0; $i < $N; $i++){
            $resultado = $connection->prepare("INSERT INTO concorrente (pessoa,leilao) VALUES (:nif,:lid)");
            $resultado->bindParam(":nif", $username, PDO::PARAM_INT);
            $resultado->bindParam(":lid", $lid[$i], PDO::PARAM_INT);
            $resultado->execute();
        }       
      }
    $connection->commit();
    
}catch (Exception $e) {
    echo'<section class="loginform cf">';
    echo("<p> Rollback <p>");
    echo '</section>';
    $connection->rollback();
}
 
echo'<section class="loginform cf">';
    echo("<center><p> Inscrito com sucesso em $N leilões </p>");
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
