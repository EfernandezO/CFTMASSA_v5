<?php
//-----------------------------------------//
	require("../../seguridad.php");
	require("../../okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_GET)
{
	if(isset($_GET["clave_archivo"]))
	{$clave_archivo=base64_decode($_GET["clave_archivo"]); $continuar=true;}
	else{ $continuar=false;}
	
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css"/>
<title>Okalis | Agrega Clave de Archivo</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:168px;
	z-index:1;
	left: 5%;
	top: 108px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:19px;
	z-index:2;
	left: 30%;
	top: 333px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:36px;
	z-index:3;
	left: 5%;
	top: 369px;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	clave_archivo=document.getElementById('clave_archivo').value;
	nombre_modulo=document.getElementById('nombre_modulo').value;
	clave_registro=document.getElementById('clave_registro').value;
	
	if((clave_archivo=="")||(clave_archivo==" "))
	{
		alert("Ingrese clave de archivo");
		continuar=false;
	}
	
	if((nombre_modulo=="")||(nombre_modulo==" "))
	{
		alert("Ingrese Nombre de Modulo");
		continuar=false;
	}
	
	if((clave_registro=="")||(clave_registro==" "))
	{
		alert("Ingrese Clave de Registro");
		continuar=false;
	}
	
	if(continuar)
	{
		c=confirm("Seguro(a) Desea Registrar este Modulo...??");
		if(c){document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Agrega Clave Archivo</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="registra_clave_archivo_2.php" method="post" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
	  <tr>
	    <th colspan="2">ingreso de Modulo</th>
    </tr>
    </thead>
    <tbody>
	  <tr>
	    <td width="48%">Clave de Archivo</td>
	    <td width="52%"><label for="clave_archivo"></label>
        <input type="text" name="clave_archivo" id="clave_archivo"  value="<?php echo $clave_archivo;?>"/></td>
    </tr>
	  <tr>
	    <td>Clave de registro</td>
	    <td><label for="clave_registro"></label>
        <input type="text" name="clave_registro" id="clave_registro" /></td>
    </tr>
	  <tr>
	    <td>Nombre de Modulo</td>
	    <td><label for="nombre_modulo"></label>
        <input type="text" name="nombre_modulo" id="nombre_modulo" /></td>
      </tr>
	  <tr>
	    <td>Categoria</td>
	    <td><label for="categoria_modulo"></label>
	      <input type="text" name="categoria_modulo" id="categoria_modulo" /></td>
	    </tr>
    </tbody>
  </table>
 </form> 
<?php }?>
</div>
<div id="apDiv2"><a href="#" class="button_R" onclick="VERIFICAR();">Registrar Modulo</a></div>
<div id="apDiv3">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	switch($error)
	{
		case 1:
			$msj="Clave Archivo Vacia";
			break;
		case 2:
			$msj="Clave Archivo Incorrecta";
			break;
		case 3:
			$msj="Nombre Modulo Vacio";
			break;
		case 4:
			$msj="Nombre Modulo ya Existe";
			break;
		case 5:
			$msj="Clave Registro Incorrecta";
			break;	
		default:
			$msj="";				
			
	}
	echo"$msj";
}
?>
</div>
</body>
</html>