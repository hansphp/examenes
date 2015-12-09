<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');
if(!empty($_POST) && isset($_POST['action'])){
	switch($_POST['action']){
		case 'editar':
			if(isset($_POST['borrar'])){
				$ssql = "DELETE FROM Clases WHERE num_cuenta = {$_POST['cuenta']} AND id_materia = {$_POST['materia']}";
				odbc_do($conn_access, $ssql);
			}
			break;
		case 'agregar':
			$ssql = "INSERT INTO Clases VALUES ({$_POST['materia']},{$_POST['cuenta']}, {$_SESSION['profe']->id_profesor})";
			odbc_do($conn_access, $ssql);
			break;
	}
}

$materias = array();

$ssql = "SELECT Clases.id_profesor, Materias.Id_materia, Materias.Materia, Count(Clases.num_cuenta) AS total
FROM Materias LEFT JOIN Clases ON Materias.Id_materia = Clases.id_materia
WHERE Clases.id_profesor = {$_SESSION['profe']->id_profesor} OR Clases.id_profesor IS NULL 
GROUP BY Materias.Id_materia, Clases.id_profesor, Materias.Materia"; 
$rs_access = odbc_exec ($conn_access, $ssql);
while ($materias[] = odbc_fetch_object($rs_access));
array_pop($materias);


$alumnos = array();
$ssql = "select * from Alumnos"; 
$rs_access = odbc_exec ($conn_access, $ssql);
while ($alumnos[] = odbc_fetch_object($rs_access));
array_pop($alumnos);

?>
<style>
tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>
<div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div>
        <h1>Clases <a type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal" data-cuenta="" data-nombre="" data-apellido="">Agregar</a></h1>
        <hr>
        <table id="tableAlumnos" class="display" cellspacing="0" width="100%">
        <thead><tr><th>Cuenta</th><th>Materia</th><th>Nombre</th><th>Apellido</th><th>Evaluado</th><th width="100px">Opciones</th></tr></thead>
        <tfoot><tr><th>Cuenta</th><th>Materia</th><th>Nombre</th><th>Apellido</th><th>Evaluado</th><th>Opciones</th></tr></tfoot>
        <tbody>
        <?php 
			$ssql = "SELECT Clases.id_materia,
							Clases.num_cuenta,
							Clases.id_profesor,
							Materias.Materia,
							Alumnos.Nombre,
							Alumnos.Apellido,
COUNT(*) AS total
							FROM Respuestas RIGHT JOIN
							(Encuestas RIGHT JOIN (Materias
							RIGHT JOIN (Alumnos RIGHT JOIN Clases ON Alumnos.[num_cuenta] = Clases.[num_cuenta])
							ON Materias.[Id_materia] = Clases.[id_materia])
							ON Encuestas.[id_materia] = Clases.[id_materia])
							ON (Respuestas.id_encuesta = Encuestas.id_encuesta)
							WHERE Clases.id_profesor = {$_SESSION['profe']->id_profesor}
GROUP BY  Clases.id_materia,
							Clases.num_cuenta,
							Clases.id_profesor,
							Materias.Materia,
							Alumnos.Nombre,
							Alumnos.Apellido"; 
							// Respuestas.[num_cuenta] = Clases.[num_cuenta] AND Respuestas.id_encuesta = Encuestas.id_encuesta
		if($rs_access = odbc_exec ($conn_access, $ssql)){ 
      	while ($fila = odbc_fetch_object($rs_access)){
         	echo "<tr><td>{$fila->num_cuenta}</td><td>".utf8_encode($fila->Materia)."</td><td>".utf8_encode($fila->Nombre)."</td><td>".utf8_encode($fila->Apellido)."</td><td>";
			if($fila->total > 1){
				echo 'Si';
			}else{
				echo 'No';
			}
			echo "</td><td><a type=\"button\" class=\"btn btn-xs btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModal\" data-cuenta=\"{$fila->num_cuenta}\" data-materia=\"{$fila->id_materia}\">Editar</a> ";
			if($fila->total > 1){
				echo "<a type=\"button\" href=\"evaluacion.php?materia={$fila->id_materia}&cuenta={$fila->num_cuenta}\" class=\"btn btn-xs btn-default\">ver evaluación</a>";
			}else{
				echo '&nbsp;';
			}
			echo"</td></tr>"; 
      	}
   	}else{ 
      	echo "Error al ejecutar la sentencia SQL"; 
   	}
		?>
        </tbody></table>
        <!--        
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
        -->
      </div>

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
		},
		"columnDefs": [
            { "visible": false, "targets": 1 }
        ],
        "order": [[ 1, 'asc' ]],
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
	});
	
	$('#tableAlumnos').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === 1 && currentOrder[1] === 'asc' ) {
            table.order( [ 1, 'desc' ] ).draw();
        }
        else {
            table.order( [ 1, 'asc' ] ).draw();
        }
    } );
	
	$('#exampleModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var materia = parseInt(button.data('materia'));
		var cuenta = parseInt(button.data('cuenta'));
		var modal = $(this);
	
		modal.find('.modal-title').html('Editar registro <b></b>');
		modal.find('.modal-body select#data-materia').val(materia);
		modal.find('.modal-body select#data-cuenta').val(cuenta);
		if(cuenta > 0){
			modal.find('.modal-body div.alert').show();
			modal.find('.modal-body input#data-action').val('editar');
		}else{
			modal.find('.modal-body div.alert').hide();
			modal.find('.modal-body input#data-action').val('agregar');
		}
	});
	
});

</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
  <form method="post" action="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label>Alumno:</label>
            <select class="form-control" name="cuenta" id="data-cuenta">
            <?php
			foreach($alumnos as $v){
				echo "<option value=\"{$v->num_cuenta}\">[$v->num_cuenta] - ".utf8_encode($v->Nombre)." ".utf8_encode($v->Apellido)."</option>";
			}
			?>
            </select>
            </div>
            <div class="form-group">
            <label>Grupo:</label>
            <select class="form-control" name="materia" id="data-materia">
            <?php
			foreach($materias as $v){
				echo "<option value=\"{$v->Id_materia}\">".utf8_encode($v->Materia)." [{$v->total}]</option>";
			}
			?>
            </select>
            </div>
           <div class="alert alert-danger">
           Marque la casilla para borrar este alumno del grupo: <input type="checkbox" name="borrar" id="data-borrar">
          </div>
          <input type="hidden" class="form-control" name="action" id="data-action">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <input type="submit" class="btn btn-primary" value="Guardar" />
      </div>
    </div>
    </form>
  </div>
</div>