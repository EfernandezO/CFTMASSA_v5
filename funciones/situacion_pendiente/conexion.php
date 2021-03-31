<?php
//defina los parametros
$host="localhost";
$user="consultor";
$pass="estaclave321";
$BBDD="maesstro";

	$conexion=@mysql_connect($host,$user,$pass) or die ("No se pudo realizar la conexion con el servidor.");   
	@mysql_select_db($BBDD,$conexion) or die("No se puede seleccionar BD");
?>