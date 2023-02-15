<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	

//////////////////////XAJAX/////////////////
require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("buscador_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ALUMNO");
////////////////////////////////////////////?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seleccion de Alumno</title>
<?php $xajax->printJavascript(); ?> 
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
	<style type="text/css">
<!--
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
#ajax_resultado {
	position:absolute;
	width:787px;
	height:115px;
	z-index:1;
	left: 101px;
	top: 236px;
}
.Estilo2 {font-size: 12px}
.Estilo4 {font-size: 12px; font-weight: bold; }
-->
    </style></head>
<body>
	<h1 id="banner">Administrador - Men&uacute; Observaciones</h1>
    
	<div id="link">
	  <div align="right"><a href="../index.php">Volver al Menu</a></div>
	</div>
<h3>Administre las Observaciones</h3>
<div id="main">
  <table width="350" border="1">
  <thead>
    <tr>
      <td colspan="2"><span class="Estilo4">Seleccion de Alumno</span></td>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
      <td width="50%"><span class="Estilo2">Sede</span></td>
      <td width="50%"><span class="Estilo2">
        <?php include("../../../funciones/funcion.php");echo selector_sede("sede"); ?>
      </span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Carrera</span></td>
      <td><span class="Estilo2">
        <select name="carrera" id="carrera" onchange="xajax_BUSCA_ALUMNO(document.getElementById('sede').value, document.getElementById('carrera').value, document.getElementById('nivel').value);return false;">
          <?php 

    include("../../../funciones/conexion.php");
   
   $_SESSION["proviene"]="dconcentracion";
   $res="SELECT carrera FROM carrera where id >= 0";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) {


  $nomcar=$row["carrera"];
  
?>
          <option>
          <?php 
  echo $nomcar;
}
mysql_free_result($result); 
mysql_close($conexion); 
 ?>
          </option>
        </select>
      </span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Nivel</span></td>
      <td><span class="Estilo2">
        <select name="nivel" id="nivel" onchange="xajax_BUSCA_ALUMNO(document.getElementById('sede').value, document.getElementById('carrera').value, document.getElementById('nivel').value);return false;">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </span></td>
    </tr>
    <tr class="odd">
      <td colspan="2"><div align="right">
        <input type="button" name="btn" id="btn" value="Ver"  onClick="xajax_BUSCA_ALUMNO(document.getElementById('sede').value, document.getElementById('carrera').value, document.getElementById('nivel').value);return false;"/>
      </div></td>
    </tr>
    </tbody>
  </table>
  <div id="ajax_resultado"></div>
</div>
	
</body>
</html>