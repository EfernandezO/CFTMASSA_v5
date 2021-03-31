<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_GET))
{
	$reasignado="repactado";
	$id_contrato=$_GET["id_contrato"];
	$cons_Up="UPDATE contratos2 SET reasignado='$reasignado' WHERE id='$id_contrato' LIMIT 1";
	if(DEBUG){ echo"--> $cons_Up<br>";}
	else
	{ 
		require("../../../../funciones/conexion_v2.php");
		$conexion_mysqli->query($cons_Up)or die($conexion_mysqli->error);
		@mysql_close($conexion);
		$conexion_mysqli->close();
		header("location: ../index.php?error=0&ID=$id_contrato");
	}
}