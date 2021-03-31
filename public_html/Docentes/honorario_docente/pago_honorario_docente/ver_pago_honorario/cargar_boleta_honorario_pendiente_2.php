<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="debug";
if($_POST)
{
	$id_funcionario=$_POST["id_funcionario"];
	$id_honorario=$_POST["id_honorario"];
	$id_pago_honorario=$_POST["id_pago_honorario"];
	$continuar_1=true;
}
else
{ $continuar_1=false;}
//--------------------------------//
if($continuar_1)
{
	if($_FILES)
	{
		require("../../../../../funciones/funciones_sistema.php");
		require("../../../../../funciones/conexion_v2.php");
		
		$prefijo="BHD_".$id_funcionario."_".$id_honorario."_";
		$array_extenciones=array("pdf");
		list($archivo_cargado, $nombre_archivo_cargado)=CARGAR_ARCHIVO($_FILES["archivo"], "../../../../CONTENEDOR_GLOBAL/boleta_honorario_docente", $prefijo, $array_extenciones);
		
		if($archivo_cargado)
		{
			$cons_UP="UPDATE honorario_docente_pagos SET archivo='$nombre_archivo_cargado' WHERE id='$id_pago_honorario' AND id_funcionario='$id_funcionario' AND id_honorario='$id_honorario' LIMIT 1";
			if(DEBUG){ echo"---> $cons_UP<br>";}
			else
			{
				if($conexion_mysqli->query($cons_UP))
				{ $error="CBP0";}
				else
				{ $error="CBP2";}
			}
		}
		else
		{
			$error="CBP1";
		}
	}
	else
	{ $error="CBP3";}
	
	$url="cargar_boleta_honorario_pendiente_3.php?error=$error&id_honorario=".base64_encode($id_honorario);
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	echo"No se puede continuar<br>";
}
?>