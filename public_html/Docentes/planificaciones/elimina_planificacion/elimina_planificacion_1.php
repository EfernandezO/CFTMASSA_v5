<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_dump($_GET);}
	
	$id_planificacion=mysqli_real_escape_string($conexion_mysqli, $_GET["id_planificacion"]);
	$id_planificacionMain=mysqli_real_escape_string($conexion_mysqli, $_GET["id_planificacionMain"]);
	
	if((is_numeric($id_planificacion))and($id_planificacion>0))
	{
		$cons_D="DELETE FROM planificaciones WHERE id_planificacion='$id_planificacion' AND idPlanificacionMain='$id_planificacionMain'LIMIT 1";
		if(DEBUG){ echo"---> $cons_D<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_D); 
			$error="PE0";
			require("../../../../funciones/VX.php");
			$evento="Elimina Registro Planificacion id_planificacion: $id_planificacion id_planificacionMain:$id_planificacionMain";
			REGISTRA_EVENTO($evento);
		}
	}
	
	$conexion_mysqli->close();
	
	$url="../ver_planificaciones.php?id_planificacionMain=".base64_encode($id_planificacionMain)."&error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}