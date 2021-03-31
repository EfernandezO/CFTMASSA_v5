<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(isset($_GET["id_factura"]))
	{
		$id_factura=$_GET["id_factura"];
		if(is_numeric($id_factura))
		{$continuar=true;}
		else
		{$continuar=false;}
	}
	else
	{ $continuar=false;}
}
else
{$continuar=false;}


if($continuar)
{
	require("../../../../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM facturas WHERE id='$id_factura' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
			$F=$sqli->fetch_assoc();
			$F_valor=$F["valor"];
			$F_saldo=$F["saldo"];
			$F_abono=$F["abono"];
		$sqli->free();	
	$conexion_mysqli->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Factura</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 128px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	saldo=document.getElementById('saldo').value;
	
	if((saldo=="")||(saldo==" "))
	{
		continuar=false;
		alert("Ingrese valor a a pagar");
	}
	
	if(continuar)
	{
		document.getElementById('frm').submit();
	}
}
</script> 
</head>

<body>
<h1 id="banner">Administrador - Cambio Condicion Factura</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="cambio_condicion_2.php" method="post" id="frm">
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Confirmar
        <input name="id_factura" type="hidden" id="id_factura" value="<?php echo $id_factura;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="41%">id_factura</td>
      <td width="59%"><?php echo $id_factura;?></td>
    </tr>
    <tr>
      <td>Valor</td>
      <td><?php echo $F_valor;?>
        <input name="valor" type="hidden" id="valor" value="<?php echo $F_valor;?>" /></td>
    </tr>
    <tr>
      <td>Saldo</td>
      <td><label for="saldo"></label>
        <input name="saldo" type="text" id="saldo" value="<?php echo $F_saldo;?>" /></td>
    </tr>
    <tr>
      <td height="45" colspan="2" align="center"><a href="#" class="button" onclick="CONFIRMAR();">Si, Seguro(a)</a></td>
    </tr>
    </tbody>
  </table>
 </form> 
<?php }?>  
</div>
</body>
</html>