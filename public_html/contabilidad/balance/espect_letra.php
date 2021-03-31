<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Espectativas de Ingreso Letras</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:106px;
	z-index:2;
	left: 115px;
	top: 162px;
}
#Layer2 {
	position:absolute;
	width:467px;
	height:47px;
	z-index:1;
	left: 33px;
	top: 83px;
}
#Layer3 {
	position:absolute;
	width:236px;
	height:26px;
	z-index:1;
	left: 282px;
	top: 56px;
}
a:link {
	text-decoration: none;
	color: #006699;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#Layer4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 104px;
	top: 147px;
}
#Layer5 {
	position:absolute;
	width:393px;
	height:61px;
	z-index:3;
	left: 96px;
	top: 86px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
</head>

<body>
<h1 id="banner">Proyecciones - Ingresos </h1>

<div id="link"><a href="../index.php">Volver al Menu</a></div>
<div id="Layer5">
  <p align="center"><strong>Genera Proyecci&oacute;nes de los Posibles Ingresos que se generar&aacute;n por medio de las letras de pago.<br />
  Considera la fecha de caducidad de las Letras. </strong></p>
</div>
<div id="Layer4"><img src="../../BAses/Images/barrav.jpg" alt="Ingrese una opcion" width="376" height="174" /></div>
<div id="Layer1">
<form action="espect_letra2.php" method="post" name="frm" id="frm">
  <table width="350" border="0">
    <tr>
      <td colspan="2" bgcolor="#CCFF00"><div align="center"><strong>Selecciones</strong> <strong>una Opci&oacute;n </strong></div></td>
    </tr>
    <tr>
      <td width="141"><strong>A&ntilde;o</strong></td>
      <td width="199">
        <input name="ano_BB" type="text" id="ano_BB" size="10" maxlength="4"  value="<?php echo date("Y");?>"/>
      </td>
    </tr>
    <tr>
      <td><strong>Mes</strong></td>
      <td>
        <select name="opcion_M" id="opcion_M">
          <option value="T" selected="selected">Todos</option>
          <option value="1">Enero</option>
          <option value="2">Febrero</option>
          <option value="3">Marzo</option>
          <option value="4">Abril</option>
          <option value="5">Mayo</option>
          <option value="6">Junio</option>
          <option value="7">Julio</option>
          <option value="8">Agosto</option>
          <option value="9">Septiembre</option>
          <option value="10">Octubre</option>
          <option value="11">Noviembre</option>
          <option value="12">Diciembre</option>
        </select>
     </td>
    </tr>
	<tr>
	<td><strong>Sede</strong></td>
	<td><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede();
	 ?> 
	  </td>
	</tr>
    <tr>
      <td colspan="2"><div align="center">
  
        <input type="submit" name="Submit" value="Consultar&gt;&gt;" />
      
      
        <input type="reset" name="Submit2" value="Restablecer" />
        
      </div></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  </form>
</div>

</body>
</html>
