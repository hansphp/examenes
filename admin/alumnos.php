<?php
require_once('../config.php');

// print_r($_SESSION);
include('top.php');
?>
    <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div>
        <h1>Alumnos</h1>
        <hr>
        <table id="tableAlumnos" class="display" cellspacing="0" width="100%">
        <thead><tr><th>Cuenta</th><th>Nombre</th><th>Apellido</th><th width="100px">Opciones</th></tr></thead>
        <tfoot><tr><th>Cuenta</th><th>Nombre</th><th>Apellido</th><th>Opciones</th></tr></tfoot>
        <tbody>
        <?php 
			$ssql = "select * from Alumnos"; 
		if($rs_access = odbc_exec ($conn_access, $ssql)){ 
      	while ($fila = odbc_fetch_object($rs_access)){ 
         	echo "<tr><td>{$fila->num_cuenta}</td><td>".utf8_encode($fila->Nombre)."</td><td>".utf8_encode($fila->Apellido)."</td><td>OPT</td></tr>"; 
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
});
</script>
  </body>
</html>