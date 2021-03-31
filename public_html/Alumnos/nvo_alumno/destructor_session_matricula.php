<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["MATRICULA"]))
{
	unset($_SESSION["MATRICULA"]);
}
if($_GET)
{
	$url=$_GET["url"];
	//echo"GET -> $url<br>";
	switch($url)
	{
		case"menu_principal":
			$url_destino="../../Administrador/ADmenu.php";
			break;
		case"paso_A":
			$url_destino="paso_A.php";	
			break;
		case"menu_alumno":	
			$url_destino="../menualumnos.php";
			break;
		case"menu_matricula":
			$url_destino="../../Administrador/menu_matricula/index.php";
			break;
		case"menu_finanzas":
			$url_destino="../../contabilidad/index.php";
			break;	
		case"HALL":
			$url_destino="../../buscador_alumno_BETA/HALL/index.php";
			break;	
		case"modificacion_alumno":
			$url_destino="../edit_alumno/buscaalumno2_tab.php?ver=2";
			break;	
	}
	//echo"---> $url_destino";
	header("location: $url_destino");
}
else
{
	header("location: ../menualumnos.php");
}
?>