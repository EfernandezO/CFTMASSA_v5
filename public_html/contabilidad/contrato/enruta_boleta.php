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
if(isset($_GET["id_boleta"]))
{ $id_boleta=$_GET["id_boleta"];}
else{ $id_boleta=0;}

if(isset($_GET["tipo"]))
{ $tipo_boleta=$_GET["tipo"];}
else{ $tipo_boleta=0;}

switch($tipo_boleta)
{
	case"matricula":
		if(DEBUG){ echo"Tipo Matricula...<br>";}
		else{{$_SESSION["FINANZAS"]["impresion"]["boleta"]=true;}}
		break;
	case"pagare":
		if(DEBUG){ echo"Tipo pagare...<br>";}
		else{{$_SESSION["FINANZAS"]["impresion"]["boleta_pagare"]=true;}}
		break;
}
$url="imprimibles/boleta/boleta_1.php?id_boleta=$id_boleta";
if(DEBUG){ echo"URL: $url<br>";}
else{header("location: $url");}
?>