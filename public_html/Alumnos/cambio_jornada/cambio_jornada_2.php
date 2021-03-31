<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Cambio_jornada_alumno");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="";
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, $_POST["id_alumno"]);
	$semestre_cambio=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year_cambio=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$jornada_nueva=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada_nueva"]);
	$jornada_old=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada_old"]);
	
	
	$cons_A="UPDATE alumno set jornada='$jornada_nueva' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1;";
	
	$cons_C="UPDATE contratos2 set jornada='$jornada_nueva' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre_cambio' AND ano='$year_cambio' AND vigencia='semestral'";
	
	$cons_C2="UPDATE contratos2 set jornada='$jornada_nueva' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ano='$year_cambio' AND vigencia='anual'";
	
	$cons_TR="UPDATE toma_ramos SET jornada='$jornada_nueva' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre_cambio' AND year='$year_cambio';";
	
	if(DEBUG){ echo"<br>A: $cons_A<br> C: $cons_C <br> TR: $cons_TR<br>";}
	else
	{
		if($conexion_mysqli->query($cons_A) and $conexion_mysqli->query($cons_C) and $conexion_mysqli->query($cons_C2) and $conexion_mysqli->query($cons_TR))
		{
			$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$jornada_nueva;
			$error="J0";
			 $evento="Cambio de Jornada desde [$jornada_old] hacia [$jornada_nueva] en periodo [$semestre_cambio - $year_cambio]";
			 REGISTRA_EVENTO($evento);
			 $descripcion="Cambio de Jornada desde [$jornada_old] hacia [$jornada_nueva] en periodo [$semestre_cambio - $year_cambio]";
			 REGISTRO_EVENTO_ALUMNO($id_alumno, 'notificacion',$descripcion);
			 ///actualizo condicion a T en alumno
		}
		else
		{$error="J1";}
	}
	
	
}
  $url="cambio_jornada_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}  