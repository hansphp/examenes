<?php
require_once('../config.php');

if(isset($_POST['nombre'])){	
	$ssql = "SELECT * FROM Profesores WHERE Nombre LIKE '%{$_POST['nombre']}%'";
	$error = "";
	
	if($rs_access = odbc_exec ($conn_access, $ssql)){ 
		$fila = odbc_fetch_object($rs_access);
		if(isset($fila->Clave) && $fila->Clave == $_POST['clave']){
			$_SESSION['profe'] = $fila;
			header('Location: inicio.php');
			exit;
		}else{
			$error = "Clave o usuario incorrecto.";
		}
	}else{ 
		die("Error al ejecutar la sentencia SQL"); 
	}
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Sistema de evaluaciones">
    <meta name="author" content="Hans Von Herrera Ortega">

    <title>Sistema de evaluaciones</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">
  </head>
  <body>

    <div class="container">
    <?php
	if(!empty($error)){
	?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
      </div>
    <?php
	}
	?>
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Panel del profesor</h2>
        <label for="inputName" class="sr-only">Nombre</label>
        <input type="text" id="inputName" name="nombre" class="form-control" placeholder="Nombre" required autofocus>
        <label for="inputKey" class="sr-only">Clave</label>
        <input type="password" id="inputKey" name="clave" class="form-control" placeholder="Clave" required>
        <hr>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      </form>
    </div> <!-- /container -->

  </body>
</html>
