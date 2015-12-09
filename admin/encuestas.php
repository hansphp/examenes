<?php
require_once('../config.php');
// print_r($_SESSION);
include('top.php');
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
        <h1>Encuestas</h1>
        <hr>
        <table id="tableAlumnos" class="display" cellspacing="0" width="100%">
        <thead><tr><th>Cuenta</th><th>Materia</th><th width="100px">Opciones</th></tr></thead>
        <tfoot><tr><th>Cuenta</th><th>Materia</th><th>Opciones</th></tr></tfoot>
        <tbody>
        <?php 
			$ssql = "SELECT DISTINCT Clases.id_materia,
				Materias.Materia,
				Encuestas.encuesta_nombre,
				Encuestas.id_encuesta
				FROM Materias
				RIGHT JOIN (Encuestas RIGHT JOIN Clases ON Encuestas.[id_materia] = Clases.[id_materia])
				ON Materias.[Id_materia] = Clases.[id_materia]
				WHERE Clases.id_profesor = {$_SESSION['profe']->id_profesor}";
		if($rs_access = odbc_exec ($conn_access, $ssql)){ 
      	while ($fila = odbc_fetch_object($rs_access)){
         	echo "<tr><td>".utf8_encode($fila->encuesta_nombre)."</td><td>".utf8_encode($fila->Materia)."</td><td><a type=\"button\" class=\"btn btn-xs btn-primary\" href=\"editar_encuesta.php?materia={$fila->id_materia}&encuesta={$fila->id_encuesta}\">Editar</a></td></tr>"; 
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
                        '<tr class="group"><td colspan="4">'+group+'</td></tr>'
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
});
</script>