<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');
?>
<div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div>
        <h1>Evaluación</h1>
        <hr>
       
        <?php 
$ssql = "SELECT DISTINCT Clases.id_materia,
	Materias.Materia,
	Encuestas.encuesta_nombre,
	Encuestas.id_encuesta
	FROM Materias
	RIGHT JOIN (Encuestas RIGHT JOIN Clases ON Encuestas.[id_materia] = Clases.[id_materia])
	ON Materias.[Id_materia] = Clases.[id_materia]
	WHERE Clases.id_materia = {$_GET['materia']} AND Clases.num_cuenta = {$_GET['cuenta']}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	$encuesta = odbc_fetch_object($rs_access);
}else{ 
	echo "Error al ejecutar la sentencia SQL"; 
}

$ssql = "SELECT Respuestas.num_cuenta, Respuestas.respuesta, Preguntas.id_encuesta, Preguntas.id_pregunta, Preguntas.pregunta, Preguntas.opcion_1, Preguntas.opcion_2, Preguntas.opcion_3, Preguntas.opcion_4, Preguntas.respuesta_correcta
FROM Preguntas LEFT JOIN Respuestas ON (Preguntas.[id_pregunta] = Respuestas.[id_pregunta]) AND (Preguntas.[id_encuesta] = Respuestas.[id_encuesta])
WHERE  Preguntas.id_encuesta = {$encuesta->id_encuesta} AND Respuestas.respuesta IS NOT NULL AND Respuestas.num_cuenta = {$_GET['cuenta']}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	while($preguntas[] = odbc_fetch_object($rs_access));
	array_pop($preguntas);
}else{ 
	echo "Error al ejecutar la sentencia SQL"; 
}
if(empty($preguntas)){
	echo "No disponible";
}else{
$i=0;
$calificacion = 0;
foreach($preguntas as $pregunta){
	$i++;
	
echo "<hr><div>
					   		<p>".utf8_encode($pregunta->pregunta)." <b>$i</b></p>
							Opciones:<br>
							<input type=\"hidden\" name=\"id_pregunta\" value=\"{$pregunta->id_pregunta}\">
							<input type=\"hidden\" name=\"id_encuesta\" value=\"{$pregunta->id_encuesta}\">
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta_{$pregunta->id_pregunta}\" ".(($pregunta->respuesta==1)?'checked':'')." value=1 required>".utf8_encode($pregunta->opcion_1)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta_{$pregunta->id_pregunta}\" ".(($pregunta->respuesta==2)?'checked':'')."value=2 required>".utf8_encode($pregunta->opcion_2)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta_{$pregunta->id_pregunta}\" ".(($pregunta->respuesta==3)?'checked':'')."value=3 required>".utf8_encode($pregunta->opcion_3)."<br>
							<input type=\"radio\" onClick=\"reactivo.submit();\" name=\"respuesta_{$pregunta->id_pregunta}\"".(($pregunta->respuesta==4)?'checked':'')." value=4 required>".utf8_encode($pregunta->opcion_4)."<br>
						</div>";
						if($pregunta->respuesta == $pregunta->respuesta_correcta){
		echo "<b>Correcto</b>";
		$calificacion++;
	}else{
		echo "<b>Incorrecto</b>";
	}

}

echo "<h1>Calificación final : $calificacion</h1>";
}
		?>
      </div>

    </div> <!-- /container -->
