<?php
require_once('config.php');

// print_r($_SESSION);
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
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/panel.css" rel="stylesheet">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
  </head>

  <body>
    <div class="container">
<h1>Lista de encuestas</h1>
<hr>
      <!-- Main component for a primary marketing message or call to action -->
<?php
$clases = array();
$ssql = "SELECT Clases.id_materia, Clases.num_cuenta, Clases.id_profesor, Materias.Materia, Alumnos.Nombre, Alumnos.Apellido
FROM Materias RIGHT JOIN (Alumnos RIGHT JOIN Clases ON Alumnos.[num_cuenta] = Clases.[num_cuenta]) ON Materias.[Id_materia] = Clases.[id_materia] WHERE  Clases.num_cuenta = {$_SESSION['alumno']->num_cuenta}";
$rs_access = odbc_exec ($conn_access, $ssql);
while($clases[] = odbc_fetch_object($rs_access));
array_pop($clases);

foreach($clases as $v){
	echo "<a href=\"examen.php?id={$v->id_materia}\">".utf8_encode($v->Materia)."</a><br>";
}

	  
			/*print_r($_SESSION['alumno']);
			echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"1\">
					   		<p>{\$reactivo->pregunta}</p>
							Opciones:<br>
							<input type=\"radio\" name=\"reactivo {$contador}\" value=1 required> {$reactivo->op1} <br>
							<input type=\"radio\" name=\"reactivo {$contador}\" value=2 required> {$reactivo->op2} <br>
							<input type=\"radio\" name=\"reactivo {$contador}\" value=3 required> {$reactivo->op3} <br>
							<input type=\"radio\" name=\"reactivo {$contador}\" value=4 required> {$reactivo->op4} <br>
						</div>";*/
      ?>

    </div> <!-- /container -->

  </body>
</html>