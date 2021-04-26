<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Verificador de Rut</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 168px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:61px;
	z-index:2;
	left: 30%;
	top: 294px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Verificador Rut</h1>
<div id="link"><br />
<a href="../../Administrador/ADmenu.php" class="button">Volver al Menu</a>
  <div id="apDiv1">
  <form action="verificador_rut2.php" method="post" id="frm">
    <table width="20%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="2" align="center">Rut</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Rut</td>
        <td>
          <input name="rut" type="text" id="rut" size="15" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="button" id="button" value="continuar" /></td>
      </tr>
      </tbody>
    </table>
    </form>
  </div>
</div>
<div id="apDiv2">Ingrese el Rut sin Puntos y con guion<br />
  EJ. 1234567-0
</div>
</body>
</html>