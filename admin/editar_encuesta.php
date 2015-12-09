<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');

if(!empty($_POST) && isset($_POST['encuesta_nombre'])){
	$ssql = "UPDATE Encuestas SET encuesta_nombre = '".utf8_decode($_POST['encuesta_nombre'])."' WHERE id_encuesta = {$_GET['encuesta']}";
	odbc_do($conn_access, $ssql);
}

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

if(empty($encuesta->id_encuesta)){
			$ssql = "select max(id_encuesta)+1 AS nueva from Encuestas"; 
			$rs_access = odbc_exec ($conn_access, $ssql);
			$enc = odbc_fetch_object($rs_access);
			$ssql = "INSERT INTO Encuestas VALUES ({$enc->nueva},$encuesta->id_materia,'')";
			odbc_do($conn_access, $ssql);
}

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

if(isset($_GET['id'])){
	$ssql = "DELETE FROM Preguntas WHERE id_pregunta = {$_GET['id']}";
	odbc_do($conn_access, $ssql);
}
//print_r($encuesta);

$preguntas = array();
$ssql = "SELECT id_pregunta,pregunta,opcion_1,opcion_2,opcion_3,opcion_4,respuesta_correcta FROM Preguntas WHERE id_encuesta = {$encuesta->id_encuesta}";
if($rs_access = odbc_exec ($conn_access, $ssql)){ 
	while($preguntas[] = odbc_fetch_object($rs_access));
	array_pop($preguntas);
}else{ 
	echo "Error al ejecutar la sentencia SQL"; 
}
//print_r($preguntas);
?>
<div class="container">
<form action="editar_encuesta.php?materia=<?php echo $_GET['materia'] ?>&encuesta=<?php echo $encuesta->id_encuesta ?>" method="post">
      <!-- Main component for a primary marketing message or call to action -->
      <div>
        <h1>Encuesta</h1>
        <hr>
        <div class="row marketing">
        <div class="col-md-6">
            <div class="form-group">
                <label for="materia">Materia</label>
                <div class='input-group'>
                    <input type="text" readonly class="form-control" id="materia" name="materia" value="<?php echo utf8_encode($encuesta->Materia) ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-file"></span>
                    </span>
                </div>
            </div>
       </div>
       <div class="col-md-6">
            <div class="form-group">
                <label for="encuesta_nombre">Nombre de la Encuesta</label>
                <input type="text" class="form-control" name="encuesta_nombre" data-minlength="4" id="encuesta_nombre" value="<?php echo utf8_encode($encuesta->encuesta_nombre) ?>" required>
                <p class="help-block">
                <b>El nombre de la encuesta es requerido.</b>
                </p>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
        	<p>
            	<a href="encuestas.php" class="btn btn-default">Cancelar</a>
        		<button type="submit" class="btn btn-success">Guardar encuesta</button>
            </p>
        </div>
        </div>
      <hr>
      <div class='row'>
      	<div class='col-md-3'>
        <a href="agregar_reactivo.php?materia=<?php echo $_GET['materia'] ?>&encuesta=<?php echo $encuesta->id_encuesta ?>" class="btn btn-sm btn-success">Agregar reactivo</a>
        </div>
      </div>
        <h3>Reactivos de la encuesta</h3>
        <table id="tableAlumnos" class="display" cellspacing="0" width="100%">
        <thead>
                    <tr>
                      <th>Pregunta</th>
                      <th>Opción 1</th>
                      <th>Opción 2</th>
                      <th>Opción 3</th>
                      <th>Opción 4</th>
                      <th width="10%">Opciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				  if(!empty($preguntas))
                  foreach($preguntas as $v){
                        echo "<tr>";
						echo "<td>".utf8_encode($v->pregunta)."</td>";
						echo "<td ".(($v->respuesta_correcta == 1)?"class=\"alert alert-success\"":"").">".utf8_encode($v->opcion_1)."</td>";
						echo "<td ".(($v->respuesta_correcta == 2)?"class=\"alert alert-success\"":"").">".utf8_encode($v->opcion_2)."</td>";
						echo "<td ".(($v->respuesta_correcta == 3)?"class=\"alert alert-success\"":"").">".utf8_encode($v->opcion_3)."</td>";
						echo "<td ".(($v->respuesta_correcta == 4)?"class=\"alert alert-success\"":"").">".utf8_encode($v->opcion_4)."</td>";						
                       echo "<td>";
					   echo "<a href=\"editar_encuesta.php?materia={$_GET['materia']}&encuesta={$_GET['encuesta']}&id={$v->id_pregunta}\" class=\"btn btn-xs btn-danger\">Borrar</a> ";
                       echo "<a href=\"editar_reactivo.php?materia={$_GET['materia']}&encuesta={$_GET['encuesta']}&id={$v->id_pregunta}\" class=\"btn btn-xs btn-primary\">Editar</a>";
                      echo "</td>";
                    echo "</tr>"; 
                  }
                  ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Pregunta</th>
                      <th>Opción 1</th>
                      <th>Opción 2</th>
                      <th>Opción 3</th>
                      <th>Opción 4</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
        </table>
        <!--        
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
        -->
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