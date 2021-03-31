<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
	require("../../../../funciones/conexion_v2.php");
	$error="Debug";
if($_GET)
{
	$continuar_1=true;
	$id_trabajo=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_trabajo"]));
	
	if($id_trabajo>0)
	{
		if(DEBUG){ echo"id_trabajo: $id_trabajo<br>";}
		
		//busco archivo asociado
		$path="../../../CONTENEDOR_GLOBAL/tareas_trabajos_docentes/";
		$cons_A="SELECT archivo FROM tareas_docente WHERE id='$id_trabajo' OR id_trabajo='$id_trabajo'";
		$sqli_A=$conexion_mysqli->query($cons_A) or die($conexion_mysqli->error);
		while($A=$sqli_A->fetch_assoc())
		{
			$ARCHIVO=$A["archivo"];
			$ruta_archivo_tarea=$path.$ARCHIVO;
			if(DEBUG){ echo"Eliminando Archivo: $ruta_archivo_tarea<br>";}
			else{@unlink($ruta_archivo_tarea); }
		}
		$sqli_A->free();
		///--------------------------//

		if($continuar_1)
		{
			if(DEBUG){ echo"Eliminar registro de BBDD<br>";}
			$cons_D="DELETE FROM tareas_docente WHERE id='$id_trabajo' OR id_trabajo='$id_trabajo'";
			
			if(DEBUG){echo"----> $cons_D<br>";}
			else
			{
				if($conexion_mysqli->query($cons_D))
				{
					 require("../../../../funciones/VX.php");
					 $evento="Elimina Tarea docente (trabajo) id_trabajo: $id_trabajo";
					 REGISTRA_EVENTO($evento);
					 
					 $error="ET0";
				}
				else
				{ $error="ET1";}
			}
			
		}
	}
	else
	{ $error="ET2";}
}
else{ $error="ET2";}
$conexion_mysqli->close();
$url="elimina_trabajo_2.php?error=$error";
if(DEBUG){ echo"URL: $url<br>";}
else{ header("location: $url");}
?>