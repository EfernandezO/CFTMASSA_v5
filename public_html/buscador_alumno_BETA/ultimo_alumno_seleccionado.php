<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$url_destino="HALL/index.php";
if(DEBUG){ var_dump($_SESSION["ULTIMO_ALUMNO"]); echo"<br><br>";}
	if(isset($_SESSION["ULTIMO_ALUMNO"]["id_alumno"]))
	{
		$id_alumnoActual=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$key=array_search($id_alumnoActual, $_SESSION["ULTIMO_ALUMNO"]["id_alumno"]);
		if(DEBUG){ echo"el id_alumno Actual: $id_alumnoActual, esta en la posicion $key<br>" ;}
		
		
	}
	$sql->free();
	$conexion_mysqli->close();

if(DEBUG){ echo" URL: $url_destino<br>";}
else{ header("location: $url_destino");}
?>