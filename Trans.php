<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sistema de leiloes de Recursos Marítimos</title>
	<link rel="stylesheet" href="normalize.css">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="favicon.png">
</head>
<body>

<?php 
// inicia sessão para passar variaveis entre ficheiros php
//var_dump($_SESSION);d
session_start();
//var_dump($_SESSION);

$username = $_SESSION['username']; $pin = $_SESSION['pin']; $nif = $_SESSION['nif'];

// Função para limpar os dados de entrada
function test_input($data) {
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 
 return $data;
}
// Carregamento das variáveis username e pin do form HTML através do metodo POST;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $dia = test_input($_POST["dia"]);
}
// Conexão à BD
$host="db.ist.utl.pt";
$user="ist169720";
$password="gfca6559";
$dbname = $user;

$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

/*
try{
	
	$cenas = "'$dia'";
$resultado = $connection->prepare("select * from leilao as l, leilaor as lr where l.dia = :dia AND l.nif = lr.nif AND l.nrleilaonodia = lr.nrleilaonodia AND l.dia = lr.dia");
	//dump_var($connection);
	$resultado->bindParam(":dia", $cenas);
	if($resultado->execute()){
	    $result = $resultado->fetchAll();
    }
    
}catch(PDOException $e){
	echo "dsgfdgdfgdfgdfgfbhnfg fghbfhg dflg hgh dfs";
	//echo $e->getMessage();
}*/

//$sql = "select * from leilao as l, leilaor as lr where l.dia = '2014-10-01' AND l.nif = lr.nif AND l.nrleilaonodia = lr.nrleilaonodia AND l.dia = lr.dia;" ; 

$cenas = "'$dia'";

/*$sql = "select * from leilao where dia = $cenas"; 
$result = $connection->query($sql);*/


//$resultado = $connection->prepare("select * from leilao as l, leilaor as lr where l.dia = :cenas AND l.nif = lr.nif AND l.nrleilaonodia = lr.nrleilaonodia AND l.dia = lr.dia");
//$resultado = $connection->prepare("select * from leilao where dia = :cenas");
$resultado = $connection->prepare("select * from leilao as l, leilaor as lr where l.dia = :cenas AND l.nif = lr.nif AND l.nrleilaonodia = lr.nrleilaonodia AND l.dia = lr.dia AND lr.lid NOT IN(select lid from concorrente as C, leilao as L, leilaor as R  where C.leilao = R.lid AND L.nif = R.nif AND L.nrleilaonodia = R.nrleilaonodia AND L.dia = R.dia AND C.pessoa = :nif);
");
$resultado->bindParam(":cenas", $dia, PDO::PARAM_STR);
$resultado->bindParam(":nif", $username, PDO::PARAM_INT);
$resultado->execute();
$result = $resultado->fetchALL();

if (!$result) {
 echo'<section class="loginform cf">';
 echo ('dfgfhbdfg hfghjgfhgfh ffg hfgh ');
 echo("<p> Pessoa nao registada: Erro na Query:($connection) <p>");
 echo '</section>';
exit();
}else{

echo'<section class="transtabela">';
echo ('<form name="login" action="LeiTran.php" method="post" accept-charset="utf-8">');
echo('<table class="center"> ');
echo("<tr><th>LID</th><th>NrDoDia</th><th>nome</th><th>valorbase</th><th>seleccionar</th></tr>\n");
$idleilao = 0;

foreach($result as $row){
	
	if ( $row["tipo"] == 1){
		$idleilao = $idleilao +1;
		echo("<tr><td>");
		echo($row["lid"]); echo("</td><td>");
		echo($row["nrleilaonodia"]); echo("</td><td>");
		echo($row["nome"]); echo("</td><td>");
		echo($row["valorbase"]); echo("</td><td>");
		echo('<input type="checkbox" name="ids[]" value="'); echo $row["lid"]; echo('"/></td></tr>'); 
	}
}
echo("</table>\n");
echo('<input type="submit" value="Inscrever" class="superbotao" >');
 echo '</form></section>';
echo'<section class="loginform cf">';
    echo("<center><p> Se clicou aqui por engano </p>");
}

//session_destroy();
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
