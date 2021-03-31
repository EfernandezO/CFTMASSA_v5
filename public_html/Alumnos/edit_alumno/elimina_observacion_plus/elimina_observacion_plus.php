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
if(($_GET)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	include("../../../../funciones/conexion.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$autor=$_GET["autor"];
	$id_observacion=$_GET["id_observacion"];
	
	
	if($id_usuario_activo==$autor)
	{
		$cons_D="DELETE FROM hoja_vida WHERE id='$id_observacion' AND id_alumno='$id_alumno' LIMIT 1";
		if(DEBUG){	echo"--->$cons_D<br>";}
		else
		{	mysql_query($cons_D)or die(mysql_error());}
	}
	
	if(!DEBUG){ header("location: ../buscaalumno2_tab.php");}
}
else
{ header("location: ../../../buscador_alumno_BETA/HALL/index.php");}
?>