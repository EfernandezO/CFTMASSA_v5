
<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="externo";
	OKALIS($lista_invitados);
//-----------------------------------------// 
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_datos_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"DEUDA_Y_MATRICULA");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Consulta alumnos Deuda matricula</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:30%;
	height:93px;
	z-index:1;
	left: 5%;
	top: 96px;
}
#apDiv2 {
	position:absolute;
	width:30%;
	height:166px;
	z-index:2;
	left: 5%;
	top: 218px;
	border: thin solid #06F;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe Consulta Alumno</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$url_menu="../index.php";
			break;
		case"externo":
			$url_menu="../../Administrador/menu_externos/index.php";
			break;	
			
	}
?>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver al menu</a></div>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="33%"><label for="rut">Rut</label></td>
      <td width="67%"><input type="text" name="rut" id="rut" />
        <a href="#" class="button_R" onclick="xajax_DEUDA_Y_MATRICULA(document.getElementById('rut').value); return false;">Consulta</a></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">...</div>
</body>
</html>