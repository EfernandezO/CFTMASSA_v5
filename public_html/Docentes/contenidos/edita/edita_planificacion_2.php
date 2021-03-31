<?php
//-----------------------------------------//
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
	
if($_POST)	
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	$id_planificacion=$_POST["id_planificacion"];
	$id_planificacionMain=$_POST["id_planificacionMain"];
	$contenido_tematico=$_POST["contenido_tematico"];
	
	$numero_semana=$_POST["numero_semana"];
	$horas_semana=$_POST["horas_semana"];
	$actividad=$_POST["actividad"];
	$implemento=$_POST["implemento"];
	$evaluacion=$_POST["evaluacion"];
	$bibliografia=$_POST["bibliografia"];
	
	
	require("../../../../funciones/conexion_v2.php");
	
		$campos_valor="numero_semana='$numero_semana', horas_semana='$horas_semana', actividad='$actividad', implemento='$implemento', evaluacion='$evaluacion', bibliografia='$bibliografia', fecha_generacion='$fecha_actual'";
		
		if($id_programa==0){ $campos_valor.=", contenido_tematico_opcional='$contenido_tematico'";}
		

		
		$CONS_IN="UPDATE planificaciones SET $campos_valor WHERE id_planificacion='$id_planificacion' AND idPlanificacionMain='$id_planificacionMain' LIMIT 1";
		
		if(DEBUG){ echo"---> $CONS_IN<br>";}
		else
		{
			if($conexion_mysqli->query($CONS_IN))
			{
				require("../../../../funciones/VX.php"); 
				$evento="Edita Registro a Planificacion id_planificacion: $id_planificacion id_planificacionMain: $id_planificacionMain";
				REGISTRA_EVENTO($evento);
				$error="PE0";
			}
			else{ $error="PE1"; echo $conexion_mysqli->error;}
		}
	$conexion_mysqli->close();
	
	$URL="edita_planificacion_3.php?id_planificacionMain=".base64_encode($id_planificacionMain)."&error=$error";
	if(DEBUG){ echo"URL $URL<br>";}
	else{ header("location: $URL");}
}
?>