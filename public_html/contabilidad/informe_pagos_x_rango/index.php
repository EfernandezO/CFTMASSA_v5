<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_pagos_X_Rango_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Detalle Pagos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:103px;
	z-index:2;
	left: 5%;
	top: 88px;
}
#Layer2 {
	position:absolute;
	width:103px;
	height:15px;
	z-index:3;
	left: 556px;
	top: 74px;
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
#link {
	text-align: right;
	padding-right: 10px;
}
#saldo_anterior {
	position:absolute;
	width:207px;
	height:88px;
	z-index:3;
	left: 781px;
	top: 188px;
	visibility: hidden;
}
#cheques {
	position:absolute;
	width:382px;
	height:115px;
	z-index:4;
	left: 568px;
	top: 123px;
}
#apDiv1 {
	position:absolute;
	width:760px;
	height:43px;
	z-index:5;
	left: 20px;
	top: 223px;
}
#apDiv1 #cheque_detalle {
	border: thin solid #FF0000;
}
#apDiv1 #div_resultados {
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:32px;
	z-index:4;
	left: 30%;
	top: 308px;
	text-align: center;
}
-->
</style>

</head>

<body>
<h1 id="banner">Administrador - Informe Caja </h1>
 <?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../index.php";	
	}
?>
<div id="link">
  <div id="saldo_anterior"></div><br />

<a href="<?php echo $url;?>" class="button">Volver al Men&uacute; </a></div>
<div id="Layer1">
	<form action="busca_pagos.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="4" ><strong>-&gt;Resum&eacute;n Ingresos Egresos</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="78">Fecha inicio </td>
      <td width="176"><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
      <td width="105">&nbsp;</td>
      <td width="175"><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede("fsede", "", true); 
	  ?></td>
      <td>Movimientos</td>
      <td>
      <select name="movimiento" id="movimiento">
      <option value="todos">Todos</option>
      <option value="I">ingresos</option>
      <option value="E">egresos</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a href="genera_excel.php"></a></td>
      <td><input name="btn_resetiar" type="button" id="btn_resetiar"  onclick="resetiar();" value="Reset"/>
      <input name="boton" type="submit" id="boton" onclick="xajax_busca_pagos(document.getElementById('fecha_inicio').value,document.getElementById('fecha_fin').value, document.getElementById('fsede').value, document.getElementById('tipo_doc').value);return false;" value="Ver"/></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Genera Un Informe de todos los Movimientos realizados<br />
  en el tiempo transcurrido entre las dos<br />
  Fechas ingresadas
</div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>