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

$continuar=true;
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
	
}
if($_GET){
	
	$semestre=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]));
	$year=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
	$cod_asignatura=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["cod_asignatura"]));
	$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
	$grupo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]));
	$id_funcionario=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_funcionario"]));
}
if($continuar){
	///consulta si esta creado el registro MAIN planificaciones

$consMAIN="SELECT idCurso FROM cursos WHERE semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo'";
$sqliMain=$conexion_mysqli->query($consMAIN)or die($conexion_mysqli->error);
$DMain=$sqliMain->fetch_assoc();
	$id_curso=$DMain["idCurso"];
	//$numeroSemanas=$DMain["numeroSemanas"];
	if(empty($id_curso)){$id_curso=0;}
$sqliMain->free();
	
	
$accion="";
if($id_curso==0){
	$accion="crear";
}

$fechaActual=date("Y-m-d");

	switch($accion){
		case"crear":
			$consX="INSERT INTO cursos (semestre, year, sede,id_carrera, cod_asignatura, jornada, grupo, fecha_generacion) VALUES ('$semestre', '$year', '$sede', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$fechaActual')";
			$conexion_mysqli->query($consX)or die($conexion_mysqli->error);
			$id_curso=$conexion_mysqli->insert_id;
			break;
	}

	
	$urlDestino="../verAsistencia?id_curso=".base64_encode($id_curso);
	
	if(DEBUG){ echo"<br>$urlDestino<br>";}
	else{header("location: $urlDestino");} 

}
?>