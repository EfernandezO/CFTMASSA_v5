<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="1";
if($_POST){
	if(DEBUG){var_dump($_POST);}
	
	require("../../../../../funciones/conexion_v2.php");
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$id_evaluacion=$_POST["id_evaluacion"];
	
	$nombreEvaluacion=$_POST["nombreEvaluacion"];
	$fechaEvaluacion=$_POST["fechaEvaluacion"];
	
	$cons_UP="UPDATE notas_parciales_evaluaciones SET nombre_evaluacion='$nombreEvaluacion', fecha_evaluacion='$fechaEvaluacion' WHERE id='$id_evaluacion' LIMIT 1";
	
	if(DEBUG){ echo"<br>--> $cons_UP<br>";}
	else{ $conexion_mysqli->query($cons_UP);}
	
	
}

	$url="../lista_evaluaciones.php?sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&cod_asignatura=".base64_encode($cod_asignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&error=$error";
	
	if(DEBUG){ echo "URL $url<br>";}
	else{ header("location: $url");}
?>