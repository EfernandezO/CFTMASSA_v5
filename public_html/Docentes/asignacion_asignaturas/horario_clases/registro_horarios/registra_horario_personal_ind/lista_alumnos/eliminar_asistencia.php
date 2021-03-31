<?php
//--------------CLASS_okalis------------------//
	require("../../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="";
if($_GET)
{
	require("../../../../../../../funciones/conexion_v2.php");
	
	
	if(DEBUG){ var_dump($_GET);}
	$sede=base64_decode($_GET["sede"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$grupo=base64_decode($_GET["grupo"]);
	$fecha_clase=base64_decode($_GET["fecha_clase"]);
	$H_id=base64_decode($_GET["H_id"]);
	
	$cons_D="DELETE FROM asistencia_alumnos WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND grupo='$grupo' AND fecha_clase='$fecha_clase' AND id_horario='$H_id'";
	
	if(DEBUG){ echo"<br>---> $cons_D<br>";}
	else{
			if($conexion_mysqli->query($cons_D))
			{ 
				$error="EA0";
				//----------------------------------------------------------//
				require("../../../../../../../funciones/VX.php");
				$evento="Elimina Asistencia del dia $fecha_clase sede: $sede id_carrera: $id_carrera jornada: $jornada grupo: $grupo asignatura: $cod_asignatura periodo [$semestre - $year] ";
				REGISTRA_EVENTO($evento);
				//----------------------------------------------------------//
			}
			else{ $error="EA1"; echo"error".$conexion_mysqli->error;}
		}
		
	$URL="eliminar_asistencia_2.php?error=$error";	
	if(DEBUG){ echo"URL: $URL<br>";}
	else{ header("location: $URL");}
	$conexion_mysqli->close();
}
?>