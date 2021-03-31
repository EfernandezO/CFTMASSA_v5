<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Resumen X Item</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 172px;
}
-->
</style>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo4 {font-size: 12px; font-style: italic; }
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Resum&eacute;n por Item</h1>
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
  <div id="apDiv1">
  <form action="resumen_x_item.php" method="post" name="frm" id="frm">
    <table width="50%" border="0" align="center">
    <thead>
      <tr>
        <th colspan="2"><div align="center"><span class="Estilo3">Configure Parametros de Busqueda</span></div></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="38%"><div align="left" class="Estilo4">Sede</div></td>
        <td width="62%"><div align="left" class="Estilo1">
          <?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede("fsede","",true); 
	  ?>
        </div></td>
      </tr>
      <tr>
        <td><div align="left" class="Estilo4">Fecha Inicio</div></td>
        <td><div align="left" class="Estilo1">
          <input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton1" id="boton1" value="..." />
        </div></td>
      </tr>
      <tr>
        <td><div align="left" class="Estilo4">Fecha Fin</div></td>
        <td><div align="left" class="Estilo1">
          <input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton2" id="boton2" value="..." />
        </div></td>
      </tr>
      <tr>
        <td><div align="left"></div></td>
        <td><div align="left"></div></td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="2" bgcolor="#e5e5e5"><div align="right">
          <input type="submit" name="button" id="button" value="Ver" />
        </div></td>
      </tr>
      </tfoot>
    </table>
    </form>
  </div>
  <p><a href="<?php echo $url;?>" class="button">Volver al Men&uacute; </a></p>
</div>
</body>
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
</html>