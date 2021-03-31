<?php
	//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
if((isset($_GET["id_laborales"]))and(isset($_GET["id_funcionario"])))
{
	require("../../../../funciones/conexion_v2.php");
	$id_laborales=$_GET["id_laborales"];
	$id_funcionario=$_GET["id_funcionario"];
	$cons="DELETE FROM personal_registro_laborales WHERE id='$id_laborales' LIMIT 1";
	if(DEBUG){ echo"-->$cons<br>";}
	else{ $conexion_mysqli->query($cons);}
	$conexion_mysqli->close();
	
	$url="laborales_1.php?id_funcionario=$id_funcionario";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ echo"Sin Datos<br>";}
?>