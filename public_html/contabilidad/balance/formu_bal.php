<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Genera Balance</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:373px;
	height:246px;
	z-index:3;
	left: 76px;
	top: 112px;
}
.Estilo1 {
	font-size: 14px;
	font-weight: bold;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:276px;
	z-index:2;
	left: 67px;
	top: 103px;
}
#Layer3 {
	position:absolute;
	width:161px;
	height:10px;
	z-index:4;
	left: 484px;
	top: 69px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo2 {color: #0080C0}
#Layer4 {
	position:absolute;
	width:119px;
	height:21px;
	z-index:5;
	left: 114px;
	top: 389px;
}
#Layer5 {
	position:absolute;
	width:200px;
	height:27px;
	z-index:5;
	left: 158px;
	top: 65px;
}
#Layer6 {
	position:absolute;
	width:153px;
	height:14px;
	z-index:6;
	left: 209px;
	top: 384px;
}
.Estilo3 {color: #008040}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<?php
$fecha_actual=date("Y-m-d");
?>
</head>

<body>
<h1 id="banner">Administrador- Registro de Ingresos/Egresos </h1>
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
<div id="link"><a href="<?php echo $url;?>" class="Estilo2">Volver al Menu</a></div>
<div id="Layer6"><a href="proyecciones_v2/proyeccion_1.php" class="Estilo3">Proyecciones V2</a> </div>
<div id="Layer5">
  <div align="center"><strong>Genera un Balace seg&uacute;n las opciones seleccionadas </strong></div>
</div>
<p>&nbsp;</p>

<div id="Layer1">
<form action="Genera_bal1.php" method="post" name="frm" target="_blank" id="frm">
  <table width="379" height="112" border="0">
    <tr>
      <td colspan="2" bgcolor="#EBE5D9"><div align="center" class="Estilo1">Generador de Balance </div></td>
    </tr>
	<tr>
      <td colspan="2" bgcolor="#F7F4EE">&nbsp;</td>
    </tr>
    <tr>
      <td width="123" bgcolor="#F7F4EE"><strong>Fecha:</strong></td>
      <td width="246" bgcolor="#F7F4EE"><input name="fecha_corte" type="text" id="fecha_corte" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="true"/>
<input type="button" name="boton" id="boton" value="..."/></td>
    </tr>
	<tr>
	<td bgcolor="#F7F4EE"><strong>Tipo Documento </strong></td>
	<td bgcolor="#F7F4EE"><select name="ftipo_doc" id="ftipo_doc">
        <?php
		 	 include("../../../funciones/conexion.php");
		  	$cons="SELECT contenido, tipo FROM parametros WHERE seccion='finanzas' ORDER by tipo";
			//echo"---> $cons<br>";
			$sql=mysql_query($cons)or die(mysql_error());
			$num_reg=mysql_num_rows($sql);
			//echo"$num_reg<br>";
			if($num_reg>0)
			{
				echo'<option value="T">Todos los tipos</option>';
				while($A=mysql_fetch_assoc($sql))
				{
					$contenido=$A["contenido"];
					$tipo=$A["tipo"];
					//echo"$contenido<br>";
					echo'<option value="'.$contenido.'">'.$tipo.' -> '.$contenido.'</option>';
				}
			}
			else
			{
				echo'<option>Sin Elementos</option>';
			}
			mysql_free_result($sql);
			mysql_close($conexion);
		  ?>
      </select></td>
	</tr>
    <tr>
      <td colspan="2" bgcolor="#F7F4EE"><label>
        <input name="ftipo_cons" type="radio" value="D" checked="checked" title="Solo Transacciones Realizadas el dia especifico" />
      Del dia </label></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F4EE"><label>
        <input name="ftipo_cons" type="radio" value="M" title="Transacciones Realizadas desde Principio de mes a la fecha Indicada"/>
        Del Mes 
      </label></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F4EE"><label>
        <input name="ftipo_cons" type="radio" value="A"  title="Transacciones Realizadas desde Inicio de Año a La fecha"/>
        Del A&ntilde;o 
      </label></td>
    </tr>
    <tr>
      <td bgcolor="#F7F4EE"><strong>Sede:</strong></td>
      <td bgcolor="#F7F4EE"><label></label>
	  <?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
	  </td>
    </tr>
	<tr>
	<td bgcolor="#F7F4EE"><label>
	  <input name="fmuestra" type="radio" value="Tot" title="Ver Solo Totales" />
	Total</label></td>
	<td bgcolor="#F7F4EE"><label>
	  <input name="fmuestra" type="radio" value="Det" checked="checked" title="Ver en Detalle" />
	Detalle</label></td>
	</tr>
    <tr>
      <td colspan="2" bgcolor="#EBE5D9"><div align="center">
        <label>
        <input type="submit" name="Submit" value="Continuar &#9658;" />
        </label>
      </div></td>
    </tr>
  </table>
  </form>
   <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_corte", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
</div>
<p>&nbsp;</p>
</body>
</html>
