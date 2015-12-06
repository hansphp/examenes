<?php
if ($conn_access = odbc_connect ( "BASE", "", "")){ 
   	echo "Conectado correctamente"; 
   	$ssql = "select * from Alumnos"; 
   	if($rs_access = odbc_exec ($conn_access, $ssql)){ 
      	echo "La sentencia se ejecutó correctamente"; 
      	while ($fila = odbc_fetch_object($rs_access)){ 
         	echo "<br>" . $fila->Nombre; 
      	}
   	}else{ 
      	echo "Error al ejecutar la sentencia SQL"; 
   	} 
} else{ 
   	echo "Error en la conexión con la base de datos"; 
}
?>