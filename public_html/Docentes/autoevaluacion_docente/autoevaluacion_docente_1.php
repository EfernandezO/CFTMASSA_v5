<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_docentes_periodo_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

require("../../../funciones/funciones_sistema.php");
$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){ $semestre_actual=2;}
	else{ $semestre_actual=1;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Autoevaluacion Docente</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 135px;
}
#div_boton {
	text-align:center;
	position:absolute;
	width:40%;
	height:33px;
	z-index:2;
	left: 30%;
	top: 290px;
}
#div_resultados {
	position:absolute;
	width:40%;
	height:41px;
	z-index:3;
	left: 30%;
	top: 314px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:78px;
	z-index:4;
	left: 30%;
	top: 431px;
	text-align: center;
}
</style>
<script language="javascript" type="text/javascript">
function enviar_formulario()
{
	document.getElementById('frm').submit();
}
</script>
</head>

<body>
<h1 id="banner">Autoevalucion Docente</h1>
<div id="link"><br />
<a href="../okdocente.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="resultados_evaluacion_docente_2.php" method="post" id="frm">
  <table width="45%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="3">Periodo</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="42%">Sede</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("sede","sede","",false,'onchange="xajax_VERIFICAR(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
    </tr>
    <tr>
      <td>Perido</td>
      <td width="58">semestre <?php echo CAMPO_SELECCION("semestre","semestre",$semestre_actual,false, 'onchange="xajax_VERIFICAR(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
      
      <td>year <?php echo CAMPO_SELECCION("year","year",$year_actual,false, 'onchange="xajax_VERIFICAR(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><a href="#" class="button" onclick="xajax_VERIFICAR(document.getElementById('sede').value, document.getElementById('semestre').value, document.getElementById('year').value);">Consultar</a></td>
      </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_resultados"></div>
<div id="apDiv2">Seleccione el periodo y sede para realizar su autoevaluacion.<br />
  solo puede realizar su autoevaluacion en los peridodos que tiene asignaciones registradas.
</div>
</body>
</html>