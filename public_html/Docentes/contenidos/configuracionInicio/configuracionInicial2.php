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


if(DEBUG){echo"Inicio configuracion inicial 2 contenidos<br>";}
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

$consMAIN="SELECT idContenidoMain, numero_semanas FROM contenidosMain WHERE semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND id_funcionario='$id_funcionario'";
$sqliMain=$conexion_mysqli->query($consMAIN)or die("INICIAL : ".$conexion_mysqli->error);
$DMain=$sqliMain->fetch_assoc();
	$id_contenidoMain=$DMain["idContenidoMain"];
	//$numeroSemanas=$DMain["numeroSemanas"];
	if(empty($id_contenidoMain)){$id_contenidoMain=0;}
$sqliMain->free();
	
	
$accion="editar";
if($id_contenidoMain==0){
	$accion="crear";
}

$fechaActual=date("Y-m-d");

	switch($accion){
		case"editar":
			$consX="UPDATE contenidosMain SET numero_semanas='$numeroSemanas', fecha_generacion='$fechaActual' WHERE idContenidoMain='$id_contenidoMain' LIMIT 1";
			$conexion_mysqli->query($consX)or die("UP ".$conexion_mysqli->error);
			break;
		case"crear":
			$consX="INSERT INTO contenidosMain (semestre, year, sede,id_carrera, cod_asignatura, jornada, grupo, id_funcionario, numero_semanas, fecha_generacion) VALUES ('$semestre', '$year', '$sede', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$id_funcionario', '$numeroSemanas', '$fechaActual')";
			$conexion_mysqli->query($consX)or die("IN ".$conexion_mysqli->error);
			$id_contenidoMain=$conexion_mysqli->insert_id;
			break;
	}

	
	$urlDestino="../ver_contenidos.php?id_contenidoMain=".base64_encode($id_contenidoMain);
	
	if(DEBUG){ echo"<br>$urlDestino<br>";}
	else{header("location: $urlDestino");} 

}
?>