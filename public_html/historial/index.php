<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("revision_historial_general_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("historial_server.php");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ID_FUNCIONARIO");
////////////////////////////////////////////
$fecha_actual=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<?php $xajax->printJavascript(); ?> 
<title>CFTMASS | historial</title>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	fecha=document.frm.fecha_X.value;
	if(fecha=="")
	{
		alert('ingrese fecha a consultar');
	}
	else
	{
		document.frm.submit();
	}
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:40%;
	height:32px;
	z-index:1;
	left: 30%;
	top: 128px;
	text-align: center;
}
#Layer2 {
	position:absolute;
	width:92px;
	height:19px;
	z-index:2;
	left: 285px;
	top: 213px;
}
a:link {
	color: #6699FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:41px;
	z-index:2;
	left: 30%;
	top: 261px;
	text-align: center;
}
#div_funcionario {
}
-->
</style>
</head>

<body onload="xajax_BUSCA_ID_FUNCIONARIO(document.getElementById('fecha_X').value);">
<h1 id="banner">Administrador - Historial de Eventos</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../Administrador/ADmenu.php";	
}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu </a></div>
<div id="Layer1">
 
  <form action="listador_historia.php" method="post" name="frm" id="frm">
<table width="100%" border="0" align="center">
<thead>
<tr>
	<th colspan="2">Seleccione la Fecha que Desea Consultar </th>
</tr>
</thead>
<tbody>
  <tr>
    <td>Fecha</td>
    <td> <input name="fecha_X" type="text" id="fecha_X" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="true"/>
      <input type="button" name="boton" id="boton" value="..." /></td>
  </tr>
  <tr>
    <td>Usuario</td>
    <td><div id="div_funcionario"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="#" class="button_R" onclick="CONFIRMAR();">Continuar</a></td>
  </tr>
  </tbody>
    </table>
  </form>
</p>
</div>
<h1>&nbsp; </h1>
<div id="apDiv1">Lista Todos Los Registros de Eventos del Sistema que<br />
  se generan en una fecha Determinada.
</div>
</body>
  <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false,
		   onSelect     : actualizar,
          onTimeChange : actualizar
      });
	  
	  function actualizar() 
	  {
          xajax_BUSCA_ID_FUNCIONARIO(document.getElementById('fecha_X').value);
		  return false;  
      };
      cal.manageFields("boton", "fecha_X", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
</html>