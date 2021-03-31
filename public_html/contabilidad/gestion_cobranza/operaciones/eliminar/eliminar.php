<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar_1=true;
require("../../../../../funciones/conexion_v2.php");
if(isset($_GET["id_cobranza"]))
{
	$id_cobranza=base64_decode($_GET["id_cobranza"]);
	
	$sede=mysqli_real_escape_string($conexion_mysqli,$_GET["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli,$_GET["id_carrera"]);
	$year_ingreso=mysqli_real_escape_string($conexion_mysqli,$_GET["year_ingreso"]);
	$year_cuotas=mysqli_real_escape_string($conexion_mysqli,$_GET["year_cuotas"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli,$_GET["jornada"]);
	$grupo=mysqli_real_escape_string($conexion_mysqli,$_GET["grupo"]);
	$fecha_corte=mysqli_real_escape_string($conexion_mysqli,$_GET["fecha_corte"]);
	$posicion=mysqli_real_escape_string($conexion_mysqli,$_GET["posicion"]);
	$niveles=mysqli_real_escape_string($conexion_mysqli,$_GET["niveles"]);
	
	
	if(is_numeric($id_cobranza)){ $continuar_1=true;}
	else{ $continuar_1=false;}
}

$error="debbug";
if($continuar_1)
{
	
	require("../../../../../funciones/VX.php");
	$cons_D="DELETE FROM cobranza WHERE id_cobranza='$id_cobranza' LIMIT 1";
	if(DEBUG){ echo"$cons_D<br>";}
	else{ $conexion_mysqli->query($cons_D)or die($conexion_mysqli->error); $error="CE1";}
	//--------------------------------------------//
	$evento="Elimina Registro de Cobranza a Alumno";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------////
	
}
$conexion_mysqli->close();
$url="../cobranza_2.php?error=$error&sede=".$sede."&id_carrera=".$id_carrera."&year_ingreso=".$year_ingreso."&year_cuotas=".$year_cuotas."&jornada=".$jornada."&grupo=".$grupo."&fecha_corte=".$fecha_corte."&posicion=".$posicion."&niveles=".$niveles;
if(DEBUG){ echo"URL: $url";}
else{ header("location: $url");}
?>