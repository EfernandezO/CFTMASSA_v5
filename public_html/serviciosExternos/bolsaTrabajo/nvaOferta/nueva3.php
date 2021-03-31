<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Informe Errores</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 141px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
	color: #FFFFFF;
}
.Estilo6 {color: #0080C0}
#Layer8 {	position:absolute;
	width:363px;
	height:20px;
	z-index:3;
	left: 157px;
	top: 314px;
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
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>
<body>
<h1 id="banner">Nueva - Noticia</h1>

<div id="link"></div>
<p>
  <?php
  if($_GET)
  {
      $error=$_GET["errorG"];
     echo"$error";
  }

?>
</p>
<div id="Layer1">
  <div align="center">
  <table width="465" border="0">
    <tr>
      <td width="459" bgcolor="#66CCFF"><div align="center" class="Estilo1">INFORMACION</div></td>
    </tr>
    <tr>
      <td height="63" bgcolor="#FFFFCC"><div align="center"><strong>La Noticia Fue Cargada Exitosamente </strong></div></td>
    </tr>
    <tr>
      <td bgcolor="#63CFFF">&nbsp;</td>
    </tr>
  </table>
  <br />
  <a href="../menu_noticias.php" class="button">Volver al Menu</a></div>
</div>
</body>
</html>