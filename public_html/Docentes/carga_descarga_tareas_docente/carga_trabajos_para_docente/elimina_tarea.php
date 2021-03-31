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
	$error="debug";
if($_GET)
{
	$continuar_1=true;
	$id_tarea=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_tarea"]));
	
	if($id_tarea>0)
	{
		if(DEBUG){ echo"id_tarea: $id_tarea<br>";}
		
		//busco archivo asociado
		$cons_A="SELECT archivo FROM tareas_docente WHERE id='$id_tarea' LIMIT 1";
		$sqli_A=$conexion_mysqli->query($cons_A) or die($conexion_mysqli->error);
		$A=$sqli_A->fetch_assoc();
		$ARCHIVO=$A["archivo"];
		$sqli_A->free();
		///--------------------------//
		$path="../../../CONTENEDOR_GLOBAL/tareas_trabajos_docentes/";
		$ruta_archivo_tarea=$path.$ARCHIVO;
		
		if(DEBUG){ echo"RUTA: $ruta_archivo_tarea<br>";}
		
		if(DEBUG){ echo"Eliminando Archivo: $ruta_archivo_tarea<br>";}
		else{@unlink($ruta_archivo_tarea); }
		
		
		if($continuar_1)
		{
			if(DEBUG){ echo"Eliminar registro de BBDD<br>";}
			$cons_D="DELETE FROM tareas_docente WHERE id='$id_tarea' LIMIT 1";
			
			if(DEBUG){echo"----> $cons_D<br>";}
			else
			{
				if($conexion_mysqli->query($cons_D))
				{
					 require("../../../../funciones/VX.php");
					 $evento="Elimina Tarea docente (tarea) id_tarea: $id_tarea";
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
$url="elimina_tarea_2.php?error=$error";
if(DEBUG){ echo"URL: $url<br>";}
else{ header("location: $url");}
?>