<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Ingresos_totales_X_mes_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////////////
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_datos_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PAGOS");
//////////////////////////////////////////////////////////////
define("DEBUG", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>ingresos X Mes</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 78px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:51px;
	z-index:1;
	left: 60%;
	top: 18px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Ingresos X Mes</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Men&uacute; </a></div>
<div id="apDiv1">
  <table width="50%" border="1">
  <thead>
    <tr>
      <th colspan="2">A&ntilde;o a Consultar</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>A&ntilde;o</td>
      <td>
        <select name="year" id="year">
        <?php
		$year_actual=date("Y");
		$year_inicial=(date("Y")-10);
		for($a=$year_inicial;$a<=$year_actual;$a++)
		{
			if($a==$year_actual)
			{ echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';}
			else{ echo'<option value="'.$a.'">'.$a.'</option>';}
		}
        ?>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><a href="#" class="button" onclick="xajax_BUSCA_PAGOS(document.getElementById('year').value);return false;">Buscar</a></td>
      </tr>
    </tbody>
  </table>
  <div id="apDiv2">Muestra Resumen Mes a mes de Los Ingresos en cada una de las sede y un totalizado final.</div>
<div id="div_datos">....</div>
  <p>&nbsp;</p>
</div>
</body>
</html>