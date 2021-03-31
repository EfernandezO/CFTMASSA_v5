<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Creacion de Item</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_3.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:476px;
	height:115px;
	z-index:1;
	left: 203px;
	top: 82px;
}
-->
</style>

<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function CONFIRMAR()
{
	codigo=document.getElementById('codigo').value;
	nombre=document.getElementById('nombre').value;
	continuar=true;
	
	if((codigo=="")||(codigo==" "))
	{
		continuar=false;
		alert("ingrese un Codigo para este Item");
	}
	if((nombre=="")||(nombre==" "))
	{
		continuar=false;
		alert("ingrese un Nombre para este Item");
	}
	if(continuar)
	{
		c=confirm('Seguro(a) Desea Agregar este Item');
		if(c)
		{
			document.frm.submit();
		}
	}
}
</script>
<style type="text/css">
<!--
a:link {
	color: #006699;
	text-decoration: none;
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
-->
</style></head>
<?php
	$sede=$_SESSION["PRESUPUESTO"]["sede"];
	$fecha_presupuesto=$_SESSION["PRESUPUESTO"]["fecha"];
?>
<body>

<h1 id="banner">Administrador - Nuevo Item</h1>

<div id="link"><a href="../item_actuales.php" class="Estilo2">Volver al Seleccion</a></div>
<div id="apDiv1">
  	<form action="nuevo_item2.php" method="post" name="frm" id="frm">
    <table width="100%" border="1">
      <tr>
        <td colspan="2">&#9658;Datos de Item</td>
      </tr>
      <tr>
        <td>Sede</td>
        <td><select name="sede" id="sede">
          <option value="Talca">Talca</option>
          <option value="Linares">Linares</option>
        </select></td>
      </tr>
      <tr>
        <td width="53%">Movimiento</td>
        <td width="47%"><select name="movimiento" id="movimiento">
          <option value="I">Ingreso</option>
          <option value="E">Egreso</option>
        </select>        </td>
      </tr>
      <tr>
        <td>Codigo</td>
        <td><input type="text" name="codigo" id="codigo" /></td>
      </tr>
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre" id="nombre" /></td>
      </tr>
      <tr>
        <td>Descripcion</td>
        <td><textarea name="descripcion" id="descripcion"></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right">
          <input type="button" name="button" id="button" value="Grabar"  onclick="CONFIRMAR();"/>
        </div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>