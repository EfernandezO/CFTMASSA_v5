<?php
//-----------------------------------------//
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->nuevoRegistro");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
	
if($_POST)	
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	
	
	$id_contenidoMain=$_POST["id_contenidoMain"];
	
	$numero_semana=$_POST["numero_semana"];
	$horas_semana=$_POST["horas_semana"];
	$contenido=$_POST["contenido"];
	$tipoActividad=$_POST["tipoActividad"];
	$fecha_clase=$_POST["fecha_clase"];
	$hora_inicio_clase=$_POST["hora_inicio"];
	$minuto_inicio_clase=$_POST["minuto_inicio"];
	$bibliografia=$_POST["bibliografia"];
	
	$fecha_actual=date("Y-m-d");
	
	$horario_clase=$hora_inicio_clase.":".$minuto_inicio_clase;
	
	
	require("../../../../funciones/conexion_v2.php");
	
		$campos="idContenidoMain, numero_semana, fecha_clase, horario_inicio_clase, duracion_clase, contenido, tipo_actividad, bibliografia, fecha_generacion";
		
		$valores="'$id_contenidoMain', '$numero_semana', '$fecha_clase', '$horario_clase', '$horas_semana', '$contenido', '$tipoActividad', '$bibliografia', '$fecha_actual'";
		
		$CONS_IN="INSERT INTO contenidosDetalle ($campos) VALUES ($valores)";
		
		if(DEBUG){ echo"<br>---> $CONS_IN<br>";}
		else
		{
			if($conexion_mysqli->query($CONS_IN))
			{
				require("../../../../funciones/VX.php"); 
				$evento="Crea Registro a Contenidos id contenido Main: $id_contenidoMain";
				REGISTRA_EVENTO($evento);
				$error="PI0";
			}
			else{ $error="PI1"; echo $conexion_mysqli->error;}
		}
	$conexion_mysqli->close()or die(":::".$conexion_mysqli->error);
	
	$URL="nueva_3.php?id_contenidoMain=$id_contenidoMain&error=$error";
	
	if(DEBUG){ echo"URL $URL<br>";}
	else{ header("location: $URL");}
}
?>