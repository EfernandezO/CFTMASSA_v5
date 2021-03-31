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
require("../../../funciones/class_ASISTENCIA_ALUMNOS.php");
$idUsuarioActual=$_SESSION["USUARIO"]["id"];

$error="A0";
$url="";
if(DEBUG){ var_dump($_POST); echo"<br>";}
if($_POST){
	$id_curso=$_POST["id_curso"];
	$id_clase=$_POST["id_clase"];
	$array_horasPresente=$_POST["hrsPresente"];
	
	$ASISTENCIA_ALUMNO = new ASISTENCIA_ALUMNOS($id_curso);
	//$ASISTENCIA_ALUMNO->setDebug(true);
	$ASISTENCIA_ALUMNO->setIdClase($id_clase);
	$hay_registro_en_esta_clase=$ASISTENCIA_ALUMNO->HAY_REGISTRO_ASISTENCIA_EN_CLASE();
	if($hay_registro_en_esta_clase){
		//borro registros previos antes de guardar los nuevos
		$ASISTENCIA_ALUMNO->BORRAR_REGISTRO_ASISTENCIA_CLASE();
	}
	
	if(count($array_horasPresente)>0){
		foreach($array_horasPresente as $auxIdAlumno => $auxNumHoras){
			echo"$auxIdAlumno -> $auxNumHoras<br>";
			if($ASISTENCIA_ALUMNO->setIdAlumno($auxIdAlumno)){
				if(DEBUG){ echo"Alumno seleccionado correctamente...<br>";}
				if($ASISTENCIA_ALUMNO->REGISTRA_ASISTENCIA($auxNumHoras, $idUsuarioActual)){
					if(DEBUG){ echo"Registro de asistencia grabado correctamente...<br>";}
				}else{ if(DEBUG){ echo"ERROR al REGISTRAR<br>";} $error="A1";}
					

			}
			
		}
	}
	
	$url="../gestionAsistencia/asistenciaClases.php?id_curso=".base64_encode($id_curso)."&id_clase=".base64_encode($id_clase)."&error=$error";
}

if(DEBUG){echo"URL: $url<br>";}
else{ header("location: $url");}
?>