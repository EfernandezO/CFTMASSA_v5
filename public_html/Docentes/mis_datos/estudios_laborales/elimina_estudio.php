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
if((isset($_GET["id_estudio"]))and(isset($_GET["id_funcionario"])))
{
	require("../../../../funciones/conexion_v2.php");
	$id_estudio=$_GET["id_estudio"];
	$id_funcionario=$_GET["id_funcionario"];
	$cons="DELETE FROM personal_registro_estudios WHERE id='$id_estudio' LIMIT 1";
	if(DEBUG){ echo"-->$cons<br>";}
	else{ $conexion_mysqli->query($cons);}
	$conexion_mysqli->close();
	
	$url="estudio_1.php?id_funcionario=$id_funcionario";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ echo"Sin Datos<br>";}
?>