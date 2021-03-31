<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_GET)
{
	$nombre_archivo=base64_decode($_GET["archivo"]);
	$directorio='../../CONTENEDOR_GLOBAL/trabajos_masivos/';
	$ruta_archivo=$directorio.$nombre_archivo;
	
	if(DEBUG){echo"Eliminar: ".$ruta_archivo."<br>";}
	if(file_exists($ruta_archivo))
	{
		if(DEBUG){ echo"Archivo Existe <br>";}
		else{@unlink($ruta_archivo);}
		$error=5;
	}
	else
	{
		if(DEBUG){ echo"Archivo No Existe...<br>";}
		$error=6;
	}
	if(DEBUG)
	{ echo"FIN<br>";}
	else
	{ header("location: index.php?error=$error");}
}
else
{
	header("location: index.php");
}
?>