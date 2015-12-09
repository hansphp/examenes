<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');

if(!empty($_POST)){
	 $ssql = "UPDATE Preguntas SET pregunta = '".utf8_decode($_POST['pregunta'])."', opcion_1 = '".utf8_decode($_POST['op1'])."', opcion_2 = '".utf8_decode($_POST['op2'])."', opcion_3 = '".utf8_decode($_POST['op3'])."', opcion_4 = '".utf8_decode($_POST['op4'])."', respuesta_correcta = {$_POST['correcta']} WHERE id_pregunta = {$_GET['id']}";
	odbc_do($conn_access, $ssql);
	header("Location: editar_encuesta.php?materia={$_GET['materia']}&encuesta={$_GET['encuesta']}");
}

$encuesta = "";
$ssql = "SELECT id_encuesta, id_pregunta, pregunta, opcion_1, opcion_2, opcion_3, opcion_4, respuesta_correcta FROM Preguntas WHERE id_pregunta = {$_GET['id']}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	$encuesta = odbc_fetch_object($rs_access);
}else{
	echo "Error al ejecutar la sentencia SQL"; 
}
?>
<script>
	$(document).ready(function() {
		$( 'input[type="radio"]' ).each(function( index ) {
			if($(this).val() == <?php echo $encuesta->respuesta_correcta ?>){
				$(this).prop("checked", true);
			}
		});
	});
</script>
<div class="container">
<form role="form" method="post" action="">
<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="pregunta">Pregunta</label> <span class="glyphicon glyphicon-question-sign"></span>
                    <textarea class="form-control" name="pregunta"  id="pregunta"><?php echo utf8_encode($encuesta->pregunta) ?></textarea>
            </div>
      </div>
</div>
 <div class="row">
  <div class="col-md-3">&nbsp;</div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="op1">Respuestas</label>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op1"  id="op1" value="<?php echo utf8_encode($encuesta->opcion_1) ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="1">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op2"  id="op2" value="<?php echo utf8_encode($encuesta->opcion_2) ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="2">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op3"  id="op3" value="<?php echo utf8_encode($encuesta->opcion_3) ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </span>
                    <span class="input-group-addon">
                       <input type="radio" name="correcta"  id="correcta" value="3">
                    </span>
                </div>
                <div class='input-group'>
                    <input type="text" class="form-control" name="op4"  id="op4" value="<?php echo utf8_encode($encuesta->opcion_4) ?>">
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
        	<a href="editar_encuesta.php?materia=<?php echo $_GET['materia'] ?>&encuesta=<?php echo $_GET['encuesta'] ?>" class="btn btn-default">Cancelar</a>
        	<button type="submit" class="btn btn-success">Guardar</button>
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