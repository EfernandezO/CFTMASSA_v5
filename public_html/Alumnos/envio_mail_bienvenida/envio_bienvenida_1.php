<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_mail_bienvenida_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
////////////////////////////////////////////////////////////////////
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$hay_alumno_activo=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"];
	if(empty($hay_alumno_activo)){ $hay_alumno_activo=false;}
}
else
{
	if(DEBUG){ echo"Sin ALUMNO Seleccionado NO session:( <br>";}
}
//////////////////////////////////////////////////////////////////////
if($hay_alumno_activo)
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	if(DEBUG){ echo"ALUMNO ACTIVO: $id_alumno<br>";}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_varias.php");

		$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
		$DA=$sqli->fetch_assoc();
		$A_nombre=$DA["nombre"];
		$A_apellido_P=$DA["apellido_P"];
		$A_apellido_M=$DA["apellido_M"];
		$A_email=$DA["email"];
		$A_carrera=$DA["carrera"];
	$sqli->free();
	$conexion_mysqli->close();
	
	if(comprobar_email($A_email))
	{	
		$action="envio_bienvenida_2.php";
		$js='c=confirm("Seguro(a) Desea Enviar Email de Bienvenida a este Alumno?");
			if(c)
			{ document.getElementById(\'frm\').submit();}';
		
	}
	else
	{
		$action="";
		$js='alert("Email de Alumno Invalido, Modifiquelo para poder Continuar");';
	}
}
else
{
	$action="";
	$js='alert(Sin ALumno Seleccionado");';
	if(DEBUG){ echo"No hay Alumno Activo<br>";}
	$A_nombre="";
	$A_apellido_P="";
	$A_apellido_M="";
	$A_email="";
	$A_carrera="";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 20%;
	top: 117px;
}
</style>
<script  language="javascript">
function ENVIAR_BIENVENIDA()
{
	<?php echo $js;?>
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Email Bienvenida Alumno</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver</a></div>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
<table width="100%" border="0" align="center" id="rounded-corner" summary="envio de bienvenida" >
<thead>
  <tr>
    <th scope="col" class="rounded-company" colspan="2">Enviar Correo Bienvenida</th>
    </tr>
</thead>
<tbody>
<tr>
 <td>ID</td>
    <td><?php echo $id_alumno;?></td>
  </tr>
  <tr>
    <td>Nombre</td>
    <td><?php echo $A_nombre;?>
      <input name="nombre" type="hidden" id="nombre" value="<?php echo $A_nombre;?>" /></td>
  </tr>
  <tr>
    <td>Apellido P</td>
    <td><?php echo $A_apellido_P;?>
      <input name="apellido_P" type="hidden" id="apellido_P" value="<?php echo $A_apellido_P;?>" /></td>
  </tr>
  <tr>
    <td>Apellido M</td>
    <td><?php echo $A_apellido_M;?>
      <input name="apellido_M" type="hidden" id="apellido_M" value="<?php echo $A_apellido_M;?>" /></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><?php echo $A_email;?>
      <input name="email" type="hidden" id="email" value="<?php echo $A_email;?>" /></td>
  </tr>
  <tr>
    <td>Carrera</td>
    <td><?php echo $A_carrera;?>
      <input name="carrera" type="hidden" id="carrera" value="<?php echo $A_carrera;?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="verificador" type="hidden" id="verificador" value="<?php echo md5("bienvenida".date("d/m/Y"))?>" /></td>
  </tr>
  </tbody>
   <tfoot>
    	<tr>
        	<td class="rounded-foot-left"><em>...</em></td>
        	<td class="rounded-foot-right"><input type="button" name="Bot&oacute;n" id="enviar" value="Enviar Bienvenida" onclick="ENVIAR_BIENVENIDA();"/></td>
        </tr>
    </tfoot>
</table>
</form>
</div>
</body>
</html>