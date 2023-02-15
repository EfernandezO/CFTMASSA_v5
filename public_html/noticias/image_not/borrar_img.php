<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Borra imagen</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:470px;
	height:115px;
	z-index:1;
	left: 226px;
	top: 160px;
}
#Layer2 {
	position:absolute;
	width:473px;
	height:115px;
	z-index:1;
	left: 226px;
	top: 159px;
}
#Layer3 {
	position:absolute;
	width:326px;
	height:24px;
	z-index:1;
	left: 314px;
	top: 395px;
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
-->
</style>
</head>

<body>
<h1 id="banner">Elimina  - Noticia</h1>
<?php
include("../../../funciones/funcion.php");
$img=str_inde($_GET["nombre"]);
if(@unlink($img))
{
   echo'
<div id="Layer1">
  <table width="470" border="0">
    <tr>
      <td width="464" bgcolor="#CCCCCC"><div align="center" class="Estilo1">INFORMACION</div></td>
    </tr>
    <tr>
      <td height="61" bgcolor="#CCFF33"><div align="center"><strong>La Noticia Fue Borrada Exitosamente </strong></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE">&nbsp;</td>
    </tr>
  </table>
</div>';
}
else
{
   echo'
</p>
<div id="Layer2">
  <table width="471" border="0">
    <tr>
      <td width="465" bgcolor="#FF0000"><div align="center" class="Estilo1">ERROR</div></td>
    </tr>
    <tr>
      <td height="61" bgcolor="#FFFFCC"><div align="center"><strong>La Imagen Relativa a La Noticia No fue Borrada .. </strong></div></td>
    </tr>
    <tr>
      <td bgcolor="#FF0000">&nbsp;</td>
    </tr>
  </table>
</div>
<p>';
  }
  ?>
</p>
<div id="Layer3"><a href="../edita_noticia/edita_not1.php" class="button">Volver a Edicion</a> &nbsp;&nbsp;<a href="../../Administrador/ADmenu.php"  class="button">Volver al Menu </a></div>
</body>
</html>
