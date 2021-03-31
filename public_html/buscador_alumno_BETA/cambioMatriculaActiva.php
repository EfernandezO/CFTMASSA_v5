<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$url_destino="HALL/index.php";
if(DEBUG){ var_export($_SESSION["ULTIMO_ALUMNO"]); echo"<br><br>";}
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	require("../../funciones/funciones_sistema.php");
	require("../../funciones/class_ALUMNO.php");
	
	$idCarreraNew=base64_decode($_GET["id_carreraNew"]);
	$yearIngresoNew=base64_decode($_GET["yearIngresoNew"]);
	$situacionNew=base64_decode($_GET["situacionNew"]);
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$ALUMNO=new ALUMNO($id_alumno);
	$nivelAcademicoMatricula=$ALUMNO->getNivelAcademicoActual($idCarreraNew, $yearIngresoNew);

	$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$idCarreraNew;
	$_SESSION["SELECTOR_ALUMNO"]["carrera"]=NOMBRE_CARRERA($idCarreraNew);
	$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"]=$yearIngresoNew;
	$_SESSION["SELECTOR_ALUMNO"]["situacion"]=$situacionNew;
	$_SESSION["SELECTOR_ALUMNO"]["nivel_academico"]=$nivelAcademicoMatricula;
}

if(DEBUG){ echo" URL: $url_destino<br>";}
else{ header("location: $url_destino");}
?>