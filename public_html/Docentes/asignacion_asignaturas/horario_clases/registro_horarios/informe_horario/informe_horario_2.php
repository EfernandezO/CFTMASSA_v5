<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
$sede_actual=$_SESSION["USUARIO"]["sede"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("informe_horario_server.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_GRUPOS_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"CARGA_INFORME_ASISTENCIA");
////////////////////////////////////////////

if(DEBUG){var_dump($_GET);}
$continuar=false;
if($_GET)
{
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_sistema.php");
	$continuar=true;
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$nivel=base64_decode($_GET["nivel"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
}
?>
<?php $xajax->printJavascript(); ?> 

<style type="text/css">
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 207px;
}
#div_grupo {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 76px;
}
-->
</style>

</head>

<body onload="xajax_CARGA_INFORME_ASISTENCIA(<?php echo"'$sede', '$id_carrera', '$jornada', '$grupo_curso', '$cod_asignatura', '$semestre', '$year'"; ?>);">
<h1 id="banner">Administrador - Horario</h1>
<div id="apDiv2">
	
</div>
<div id="div_grupo" ></div>
</body>
</html>