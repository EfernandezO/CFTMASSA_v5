<?php
//defina los parametros
$host="localhost";
$user="soloLMS";
$pass="UZxMnor6yTuUW";
$BBDD="moodle";

//------------------------------------------MYSQL-----------------------------------------------------------------//
	//$conexion=@mysql_connect($host,$user,$pass) or die ("No se pudo realizar la conexion con el servidor.");   
	//mysql_select_db($BBDD,$conexion) or die("No se puede seleccionar BD");
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------MYSQLI---------------------------------------------//
$conexion_mysqliMoodle = new mysqli($host, $user, $pass, $BBDD);
if (mysqli_connect_errno()) 
{
  echo"Falló la conexión mysqli Moodle<br>";
  exit();
}
//------------------------------------------------------------------------------------//	
	unset($host);
	unset($user);
	unset($pass);
	unset($BBDD);
?>
