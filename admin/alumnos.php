<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');
if(!empty($_POST) && isset($_POST['action'])){
	switch($_POST['action']){
		case 'editar':
			if(isset($_POST['borrar'])){
				$ssql = "DELETE FROM Alumnos WHERE num_cuenta = {$_POST['cuenta']}";
			}else{
				$ssql = "UPDATE Alumnos SET nombre = '".utf8_decode($_POST['nombre'])."', apellido = '".utf8_decode($_POST['apellido'])."' WHERE num_cuenta = {$_POST['cuenta']}";
			}
			odbc_do($conn_access, $ssql);
			break;
		case 'agregar':
			$ssql = "select max(num_cuenta)+1 AS cuenta_nueva from Alumnos"; 
			$rs_access = odbc_exec ($conn_access, $ssql);
			$fila = odbc_fetch_object($rs_access);
			$ssql = "INSERT INTO Alumnos VALUES ({$fila->cuenta_nueva},'".utf8_decode($_POST['nombre'])."','".utf8_decode($_POST['apellido'])."')";
			odbc_do($conn_access, $ssql);
			break;
	}
}
?><div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div>
        <h1>Alumnos <a type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal" data-cuenta="" data-nombre="" data-apellido="">Agregar</a></h1>
        <hr>
        <table id="tableAlumnos" class="display" cellspacing="0" width="100%">
        <thead><tr><th>Cuenta</th><th>Nombre</th><th>Apellido</th><th width="100px">Opciones</th></tr></thead>
        <tfoot><tr><th>Cuenta</th><th>Nombre</th><th>Apellido</th><th>Opciones</th></tr></tfoot>
        <tbody>
        <?php 
			$ssql = "select * from Alumnos"; 
		if($rs_access = odbc_exec ($conn_access, $ssql)){ 
      	while ($fila = odbc_fetch_object($rs_access)){ 
         	echo "<tr><td>{$fila->num_cuenta}</td><td>".utf8_encode($fila->Nombre)."</td><td>".utf8_encode($fila->Apellido)."</td><td><a type=\"button\" class=\"btn btn-xs btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModal\" data-cuenta=\"{$fila->num_cuenta}\" data-nombre=\"".utf8_encode($fila->Nombre)."\" data-apellido=\"".utf8_encode($fila->Apellido)."\">Editar</a></td></tr>"; 
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
	
    $('#tableAlumnos').DataTable({
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
	
	$('#exampleModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var cuenta = parseInt(button.data('cuenta'));
		var nombre = button.data('nombre');
		var apellido = button.data('apellido');
		var modal = $(this);
		modal.find('.modal-title').html('Editar: <b>' + nombre + ' ' + apellido + '</b>');
		modal.find('.modal-body input#data-cuenta').val(cuenta);
		modal.find('.modal-body input#data-nombre').val(nombre);
		modal.find('.modal-body input#data-apellido').val(apellido);
		
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
            <label for="recipient-name" class="control-label">Cuenta:</label>
            <input type="text" class="form-control" readonly="readonly" name="cuenta" id="data-cuenta">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="data-nombre">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Apellido:</label>
            <input type="text" class="form-control" name="apellido" id="data-apellido">
          </div>
          <div class="alert alert-danger">
           Marque la casilla para borrar este Alumno: <input type="checkbox" name="borrar" id="data-borrar">
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