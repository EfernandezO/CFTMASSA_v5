<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_dump($_GET);}
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_GET["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$id_planificacion=mysqli_real_escape_string($conexion_mysqli, $_GET["id_planificacion"]);
	
	if((is_numeric($id_planificacion))and($id_planificacion>0))
	{
		$cons_D="DELETE FROM planificaciones WHERE id_planificacion='$id_planificacion' LIMIT 1";
		if(DEBUG){ echo"---> $cons_D<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_D); 
			$error="PE0";
			require("../../../../funciones/VX.php");
			$evento="Elimina Registro Planificacion id_carrera: $id_carrera cod_asignatura: $cod_asignatura Sede:$sede";
			REGISTRA_EVENTO($evento);
		}
	}
	
	$conexion_mysqli->close();
	
	$url="../ver_planificaciones.php?id_carrera=".base64_encode($id_carrera)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&sede=".base64_encode($sede)."&cod_asignatura=".base64_encode($cod_asignatura)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo_curso)."&error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}