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
	include("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_GET); echo"<br><br>";}
	$id_carrera=mysql_real_escape_string($_GET["id_carrera"]);
	$id_ramo=mysql_real_escape_string($_GET["id_ramo"]);
	$sede=mysql_real_escape_string($_GET["sede"]);
	$codigo=mysql_real_escape_string($_GET["codigo"]);
	
	/////////
	//borrar registro de ramo
	$cons_B="DELETE FROM mallas WHERE id_carrera='$id_carrera' AND id='$id_ramo' LIMIT 1";
	if(DEBUG){ echo"1.-$cons_B<br>";}
	else{mysql_query($cons_B)or die(mysql_error());}
	//////
	///borrando asociacion con ottros ramos
	if(($codigo>0)and($id_carrera>0))
	{
		for($i=1;$i<=5;$i++)
		{
			$cons_UP="UPDATE mallas SET pr$i='0' WHERE pr$i='$codigo' AND id_carrera='$id_carrera'";
			if(DEBUG){ echo"<strong>X$i:</strong> $cons_UP<br>";}
			else{ mysql_query($cons_UP)or die($cons_UP." ".mysql_error());}
		}
	}
	else
	{ if(DEBUG){ echo"ERROR: NO borrar asociados...<br>";}}
	mysql_close($conexion);
	
	
	$url="../ver_malla.php?id_carrera=$id_carrera&sede=$sede&error=E0";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../ver_malla.php");}
?>