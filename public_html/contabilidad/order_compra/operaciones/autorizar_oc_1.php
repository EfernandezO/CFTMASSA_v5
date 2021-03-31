<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Autorizar OC</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 59px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:32px;
	z-index:2;
	left: 5%;
	top: 215px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('¿Seguro(a) Desea Autorizar esta Orden de Compra..?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>
<?php
$action="";
if(isset($_GET["id_oc"]))
{
	$OC_id=base64_decode($_GET["id_oc"]);
	if(is_numeric($OC_id)){ $continuar=true; $action="autorizar_oc_2.php";}
	else{ $continuar=false;}
}
else
{ $continuar=false;}
?>
<body>
<h1 id="banner">Autorizar - Orden de Compra</h1>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="51%">ID ORDEN COMPRA</td>
      <td width="49%"><?php echo $OC_id;?><input name="oc_id" type="hidden" value="<?php echo $OC_id;?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2" align="center"><a href="#" class="button_G" onclick="CONFIRMAR();">Autorizar</a></div>
</body>
</html>