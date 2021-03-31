<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("informe_horario_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PERIODOS");
$xajax->register(XAJAX_FUNCTION,"CARGA_INFORME_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"BUSCA_ASIGNATURAS");
////////////////////////////////////////////
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
$sede_actual=$_SESSION["USUARIO"]["sede"];

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];;
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<?php $xajax->printJavascript(); ?> 
<title>Asistencia</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#div_periodos {
	position:absolute;
	width:30%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 100px;
}
#div_resultados {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 348px;
}
#div_asignaturas {
	position:absolute;
	width:45%;
	height:115px;
	z-index:3;
	left: 40%;
	top: 102px;
}
</style>
</head>

<body onload="xajax_BUSCA_PERIODOS(<?php echo"'$id_alumno', '$id_carrera'";?>);">
<h1 id="banner">Administrador - Horario</h1>
<div id="link"><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="div_periodos"></div>
<div id="div_resultados"></div>
<div id="div_asignaturas"></div>
</body>
</html>