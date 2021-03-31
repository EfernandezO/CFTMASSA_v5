<?php
//-----------------------------------------//
	require("../../../../../OKALIS/seguridad.php");
	require("../../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", true);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registro_personal_ind_server.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"MARCA_TIME");
////////////////////////////////////////////
if($_GET)
{
	$fecha_actual=base64_decode($_GET["fecha"]);
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
}
else
{
	$fecha_actual=date("Y-m-d");
	$id_funcionario=$_SESSION["USUARIO"]["id"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../funciones/codificacion.php");?>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css">
<title>Asistencia</title>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
#div_resultados {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 135px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:18px;
	z-index:2;
	left: 30%;
	top: 308px;
	text-align: center;
}
#div_informacion {
	position:absolute;
	width:30%;
	height:34px;
	z-index:2;
	left: 5%;
	top: 73px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	if(continuar)
	{
		c=confirm('¿Seguro(a) Desea Actualizar su Clave?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>

</head>

<body onload="xajax_CARGA_HORARIO(<?php echo $id_funcionario?>,'<?php echo $fecha_actual;?>')">
<h1 id="banner">Docentes - Asistencia</h1>
<div id="link"><br>
<a href="../../../../okdocente.php" class="button">Volver al Menu</a></div>
 <div id="div_resultados">
 </div>
 <div id="div_informacion"></div>
</body>
</html>