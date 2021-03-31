<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$url="formu.php";
if($_GET)
{
	$destino=$_GET["url"];
}
$validador=md5("GDXT".date("d-m-Y"));

if(isset($_SESSION["FINANZAS"]))
{unset($_SESSION["FINANZAS"]);}
switch ($destino)
{
	case"menu_principal":
		$url="../index.php";
		break;
	case"formulario_matricula";	
		$url="formu.php";
		break;
	case"HALL":
		$url="../../buscador_alumno_BETA/HALL/index.php";
		break;	
	case"enrutador":
		$url="../../buscador_alumno_BETA/enrutador.php?validador=$validador&id_alumno=".$_SESSION["SELECTOR_ALUMNO"]["id"];
		break;
}
if(DEBUG){echo"URL: $url<br>";}
else{header("location: $url");}
?>