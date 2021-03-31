<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_proveedores_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(isset($_GET["id_proveedor"]))
	{ 
		$id_proveedor=$_GET["id_proveedor"];
		if(is_numeric($id_proveedor))
		{ $continuar_1=true;}
		else
		{ $continuar_1=false;}
	}
	else
	{ $continuar_1=false;}
}
else
{ $continuar_1=false;}
//------------------------------------------//
if($continuar_1)
{
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/VX.php");
	$evento="Elimina Proveedor id_proveedor: $id_proveedor";
	REGISTRA_EVENTO($evento);
	
		$cons_D="DELETE FROM proveedores WHERE id_proveedor='$id_proveedor' LIMIT 1";
		if(DEBUG){ echo"---> $cons_D<br>";}
		else{ $conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);}
	$conexion_mysqli->close();	
	
	$url="../listar_proveedores.php?error=PG3";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}