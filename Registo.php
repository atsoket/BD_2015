<!DOCTYPE html>
<html>
<head>
	<title>Sistema de leilões de Recursos Marítimos</title>
	<meta charset="utf-16">
        <link rel="stylesheet" href="normalize.css">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" href="favicon.png">
</head>
<body>
<div id="wrap">
<?php 
// inicia sessão para passar variaveis entre ficheiros php
session_start();

// Função para limpar os dados de entrada
function test_input($data) {
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 
 return $data;
}



// Carregamento das variáveis username e pin do form HTML através do metodo POST;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $username = test_input($_POST["username"]);
 $pin = test_input($_POST["pin"]);
}
 
$host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
$user="ist169720"; // -> substituir pelo nome de utilizador
$password="gfca6559"; // -> substituir pela password dada pelo mysql_reset
$dbname = $user; // a BD tem nome identico ao utilizador

$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, 
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


try{
	$resultado = $connection->prepare('SELECT * FROM pessoa WHERE nif = :nif');
	$resultado->bindParam(":nif", $username, PDO::PARAM_INT);
	if($resultado->execute()){
	    $result = $resultado->fetchAll();
	}else{
	     echo'<section class="loginform cf">';
	     echo("<p> Erro na Query:($sql)<p>");
	     echo '</section>';
	     exit();
	}	    
}catch(PDOException $e){
	echo $e->getMessage();
}

if (!$result) {
	 echo'<section class="loginform cf">';
	 echo("<p> Erro na Query:($sql)<p>");
	 echo '</section>';
	 exit();
}
foreach($result as $row){
$safepin = $row["pin"];
$nif = $row["nif"];
}
if ($safepin != $pin ) {
 echo'<section class="loginform cf">';
    echo "<h1>Pin Invalido!</h1>\n";
$connection = null;
echo '</section>';
 exit;
}
// passa variaveis para a sessao;
$_SESSION['username'] = $_POST["username"];
$_SESSION['pin'] = $_POST["pin"];
$_SESSION['nif'] = $_POST["username"]; 
// Apresenta os leilões
$sql = "SELECT * FROM leilao"; 
$result = $connection->query($sql);
//echo("<table border=\"1\">\n");
echo('<div id="left">');
echo('<table class="center"> ');

echo("<tr><th>ID</th><th>nif</th><th>diahora</th><th>NrDoDia</th><th>nome</th><th>tipo</th><th>valorbase</th></tr>\n");
$idleilao = 0;foreach($result as $row){
	if ( $row["tipo"] == 1){
		$idleilao = $idleilao +1;
		echo("<tr><td>");
		echo($idleilao); echo("</td><td>");
		echo($row["nif"]); echo("</td><td>");
		echo($row["dia"]); echo("</td><td>");
		echo($row["nrleilaonodia"]); echo("</td><td>");
		echo($row["nome"]); echo("</td><td>");
		echo($row["tipo"]); echo("</td><td>");
		echo($row["valorbase"]); echo("</td></tr>");
		$leilao[$idleilao]= array($row["nif"],$row["diahora"],$row["nrleilaonodia"]);
	}
}
echo("</table>\n");
echo("</div>");
$sql = "select distinct dia from leilao;";
$result = $connection->query($sql);

echo('
   <div id="right">
    <section class="logform cf">
        <form action="Leilao.php" method="post">
        <center><ul>
        <h2>Escolha o ID do leilão a que pretende concorrer</h2>
        <li>ID: <input type="number" name="lid" width: 149px; min="1" max="'); echo('"/>
        <input type="submit" /></center>
        </li>
        </ul>
        </form>
    </section>
    <section class="logform cf">
        <form action="Trans.php" method="post">
        <center><ul>
        <h2>Inscrição em múltiplos leilões do mesmo dia</h2>
        <li>Dia: '); 
        echo "<select name='dia'>"; 
            foreach($result as $row){
                echo ('<option value = "');echo($row["dia"]); echo('">'); echo($row["dia"]); echo("</option>");
                //echo ('<option value ="'); echo($row["leilao"]); echo ('">');
            }
        echo "</select>";
        echo('
        <input type="submit" /></center>
        </li>
        </ul>
        </form>
    </section>
    </div>');

    echo('</div>');

$resultado = $connection->prepare('select * from concorrente as C, leilao as L, leilaor as R  where C.leilao = R.lid AND L.nif = R.nif AND L.nrleilaonodia = R.nrleilaonodia AND L.dia = R.dia AND C.pessoa = :nif');
$resultado->bindParam(":nif", $username);
$resultado->execute();
$result = $resultado->fetchAll();

//$sql = "select * from concorrente as C, leilao as L, leilaor as R  where C.leilao = R.lid AND L.nif = R.nif AND L.nrleilaonodia = R.nrleilaonodia AND L.dia = R.dia AND C.pessoa = $username;";
//$result  = $connection->query($sql);
$num_rows = $resultado->rowCount();
echo $num_rows;
if( $num_rows > 0){
echo('<div id="wrap">');
echo('<div id="left">');
echo('<table class="center" style="width:100%"> ');
// echo("<tr><th>LID</th><th>dia</th><th>nrL</th><th>nif</th><th>nome</th><th>nrdias</th><th>valorbase</th></tr>\n");
echo("<tr><th>LID</th><th>nome</th><th>valorbase</th><th>lance</th></tr>\n");
foreach($result as $row){

		//$idleilao = $idleilao +1;
		echo("<tr><td>");
		echo($row["lid"]); echo("</td><td>");
		$idleilao = $row["lid"];
		/*echo($row["dia"]); echo("</td><td>");
		echo($row["nrleilaonodia"]); echo("</td><td>");
		echo($row["nif"]); echo("</td><td>");*/
		echo($row["nome"]); echo("</td><td>");
		//echo($row["nrdias"]); echo("</td><td>");
		echo($row["valorbase"]); echo("</td><td>");
		//$leilao[$idleilao]= array($row["nif"],$row["diahora"],$row["nrleilaonodia"]);
		$sql = "select MAX(valor) from lance as Lc where Lc.leilao = $idleilao;";
		$resultLance  = $connection->query($sql);
		if( $resultLance->rowCount() == 1){
		    $row2 = $resultLance->fetch();
		    //echo($row2); echo("</td></tr>");
		    if( $row2[0] != NULL){
		        echo($row2[0]);echo("</td></tr>");
		    }else{
		        echo("0</td></tr>");
		    }
	    }else{
	        echo(0); echo("</td></tr>");
	    }
}
echo("</table>\n");
echo("</div>");

$resultado = $connection->prepare('SELECT leilao FROM concorrente WHERE pessoa = :nif');
$resultado->bindParam(":nif", $username);
$resultado->execute();
$result = $resultado->fetchAll();

//$sql = "SELECT leilao FROM concorrente WHERE pessoa = $username";
//$result = $connection->query($sql);

    
    echo('
   <div id="right">
    <section class="logform cf">
        <form action="Lance.php" method="post">
        <ul>
        <center><h2>Escolha o ID do leilão e valor que pretende Licitar</h2>
        <li>ID:');
        

        

        echo "<select name='lid'>"; 
            foreach($result as $row){
                echo ('<option value = "');echo($row["leilao"]); echo('">'); echo($row["leilao"]); echo("</option>");
                //echo ('<option value ="'); echo($row["leilao"]); echo ('">');
            }
        echo "</select>";

        echo('    Valor: <input type="number" name="quantia" placeholder="Valor" required />
        <input type="submit" /></center>
        </li>
        </ul>
        </form>
    </section>
    </div>');
    echo('</div>');}
 


echo("</div>");

?>
</body>
</html>
