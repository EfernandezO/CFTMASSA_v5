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
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$sede=$_POST["sede"];
	$jornada=$_POST["jornada"];
	$grupo_curso=$_POST["grupo_curso"];
	
	$id_programa=$_POST["id_programa"];
	
	if($id_programa==0){$contenido_tematico=$_POST["contenido_tematico"];}
	
	$numero_semana=$_POST["numero_semana"];
	$horas_semana=$_POST["horas_semana"];
	$actividad=$_POST["actividad"];
	$implemento=$_POST["implemento"];
	$evaluacion=$_POST["evaluacion"];
	$bibliografia=$_POST["bibliografia"];
	
	
	require("../../../../funciones/conexion_v2.php");
	
		$campos_valor="numero_semana='$numero_semana', horas_semana='$horas_semana', actividad='$actividad', implemento='$implemento', evaluacion='$evaluacion', bibliografia='$bibliografia', fecha_generacion='$fecha_actual'";
		
		if($id_programa==0){ $campos_valor.=", contenido_tematico_opcional='$contenido_tematico'";}
		

		
		$CONS_IN="UPDATE planificaciones SET $campos_valor WHERE id_planificacion='$id_planificacion' LIMIT 1";
		
		if(DEBUG){ echo"---> $CONS_IN<br>";}
		else
		{
			if($conexion_mysqli->query($CONS_IN))
			{
				require("../../../../funciones/VX.php"); 
				$evento="Edita Registro a Planificacion Sede: $sede id_carrera: $id_carrera cod_asignatura: $cod_asignatura id_programa: $id_programa";
				REGISTRA_EVENTO($evento);
				$error="PE0";
			}
			else{ $error="PE1"; echo $conexion_mysqli->error;}
		}
	$conexion_mysqli->close();
	
	$URL="edita_planificacion_3.php?id_carrera=".base64_encode($id_carrera)."&cod_asignatura=".base64_encode($cod_asignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo_curso)."&sede=".base64_encode($sede)."&error=$error";
	if(DEBUG){ echo"URL $URL<br>";}
	else{ header("location: $URL");}
}
?>