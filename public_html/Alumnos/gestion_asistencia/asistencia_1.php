<?php
//-----------------------------------------//
	define("DEBUG", false);
//-----------------------------------------//	
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("asistencia_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"CARGA_LISTA_ASISTENCIA");
/////////////////////////////
//------------------------------------------//
require("../../../funciones/funciones_sistema.php");
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{$semestre_actual=1;}
$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
$dia_actual=date("w");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Asistencia</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 96px;
}
#div_boton {
	position:absolute;
	width:40%;
	height:42px;
	z-index:2;
	left: 50%;
	top: 303px;
}
#div_lista {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 398px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Asistencia</h1>
<div id="link"><a href="#" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Parametros de busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="32%">Sede</td>
      <td width="68%"><?php echo CAMPO_SELECCION("sede","sede","",false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"');?></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre",$semestre_actual,false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"');?></td>
    </tr>
    <tr>
      <td>Year</td>
      <td><?php echo CAMPO_SELECCION("year","year",$year_actual,false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"');?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo CAMPO_SELECCION("carreras","carreras","",false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"', "id_carrera");?></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td><?php echo CAMPO_SELECCION("jornada","jornada","",false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"');?></td>
    </tr>
    <tr>
      <td>Grupo</td>
      <td><?php echo CAMPO_SELECCION("grupo","grupo","",false,'onchange="xajax_CARGA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);"');?></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td><div id="div_asignatura"><a href="#" class="button_R" onclick="xajax_CARGA_ASISTENCIA(document.getElementById('sede').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('id_carrera').value, document.getElementById('jornada').value, document.getElementById('grupo').value);">Actualizar</a></div></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_boton"></div>
<div id="div_lista"></div>
</body>
</html>