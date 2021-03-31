<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->nuevoRegistro");
	$O->PERMITIR_ACCESO_USUARIO();
	
if($_POST)	
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	
	
	$id_planificacionMain=$_POST["id_planificacionMain"];
	$contenido_tematico=$_POST["contenido_tematico"];
	
	$numero_semana=$_POST["numero_semana"];
	$horas_semana=$_POST["horas_semana"];
	$actividad=$_POST["actividad"];
	$implemento=$_POST["implemento"];
	$evaluacion=$_POST["evaluacion"];
	$bibliografia=$_POST["bibliografia"];
	
	$fecha_actual=date("Y-m-d");
	
	require("../../../../funciones/conexion_v2.php");
	
		$campos="idPlanificacionMain, numero_semana, horas_semana, actividad, implemento, evaluacion, bibliografia, fecha_generacion, contenido_tematico_opcional";
		
		$valores="'$id_planificacionMain', '$numero_semana', '$horas_semana', '$actividad', '$implemento', '$evaluacion', '$bibliografia', '$fecha_actual','$contenido_tematico'";
		
		$CONS_IN="INSERT INTO planificaciones ($campos) VALUES ($valores)";
		
		if(DEBUG){ echo"<br>---> $CONS_IN<br>";}
		else
		{
			if($conexion_mysqli->query($CONS_IN))
			{
				require("../../../../funciones/VX.php"); 
				$evento="Crea Registro a Planificacion id_planificacin Main: $id_planificacionMain";
				REGISTRA_EVENTO($evento);
				$error="PI0";
			}
			else{ $error="PI1"; echo $conexion_mysqli->error;}
		}
	$conexion_mysqli->close();
	
	$URL="nueva_planificacion_3.php?id_planificacionMain=$id_planificacionMain&error=$error";
	
	if(DEBUG){ echo"URL $URL<br>";}
	else{ header("location: $URL");}
}
?>