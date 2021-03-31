<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_proveedores_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////////////

if(isset($_GET["modo"]))
{$modo=$_GET["modo"];}
else{ $modo="normal";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>nvo Proveedores</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">

<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:1;
	left: 5%;
	top: 123px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:28px;
	z-index:2;
	left: 5%;
	top: 372px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:49px;
	z-index:3;
	left: 30%;
	top: 449px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	rut=document.getElementById('proveedor_rut').value;
	razon_social=document.getElementById('proveedor_razon_social').value;
	direccion=document.getElementById('proveedor_direccion').value;
	ciudad=document.getElementById('proveedor_ciudad').value;
	
	
	if((rut=="")||(rut==" "))
	{
		continuar=false;
		alert('Ingrese Rut');
	}
	if((razon_social=="")||(razon_social==" "))
	{
		continuar=false;
		alert('Ingrese Razon Social');
	}
	if((direccion=="")||(direccion==" "))
	{
		continuar=false;
		alert('Ingrese Direccion');
	}
	if((ciudad=="")||(ciudad==" "))
	{
		continuar=false;
		alert('Ingrese Ciudad');
	}
	
	
	if(continuar)
	{
		c=confirm('Seguro(a) desea Grabar este Proveedor..?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Nuevo Proveedores</h1>
<div id="link"><br />
<a href="../listar_proveedores.php" class="button">Volver a Proveedores</a></div>
<div id="apDiv1">
<form action="nvo_proveedor_2.php" method="post" id="frm">
  <table width="60%" border="1" align="center" id="proveedor">
  <thead>
  	<tr>
      <tH colspan="2">Proveedores        </tH	>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="41%">Rut</td>
      <td width="59%"><label for="proveedor_rut"></label>
        <input type="text" name="proveedor_rut" id="proveedor_rut"  onblur="xajax_BUSCA_PROVEEDOR(this.value, document.getElementById('proveedor_razon_social').value, document.getElementById('proveedor_direccion').value, document.getElementById('proveedor_ciudad').value);return false;"/></td>
    </tr>
    <tr>
      <td>Razon Social</td>
      <td><label for="proveedor_razon_social"></label>
        <input type="text" name="proveedor_razon_social" id="proveedor_razon_social" /></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td><label for="proveedor_direccion"></label>
        <input type="text" name="proveedor_direccion" id="proveedor_direccion" /></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><label for="proveedor_ciudad"></label>
        <input type="text" name="proveedor_ciudad" id="proveedor_ciudad" /></td>
    </tr>
    <tr>
      <td>Telefono</td>
      <td><label for="proveedor_telefono"></label>
        <input type="text" name="proveedor_telefono" id="proveedor_telefono" />
        <input name="modo" type="hidden" id="modo" value="<?php echo $modo;?>" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Grabar Proveedor</a></div>
<div id="apDiv3">Graba manualmente Proveedores, para tener sus datos<br />
disponibles en la creacion de ordenes de compra<br />
y facturas.
</div>
</body>
</html>