<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Modificacion_datos_de_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//
if(($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	include("../../../../funciones/conexion.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$tipo_visualizacion="privada";
	$fecha_actual=date("Y-m-d");
	$observacion=mysql_real_escape_string($_POST["observacion"]);
	//$observacion=ucwords(strtolower($observacion));
	$cons_O_in="INSERT INTO hoja_vida (id_alumno, observacion, fecha, id_user, tipo_visualizacion) VALUES('$id_alumno', '$observacion', '$fecha_actual', '$id_usuario_activo', '$tipo_visualizacion')";
	if(DEBUG){ echo"---> $cons_O_in<br>";}
	else
	{ mysql_query($cons_O_in)or die(mysql_error());}
	mysql_close($conexion);
	
	if(!DEBUG){ header("location: ../buscaalumno2_tab.php");}
}
else
{ header("location: ../../../buscador_alumno_BETA/HALL/index.php");}
?>