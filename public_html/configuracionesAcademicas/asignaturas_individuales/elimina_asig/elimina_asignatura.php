<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	include("../../../../funciones/conexion.php");
	if(DEBUG){ var_export($_GET); echo"<br>";}
	$id_carrera=mysql_real_escape_string($_GET["id_carrera"]);
	$id_ramo=mysql_real_escape_string($_GET["id_ramo"]);
	$sede=mysql_real_escape_string($_GET["sede"]);
	
	if(is_numeric($id_ramo))
	{
		$cons_DEL="DELETE FROM asignatura WHERE id='$id_ramo' AND sede='$sede' LIMIT 1";
		if(DEBUG){ echo"$cons_DEL<br>";}
		else
		{mysql_query($cons_DEL)or die(mysql_error());}
		$error="AI6";
	}
	else{ $error="AI7";}
	$url="../lista_asignaturas_individuales.php?error=$error&id_carrera=$id_carrera&sede=$sede";
	mysql_close($conexion);
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{}
?>