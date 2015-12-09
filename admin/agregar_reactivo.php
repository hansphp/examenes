<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');
 
$encuesta = "";
$ssql = "SELECT DISTINCT Clases.id_materia,
	Materias.Materia,
	Encuestas.encuesta_nombre,
	Encuestas.id_encuesta
	FROM Materias
	RIGHT JOIN (Encuestas RIGHT JOIN Clases ON Encuestas.[id_materia] = Clases.[id_materia])
	ON Materias.[Id_materia] = Clases.[id_materia]
	WHERE Clases.id_profesor = {$_SESSION['profe']->id_profesor} AND Clases.id_materia = {$_GET['materia']}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	$encuesta = odbc_fetch_object($rs_access);
}else{
	echo "Error al ejecutar la sentencia SQL"; 
}

if(!empty($_POST)){
	$ssql = "select max(id_pregunta)+1 AS nuevo from Preguntas"; 
	$rs_access = odbc_exec ($conn_access, $ssql);
	$reactivo = odbc_fetch_object($rs_access);
	if(isset($reactivo->nuevo) && $reactivo->nuevo > 0){
		$NUEVO = $reactivo->nuevo;
	}else{
		$NUEVO = 1;
	}
	if(isset($_POST['correcta']) && !empty($_POST['correcta'])){
	$ssql = "INSERT INTO Preguntas VALUES ({$encuesta->id_encuesta}, {$NUEVO},'".utf8_decode($_POST['pregunta'])."','".utf8_decode($_POST['op1'])."','".utf8_decode($_POST['op2'])."','".utf8_decode($_POST['op3'])."','".utf8_decode($_POST['op4'])."',{$_POST['correcta']})";
	odbc_do($conn_access, $ssql);
	}else{
		echo "<div class=\"alert alert-danger\" role=\"alert\">Error falta la opción correcta</div>";
	}
}
?>
<div class="container">
<form role="form" method="post" action="">
      <div class="row marketing">
        <div class="col-md-6">
            <div class="form-group">
                <label for="materia">Materia</label>
                <input type="text" class="form-control"  readonly id="materia" value="<?php echo utf8_encode($encuesta->Materia) ?>">
            </div>
         </div>
           <div class="col-md-6">
            <div class="form-group">
                <label for="encuesta_nombre">Nombre de la encuesta</label>
                <input type="text" class="form-control" readonly id="encuesta_nombre" value="<?php echo utf8_encode($encuesta->encuesta_nombre) ?>">
            </div>
        </div>
      </div>
      <hr>
<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="pregunta">Pregunta</label> <span class="glyphicon glyphicon-question-sign"></span>
                    <textarea class="form-control" name="pregunta"  id="pregunta"></textarea>
            </div>
      </div>
</div>
 <div class="row">
  <div class="col-md-3">&nbsp;</div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="op1">Respuestas</label>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op1"  id="op1" value="">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="1">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op2"  id="op2" value="">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="2">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op3"  id="op3" value="">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="3">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op4"  id="op4" value="">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="4">
                    </span>
                </div>
            </div>
         </div>
          <div class="col-md-3">&nbsp;</div>
        <div class="row">
        <div class="col-md-12 text-center">
        	<a href="editar_encuesta.php?materia=<?php echo $_GET['materia'] ?>&encuesta=<?php echo $_GET['encuesta'] ?>" class="btn btn-default">Regresar</a>
        	<button type="submit" class="btn btn-success">Agregar reactivo</button>
        </div>
        </div>
      </div>
      </form>      
</div> <!-- /container -->
<script>
$(document).ready(function(){
	
   var table = $('#tableAlumnos').DataTable({
		"oLanguage": {
			"sLengthMenu": "Mostrar _MENU_ filas por página",
			"sZeroRecords": "No se encontraron registros",
			"sInfo": "Mostrando _START_ de _END_ para _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 de 0 para 0 registros",
			"sInfoFiltered": "(filtrado de _MAX_ registros totales)",
			"oPaginate": {
				"sPrevious": "Anterior",
				"sNext": "Siguiente",
				"sLast": "Última"
			},
			"sSearch": "Buscar"
		}
	});	
});
</script>