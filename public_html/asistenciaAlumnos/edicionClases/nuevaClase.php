<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("AsistenciaManualAlumno->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$id_usuario_actual=$_SESSION["USUARIO"]["id"];
require("../../../funciones/class_ASISTENCIA_ALUMNOS.php");
$url="";

if($_POST){
	if(DEBUG){var_dump($_POST);}
	$error="C0";
	
	$id_curso=$_POST["id_curso"];
	$fecha_clase=$_POST["fecha_clase"];
	$horaInicio=$_POST["hora_inicio"];
	$minutoInicio=$_POST["minuto_inicio"];
	$horario_clase=$horaInicio.":".$minutoInicio.":00";
	
	$duracionClase=$_POST["duracionClase"];
	$modalidadClase=$_POST["modalidadClase"];
	
	$ASISTENCIA_ALUMNOS=new ASISTENCIA_ALUMNOS($id_curso);
	
	if(DEBUG){ echo"Horario Clase Actual: $horario_clase<br>";}
	$claseCreada=$ASISTENCIA_ALUMNOS->REGISTRA_CLASE($fecha_clase, $horario_clase,$duracionClase, $modalidadClase);
	
	if(!$claseCreada){$error="C1";}
	
	$url="../gestionAsistencia/asistenciaClases.php?id_curso=".base64_encode($id_curso)."&error=$error";
	
}

header("location: $url");

?>