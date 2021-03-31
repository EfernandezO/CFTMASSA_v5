<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Eliminacion de Pagos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:362px;
	height:184px;
	z-index:2;
	left: 72px;
	top: 117px;
}
.Estilo2 {color: #0080C0}
#Layer3 {	position:absolute;
	width:169px;
	height:17px;
	z-index:4;
	left: 170px;
	top: 313px;
}
a:link {
	text-decoration: underline;
	color: #6699FF;
}
a:visited {
	text-decoration: underline;
	color: #6699FF;
}
a:hover {
	text-decoration: none;
	color: #FF0000;
}
a:active {
	text-decoration: underline;
	color: #6699FF;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 68px;
	top: 104px;
}
.Estilo7 {font-size: 10px; font-weight: bold; }
.Estilo8 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
<script language="javascript">
function Confirmar()
{
	c=confirm('¿Seguro(a) que Desea Eliminar este Pago ?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Eliminacion Movimientos </h1>
<div id="Layer1">
<form action="borra_pago2.php" method="post" name="frm" id="frm">
  <table width="115%" sumary="">
  <caption></caption>
  <thead>
    <tr>
      <td colspan="2" scope="col" bgcolor="#CCFF00"><div align="center" class="Estilo8">Eliminacion de Movimiento Realizados </div></td>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="30%" height="32"><span class="Estilo7">Tipo Documento : </span></td>
      <td width="70%">
        <select name="tipo_doc" id="tipo_doc">
          <option value="Boleta" selected="selected">Boleta</option>
          <option value="Factura">Factura</option>
        </select>      </td>
    </tr>
    <tr class="odd">
      <td height="28"><span class="Estilo7">Tipo Movimiento </span></td>
      <td><select name="ftipo_mov" id="ftipo_mov" onchange="cargarContenido();">
        <option value="I" selected="selected">Ingreso</option>
        <option value="E">Egreso</option>
      </select></td>
    </tr class="odd">
    <tr class="odd">
      <td height="28"><span class="Estilo7">N&ordm; Documento: </span></td>
      <td>
        <input name="num_doc" type="text" id="num_doc" size="11" maxlength="10" />    </td>
    </tr class="odd">
    <tr class="odd">
      <td><span class="Estilo7">Sede:</span></td>
      <td><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
	</tbody>
	<tfoot>
	
    <tr>
      <td colspan="2"><div align="center">
        <input type="button" name="Submit" value="Eliminar"  onclick="Confirmar();"/>
      </div></td>
      </tr>
	  </tfoot>
  </table>
  </form>
</div>
<div id="Layer3">
  <div align="center"><a href="../index.php" class="Estilo2">Volver al Menu de Finanzas</a> </div>
</div>
</body>
</html>