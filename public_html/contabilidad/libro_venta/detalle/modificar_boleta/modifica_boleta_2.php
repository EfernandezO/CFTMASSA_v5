<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("modifica_boleta_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="debug";
if($_POST)
{
	require("../../../../../funciones/conexion_v2.php");
	if(DEBUG){var_dump($_POST);}
	$folio_boleta=mysqli_real_escape_string($conexion_mysqli, $_POST["folio"]);
	$id_boleta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_boleta"]);
	
	$cons_UP="UPDATE boleta SET folio='$folio_boleta' WHERE id='$id_boleta' LIMIT 1";
	if(DEBUG){ echo"--> $cons_UP<br>";}
	else
	{
		if($conexion_mysqli->query($cons_UP))
		{
			$error="MB0";
			//----------------------------------------------//
			include("../../../../../funciones/VX.php");
			$evento="Modifica Folio de Boleta id_boleta: $id_boleta nuevo folio: $folio_boleta";
			REGISTRA_EVENTO($error);
			//------------------------------------------------//
		}
		else
		{$error="MB1";}
	}
	$url="modifica_boleta_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else{ header("location: ../index.php");}
?>