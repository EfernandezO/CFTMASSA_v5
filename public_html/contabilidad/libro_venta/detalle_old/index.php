<?php require ("../../../SC/seguridad.php");?>
<?php require ("../../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Libro de Venta</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:543px;
	height:153px;
	z-index:1;
	left: 70px;
	top: 96px;
}
.Estilo1 {	font-size: 12px;
	font-style: italic;
}
.Estilo2 {font-size: 12px}
.Estilo3 {	font-size: 12px;
	font-weight: bold;
}
#link {	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Finanzas Libro de Venta</h1>
<div id="apDiv1">
  <form action="libro_venta_1.php" method="post" name="frm" id="frm">
    <table width="76%" border="0">
      <thead>
        <tr>
          <td colspan="2" bgcolor="#EBE5D9"><span class="Estilo3">Parametros</span></td>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="25%" bgcolor="#F7F4EE"><span class="Estilo1">Fecha Inicio</span></td>
          <td width="75%" bgcolor="#F7F4EE"><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
              <input type="button" name="boton1" id="boton1" value="..." /></td>
        </tr>
        <tr class="odd">
          <td bgcolor="#F7F4EE"><span class="Estilo1">Fecha Fin</span></td>
          <td bgcolor="#F7F4EE"><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
              <input type="button" name="boton2" id="boton2" value="..." /></td>
        </tr>
        <tr class="odd">
          <td height="22" bgcolor="#F7F4EE"><span class="Estilo2">Sede</span></td>
          <td bgcolor="#F7F4EE"><?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2" bgcolor="#EBE5D9"><div align="right">
            <input type="submit" name="button" id="button" value="Consultar" />
          </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../../index.php";	
	}
?>
<div id="link"><a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
</body>
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]></script>
</html>