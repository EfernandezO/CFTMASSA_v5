<?php
//defina los parametros
$host="localhost";
$user="user_cftmassa";
$pass="iGpwbQ45B5g9x";
$BBDD="maesstro";

//------------------------------------------MYSQL-----------------------------------------------------------------//
	//$conexion=@mysql_connect($host,$user,$pass) or die ("No se pudo realizar la conexion con el servidor.");   
	//mysql_select_db($BBDD,$conexion) or die("No se puede seleccionar BD");
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------MYSQLI---------------------------------------------//
$conexion_mysqli = new mysqli($host, $user, $pass, $BBDD);
if (mysqli_connect_errno()) 
{
  echo"Falló la conexión mysqli<br>";
  exit();
}
//------------------------------------------------------------------------------------//	
	unset($host);
	unset($user);
	unset($pass);
	unset($BBDD);
?>
