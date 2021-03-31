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
	if(DEBUG){ var_dump($_SESSION);}
	if(isset($_SESSION["SELECTOR_ALUMNO"]))
	{
		if(DEBUG){ echo"HAY SESION ALUMNO<br>";}
		unset($_SESSION["SELECTOR_ALUMNO"]);
	}
	else
	{if(DEBUG){ echo"NO HAY SESION ALUMNO<br>";}}
	
	if(DEBUG){ echo"URL: $url_destino<br>";}
	else{header("location: $url_destino");}
?>