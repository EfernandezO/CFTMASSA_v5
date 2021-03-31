<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_titulacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$error="";
$error=0;
if(($_GET)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	if(DEBUG){ var_export($_GET);}

	$idProcesoTitulacion=$_GET["idProceso"];

	$consE="DELETE FROM proceso_titulacion where id='$idProcesoTitulacion' LIMIT 1";

	if(DEBUG){ echo"--->$consE";}
	else{	
		if($conexion_mysqli->query($consE)){ $error="7";}
		else{$error="8";}
	}

	
	$url="proceso_titulacion_final.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}

?>