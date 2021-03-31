<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_SESSION["SELECTOR_ALUMNO"]))
{ unset($_SESSION["SELECTOR_ALUMNO"]);}

if(isset($_SESSION["ULTIMO_ALUMNO"]))
{ unset($_SESSION["ULTIMO_ALUMNO"]);}

$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"matricula":
		$url="../Alumnos/menualumnos.php";
		break;
	default:
		$url="../Alumnos/menualumnos.php";
}
header("location: $url");
?>