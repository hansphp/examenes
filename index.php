<?php
require_once('config.php');

if(isset($_POST['cuenta'])){	
	$ssql = "SELECT * FROM Alumnos WHERE num_cuenta LIKE '%{$_POST['cuenta']}%'";
	$error = "";

	if($rs_access = odbc_exec ($conn_access, $ssql)){ 
		$fila = odbc_fetch_object($rs_access);
		if(isset($fila->Nombre) && !empty($fila->Nombre)){
			$_SESSION['alumno'] = $fila;
			header('Location: inicio.php');
			exit;
		}else{
			$error = "Clave o usuario incorrecto.";
		}
	}else{ 
		die("Error al ejecutar la sentencia SQL"); 
	}
}
?><!DOCTYPE html>
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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
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
        <h2 class="form-signin-heading">Evaluaciones</h2>
        <label for="cuenta" class="sr-only">Número de cuenta</label>
        <input type="text" id="cuenta" name="cuenta" class="form-control" placeholder="Número de cuenta" required autofocus>
		<hr>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar evaluación</button>
      </form>
    </div> <!-- /container -->
    
  </body>
</html>
