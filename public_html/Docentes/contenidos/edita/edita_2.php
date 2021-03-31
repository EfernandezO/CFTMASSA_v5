<?php
//-----------------------------------------//
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
	
if($_POST)	
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$id_contenido=$_POST["id_contenido"];
	$id_contenidoMain=$_POST["id_contenidoMain"];
	$contenido_tematico=$_POST["contenido_tematico"];
	
	$numero_semana=$_POST["numero_semana"];
	$duracion_clase=$_POST["horas_semana"];
	$contenido=$_POST["contenido"];
	$tipoActividad=$_POST["tipoActividad"];
	$fecha_clase=$_POST["fecha_clase"];
	$hora_inicio_clase=$_POST["hora_inicio"];
	$minuto_inicio_clase=$_POST["minuto_inicio"];
	$bibliografia=$_POST["bibliografia"];
	
	$fecha_actual=date("Y-m-d");
	
	$horario_clase=$hora_inicio_clase.":".$minuto_inicio_clase;
	
	
	require("../../../../funciones/conexion_v2.php");
	
		$campos_valor="numero_semana='$numero_semana', fecha_clase='$fecha_clase', horario_inicio_clase='$horario_clase', duracion_clase='$duracion_clase', contenido='$contenido', tipo_actividad='$tipoActividad', bibliografia='$bibliografia', fecha_generacion='$fecha_actual'";
		
		
		$CONS_UP="UPDATE contenidosDetalle SET $campos_valor WHERE id_contenido='$id_contenido' AND idContenidoMain='$id_contenidoMain' LIMIT 1";
		
		if(DEBUG){ echo"---> $CONS_UP<br>";}
		else
		{
			if($conexion_mysqli->query($CONS_UP))
			{
				require("../../../../funciones/VX.php"); 
				$evento="Edita Registro a contenido id_contenido: $id_contenido id_contenidoMain: $id_contenidoMain";
				REGISTRA_EVENTO($evento);
				$error="PE0";
			}
			else{ $error="PE1"; echo $conexion_mysqli->error;}
		}
	$conexion_mysqli->close();
	
	$URL="edita_3.php?id_contenidoMain=".base64_encode($id_contenidoMain)."&error=$error";
	if(DEBUG){ echo"URL $URL<br>";}
	else{ header("location: $URL");}
}
?>