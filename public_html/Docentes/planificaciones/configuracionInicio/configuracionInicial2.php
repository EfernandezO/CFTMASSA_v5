<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


if(DEBUG){echo"Inicio configuracion inicial 2 planificaciones<br>";}
require("../../../../funciones/conexion_v2.php");
if($_POST){
	
	var_dump($_POST);
	
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$id_funcionario=$_POST["id_funcionario"];
	$numeroSemanas=$_POST["numeroSemanas"];
	
	///consulta si esta creado el registro MAIN planificaciones

$consMAIN="SELECT idPlanificacionMain, numeroSemanas FROM planificacionesMain WHERE semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND id_funcionario='$id_funcionario'";
$sqliMain=$conexion_mysqli->query($consMAIN)or die($conexion_mysqli->error);
$DMain=$sqliMain->fetch_assoc();
	$id_planificacionMain=$DMain["idPlanificacionMain"];
	//$numeroSemanas=$DMain["numeroSemanas"];
	if(empty($id_planificacionMain)){$id_planificacionMain=0;}
$sqliMain->free();
	
	
$accion="editar";
if($id_planificacionMain==0){
	$accion="crear";
}

$fechaActual=date("Y-m-d");

	switch($accion){
		case"editar":
			$consX="UPDATE planificacionesMain SET numeroSemanas='$numeroSemanas', fecha_generacion='$fechaActual' WHERE idPlanificacionMain='$id_planificacionMain' LIMIT 1";
			$conexion_mysqli->query($consX);
			break;
		case"crear":
			$consX="INSERT INTO planificacionesMain (semestre, year, sede,id_carrera, cod_asignatura, jornada, grupo, id_funcionario, numeroSemanas, fecha_generacion) VALUES ('$semestre', '$year', '$sede', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$id_funcionario', '$numeroSemanas', '$fechaActual')";
			$conexion_mysqli->query($consX)or die($conexion_mysqli->error);
			$id_planificacionMain=$conexion_mysqli->insert_id;
			break;
	}

	
	$urlDestino="../ver_planificaciones.php?id_planificacionMain=".base64_encode($id_planificacionMain);
	
	if(DEBUG){ echo"<br>$urlDestino<br>";}
	else{header("location: $urlDestino");} 

}
?>