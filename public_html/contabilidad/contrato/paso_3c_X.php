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
////////////////
$acceso=false;
$validador=$_POST["validador"];
$comparador=md5("PASO3".date("Y-m-d"));

if(DEBUG){ var_dump($_POST); echo"<br><br>";}

if($comparador==$validador)
{
	$_SESSION["FINANZAS"]["paso3"]=true;
	$url="resumenV2.php";
	if(DEBUG){var_dump($_SESSION["FINANZAS"]);}
}
else
{
	$url="formu.php";
}
if(DEBUG){ echo"$url<br>";}
else{ header("location: $url");}
?>