<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Plantillas de Impresion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 76px;
}
a:link {
	color: #069;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #069;
}
a:hover {
	text-decoration: underline;
	color: #F00;
}
a:active {
	text-decoration: none;
	color: #069;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Plantillas de Impresi&oacute;n</h1>
<div id="link"><br />
<a href="../../Administrador/ADmenu.php" class="button">Volver al Menu</a>
  <div id="apDiv1">
  <form action="lomo_archivador_2.php" method="post" id="frm" target="_blank">
    <table width="55%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="2">Texto para Lomos de Archivadores</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Texto1</td>
        <td>
          <input name="texto[]" type="text" id="texto[]2" size="50" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[1][]" id="subtexto1" />
          <br />
</td>
      </tr>
      <tr>
        <td>Texto2</td>
        <td>
          <input name="texto[]" type="text" id="texto[]3" size="50" />
           <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[2][]" id="subtexto1" /></td>
      </tr>
      <tr>
        <td>Texto3</td>
        <td>
          <input name="texto[]" type="text" id="texto[]4" size="50" />
           <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[3][]" id="subtexto1" /></td>
      </tr>
      <tr>
        <td>Texto4</td>
        <td>
          <input name="texto[]" type="text" id="texto[]" size="50" />
           <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
           <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
          <br />
          <input type="text" name="subtexto[4][]" id="subtexto1" />
          </td>
      </tr>
      <tr>
        <td>Tama&ntilde;o Letra</td>
        <td><label for="size_letra"></label>
          <select name="size_letra" id="size_letra">
            <option value="8">8</option>
            <option value="10">10</option>
            <option value="12">12</option>
            <option value="14">14</option>
            <option value="16" selected="selected">16</option>
            <option value="18">18</option>
            <option value="20">20</option>
            <option value="24">24</option>
            <option value="30">30</option>
          </select></td>
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
</body>
</html>