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
if(isset($_GET["id_proveedor"]))
{
	$id_proveedor=$_GET["id_proveedor"];
	if(is_numeric($id_proveedor))
	{
		if($id_proveedor>0){ $continuar=true;}
		else{ $continuar=false;}
	}
	else
	{$continuar=false;}
}
else
{ $continuar=false;}
//-----------------------------------------------------------------------//
if($continuar)
{
	require('../../../../funciones/conexion_v2.php');
		$cons="SELECT * FROM proveedores WHERE id_proveedor='$id_proveedor' LIMIT 1";
		$sql=$conexion_mysqli->query($cons);
		$DP=$sql->fetch_assoc();
			$proveedor_id=$DP["id_proveedor"];
			$proveedor_rut=$DP["rut"];
			$proveedor_razon_social=$DP["razon_social"];
			$proveedor_direccion=$DP["direccion"];
			$proveedor_ciudad=$DP["ciudad"];
			$proveedor_telefono=$DP["telefono"];
		$sql->free();	
	mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{ header("location: ../listar_proveedores.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Edita Proveedores</title>
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
	width:40%;
	height:28px;
	z-index:2;
	left: 30%;
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
		c=confirm('Seguro(a) desea Editar este Proveedor..?');
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
<form action="edita_proveedor_2.php" method="post" id="frm">
  <table width="30%" border="1" align="center" id="proveedor">
  <thead>
  	<tr>
      <tH colspan="2">Proveedores        
        <input name="proveedor_id" type="hidden" id="proveedor_id" value="<?php echo $proveedor_id;?>" /></tH	>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="41%">Rut</td>
      <td width="59%"><label for="proveedor_rut"></label>
        <input type="text" name="proveedor_rut" id="proveedor_rut" value="<?php echo $proveedor_rut;?>"/></td>
    </tr>
    <tr>
      <td>Razon Social</td>
      <td><label for="proveedor_razon_social"></label>
        <input type="text" name="proveedor_razon_social" id="proveedor_razon_social" value="<?php echo $proveedor_razon_social;?>"/></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td><label for="proveedor_direccion"></label>
        <input type="text" name="proveedor_direccion" id="proveedor_direccion"  value="<?php echo $proveedor_direccion;?>"/></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><label for="proveedor_ciudad"></label>
        <input type="text" name="proveedor_ciudad" id="proveedor_ciudad" value="<?php echo $proveedor_ciudad;?>"/></td>
    </tr>
    <tr>
      <td>Telefono</td>
      <td><label for="proveedor_telefono"></label>
        <input type="text" name="proveedor_telefono" id="proveedor_telefono" value="<?php echo $proveedor_telefono?>"/></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Edita Proveedor</a></div>
<div id="apDiv3">Edita manualmente Proveedores, para tener sus datos<br />
disponibles en la creacion de ordenes de compra<br />
y facturas.
</div>
 <?php
  $msj="";
  $img=array(""=>"");
  $tipo_img="";
  	if($_GET)
	{
		$img["buena"]='<img src="../../../BAses/Images/ok.png" alt="ok" />';
		$img["mala"]='<img src="../../../BAses/Images/b_drop.png" alt="X" />';
		$error=$_GET["error"];
		switch($error)
		{
			case"PE1":
				$msj="Error al modificar Proveedor";
				$tipo_img="mala";
				break;
			case"PE2":
				$msj="Error al modificar Proveedor, Rut ya esta en uso";
				$tipo_img="mala";
				break;
			case"PE3":
				$msj="Error al modificar Proveedor, Faltan Datos";
				$tipo_img="mala";
				break;

				
		}
	}
  ?>
  <div id="msj">*<?php echo $msj;?> <?php echo $img[$tipo_img];?>*</div>
</body>
</html>