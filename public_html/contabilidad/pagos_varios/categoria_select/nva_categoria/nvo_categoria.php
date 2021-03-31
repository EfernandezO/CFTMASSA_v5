<?php include ("../../../../SC/seguridad.php");?>
<?php include ("../../../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>agrega documento</title>
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla.css">

<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:388px;
	height:115px;
	z-index:2;
	left: 108px;
	top: 136px;
}
#titulo #link {
	text-align: right;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 101px;
	top: 117px;
}
a:link {
	text-decoration: none;
	color: #6699FF;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
#titulo #link {
	width: 95%;
}
#Layer3 {
	position:absolute;
	width:143px;
	height:49px;
	z-index:3;
	left: 450px;
	top: 74px;
}
.Estilo5 {
	font-size: small;
	font-weight: bold;
}
.Estilo6 {font-size: small}
-->
</style>
<script language="javascript">
function Verificar()
{
	nvo=document.getElementById('fnvo_doc').value;
	if(nvo!="")
	{
		c=confirm('Agregar este nuevo Documento');
		if(c==true)
		{
			document.frm.submit();
		}
	}
	else
	{
		alert('ingrese un nuevo tipo de documento');
	}
}
</script>
</head>
<?php
if($_GET)
{
	$error=$_GET["error"];
	switch ($error)
	{
		case "0":
			$msj="Doc. Agregados Correctamente";
		 break;
		case "1":
			$msj="Datos Incorrectos";
			break;
		case "2":
			$msj="Fallo al insertar Datos";
			break;
		case "3":
			$msj="Repetida. valor presente en BBDD";
			break;		
	}
	
}
?>
<body>
<h1 id="banner">Administrador - Creaci&oacute;n de Elementos </h1>
  <div id="Layer3">
<div align="right"><a href="../del_categoria/borra_elemento.php">Eliminar un Elemento</a><br />
        <a href="../../../index.php">Volver al Menu</a><br />
      <a href="../../form_pago1.php">Volver Registro de Mov </a></div>
</div>
</div>
<div id="Layer1">
<form action="graba_categoria_select.php" method="post" name="frm" id="frm">
  <table width="124%" sumary="formulario">
  <caption>Agrega nuevos elementos a la categoria de "tipo de Documento"</caption>
  <thead>
    <tr>
      <td scope="col" colspan="2" bgcolor="#CCFF66"><span class="Estilo5">Nvo tipo de Documento </span></td>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="65%"><span class="Estilo6">Tipo</span></td>
      <td width="35%"><select name="ftipo_mov" id="ftipo_mov">
        <option value="I" selected="selected">Ingreso</option>
        <option value="E">Egreso</option>
      </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo6">Nvo Documento</span></td>
      <td><input name="fnvo_doc" type="text" id="fnvo_doc" size="20" maxlength="20" /></td>
    </tr>
    <tr class="odd">
      <td class="Estilo6">Mostrar en pago de Certificado y Otros</td>
      <td><select name="permitir" id="permitir">
        <option value="ON">SI</option>
        <option value="OFF" selected="selected">NO</option>
      </select>
      </td>
    </tr>
    <tr class="odd">
      <td colspan="2"><div align="center">
        
        <input type="button" name="Submit" value="Agregar"  onclick="Verificar();"/>
      
      </div></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="2"><div align="center" class="Estilo6"><em>&nbsp;<?php echo $msj;?></em></div></td>
    </tr>
	</tfoot>
  </table>
  </form>
</div>
</body>
</html>