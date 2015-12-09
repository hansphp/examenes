<?php
require_once('config.php');

if(!empty($_POST)){
	if(isset($_POST['respuesta'])){
		$RESPUESTA = $_POST['respuesta'];
	}else{
		$RESPUESTA = -1;
	}
	$ssql = "INSERT INTO Respuestas VALUES ({$_POST['id_encuesta']},{$_POST['id_pregunta']},{$_SESSION['alumno']->num_cuenta},{$RESPUESTA})";
	odbc_do($conn_access, $ssql);
}

$ssql = "SELECT DISTINCT Clases.id_materia,
	Materias.Materia,
	Encuestas.encuesta_nombre,
	Encuestas.id_encuesta
	FROM Materias
	RIGHT JOIN (Encuestas RIGHT JOIN Clases ON Encuestas.[id_materia] = Clases.[id_materia])
	ON Materias.[Id_materia] = Clases.[id_materia]
	WHERE Clases.id_materia = {$_GET['id']}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	$encuesta = odbc_fetch_object($rs_access);
}else{ 
	echo "Error al ejecutar la sentencia SQL"; 
}

$ssql = "select COUNT(*) AS total from Respuestas WHERE id_encuesta = $encuesta->id_encuesta AND num_cuenta = {$_SESSION['alumno']->num_cuenta}"; 
			$rs_access = odbc_exec ($conn_access, $ssql);
			$fila = odbc_fetch_object($rs_access);
	
$preguntas = array();
$ssql = "SELECT id_pregunta,pregunta,opcion_1,opcion_2,opcion_3,opcion_4,respuesta_correcta FROM Preguntas WHERE id_encuesta = {$encuesta->id_encuesta}";

$ssql = "SELECT Respuestas.num_cuenta,
				Respuestas.respuesta,
				Preguntas.id_encuesta,
				Preguntas.id_pregunta,
				Preguntas.pregunta,
				Preguntas.opcion_1,
				Preguntas.opcion_2,
				Preguntas.opcion_3,
				Preguntas.opcion_4,
				Preguntas.respuesta_correcta
				FROM Preguntas LEFT JOIN Respuestas ON (Preguntas.[id_pregunta] = Respuestas.[id_pregunta]) AND (Preguntas.[id_encuesta] = Respuestas.[id_encuesta])
WHERE  Preguntas.id_encuesta = {$encuesta->id_encuesta} AND Respuestas.respuesta IS NULL ORDER BY 1";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	$pregunta = odbc_fetch_object($rs_access);
}else{ 
	echo "Error al ejecutar la sentencia SQL"; 
}
//print_r($pregunta);
//echo "[".count($pregunta)."]";
//print_r($encuesta->id_encuesta);
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
    <script>
function counter($el, n) {
    (function loop() {
       $el.html(n);
       if (n--) {
           setTimeout(loop, 1000);
       }else{
		  reactivo.submit();
	   }
    })();
}
<?php
if($fila->total < 10){
?>
$(function() {
	counter($('label'), 60);
});
<?php
}
?>
	</script>
  </head>

  <body>
    <div class="container">
<h1>Encuesta: <?php echo utf8_encode($encuesta->encuesta_nombre) ?></h1>
<h3>Materia: <?php echo utf8_encode($encuesta->Materia) ?></h3>
Tiempo restante <label style="color:#F00">0</label> segundos
<hr>
      <!-- Main component for a primary marketing message or call to action -->
      
<form method="post" name="reactivo" action="">
<?php
if($fila->total < 10){
			//print_r($_SESSION['alumno']);
			echo "<div>
					   		<p>".utf8_encode($pregunta->pregunta)."</p>
							Opciones:<br>
							<input type=\"hidden\" name=\"id_pregunta\" value=\"{$pregunta->id_pregunta}\">
							<input type=\"hidden\" name=\"id_encuesta\" value=\"{$pregunta->id_encuesta}\">
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta\" value=1 required>".utf8_encode($pregunta->opcion_1)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta\" value=2 required>".utf8_encode($pregunta->opcion_2)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta\" value=3 required>".utf8_encode($pregunta->opcion_3)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta\" value=4 required>".utf8_encode($pregunta->opcion_4)."<br>
						</div>";
}else{
	echo "<h1 style=\"color:green\">Encuesta terminada<h1>";
}
      ?>
</form>
    </div> <!-- /container -->

  </body>
</html>