<?php
session_start();

if (!$conn_access = odbc_connect ( "BASE", "", "")){ 
   	die("Error en la conexión con la base de datos"); 
}

if(isset($_GET['salir'])){
	session_destroy();
	header("Location: index.php");
}