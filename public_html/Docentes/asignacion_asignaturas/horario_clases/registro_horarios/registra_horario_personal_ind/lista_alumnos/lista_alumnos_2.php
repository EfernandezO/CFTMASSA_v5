<?php
//-----------------------------------------//
	require("../../../../../../OKALIS/seguridad.php");
	require("../../../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$error="";
if($_POST)
{
	require("../../../../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_dump($_POST);}
	
	$fecha_actual=date("Y-m-d");
	$hora_actual=date("H:i:s");
	
	$fecha_hora_actual_time=strtotime($fecha_actual.$hora_actual);
	//$fecha_hora_actual_time=strtotime("2017-05-0418:20:44");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$tipo_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_usuario"]);
	
	if($tipo_usuario=="administrador"){ $verificar_horario=false;}
	else{ $verificar_horario=true;}
	
	
	$participantes_curso=mysqli_real_escape_string($conexion_mysqli, $_POST["participantes_curso"]);
	$hrs_pedagogicas=mysqli_real_escape_string($conexion_mysqli, $_POST["horas_pedagogicas"]);
	
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_POST["cod_asignatura"]);
	$grupo=mysqli_real_escape_string($conexion_mysqli, $_POST["grupo"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$H_id=mysqli_real_escape_string($conexion_mysqli, $_POST["H_id"]);
	$ARRAY_ASISTENCIA=$_POST["asistencia"];
	$fecha_clase=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_clase"]);
	
	//---------------------------------------------------------------------------//
	
	$cons_H="SELECT hora_inicio, hora_fin FROM horario_docente WHERE id_horario='$H_id' LIMIT 1";
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$DH=$sqli_H->fetch_assoc();
		$H_hora_inicio=$DH["hora_inicio"];
		$H_hora_inicio_time=strtotime($fecha_actual.$H_hora_inicio);
		$H_hora_fin=$DH["hora_fin"];
		$H_hora_fin_time=strtotime($fecha_actual.$H_hora_fin);
	$sqli_H->free();	
	
	////-------------------------------------------------------------------------//
	//verifico que registro se realice dentro del rango horario
	
	if($verificar_horario)
	{
		if(($fecha_hora_actual_time>=$H_hora_inicio_time)and($fecha_hora_actual_time<=$H_hora_fin_time))
		{ $horario_ok=true; if(DEBUG){ echo"Verificacion Horario OK<br>";}}
		else{ $horario_ok=false; if(DEBUG){ echo"Verificacion Horario ERROR<br>";}}	
	}else{if(DEBUG){ echo"No Verificar Horario<br>";} $horario_ok=true;}
	
	///busco si hay registros
	$cons_B="SELECT COUNT(id_asistencia) FROM asistencia_alumnos WHERE semestre='$semestre' AND  year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND jornada='$jornada' AND cod_asignatura='$cod_asignatura' AND grupo='$grupo' AND fecha_clase='$fecha_clase' AND id_horario='$H_id'";
	
	$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$D_B=$sqli_B->fetch_row();
		$coincidencias=$D_B[0];
		if(empty($coincidencias)){$coincidencias=0;}
	$sqli_B->free();
	if($coincidencias>0){ $asistencia_ok=false;}
	else{ $asistencia_ok=true;}
	//---------------------------------------------------------------------------//	
	
	if($coincidencias>0){$grabar=false;}
	else{$grabar=true;}
	
	//---------------------------------------------------------------------///
	if($horario_ok)
	{
		if($asistencia_ok)
		{
			foreach($ARRAY_ASISTENCIA as $aux_id_alumno =>$aux_condicion)
			{
				if(DEBUG){echo"$aux_id_alumno -> $aux_condicion<br>";}
				
				if($aux_condicion!=="no_considerar")
				{
				
					$cons_IN="INSERT INTO asistencia_alumnos (semestre, year, sede, id_carrera, cod_asignatura, jornada, grupo, participantes_curso, horas_pedagogicas, fecha_clase, fecha, hora, id_alumno, asistencia, id_horario, id_usuario_actual) VALUES ('$semestre', '$year', '$sede', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$participantes_curso', '$hrs_pedagogicas', '$fecha_clase', '$fecha_actual', '$hora_actual', '$aux_id_alumno', '$aux_condicion', '$H_id', '$id_usuario_actual')";
					
					if(DEBUG){ echo"---> $cons_IN<br>";}
					else{$conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);}
				}
			}
			 $error="AA0";
		}
		else
		{ $error="AA1";}
	}
	else{ $error="AA2";}
}

$url="lista_alumnos_3.php?error=$error";
if(DEBUG){ echo"URL: $url";}
else{ header("location: $url");}
?>