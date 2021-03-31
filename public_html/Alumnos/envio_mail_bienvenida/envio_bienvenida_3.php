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
	if(DEBUG){ echo"ALUMNO ACTIVO: $id_alumno<br>";}
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funciones_varias.php");
	
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
	
}
else
{
	if(DEBUG){ echo"No hay Alumno Activo<br>";}
	$A_nombre="";
	$A_apellido_P="";
	$A_apellido_M="";
	$A_email="";
	$A_carrera="";
}

if(isset($_GET["error"]))
{
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$img=$img_ok;
			$msj="Mensaje Enviado Exitosamente...";
			break;
		case"1":
			$img=$img_error;
			$msj="Fallo en Envio de Mensaje...";	
			break;
	}
}
else
{$msj=""; $img="";}
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
</head>

<body>
<h1 id="banner">Administrador - Email Bienvenida Alumno</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver</a></div>
<div id="apDiv1">
<table width="100%" border="0" align="center" id="rounded-corner" summary="envio de bienvenida" >
<thead>
  <tr>
    <th scope="col" class="rounded-company" colspan="2">Enviar Correo Bienvenida</th>
    </tr>
</thead>
<tbody>
<tr>
 <td width="15%">ID</td>
    <td width="85%"><?php echo $id_alumno;?></td>
  </tr>
  <tr>
    <td>Nombre</td>
    <td><?php echo "$A_nombre $A_apellido_P $A_apellido_M";?></td>
  </tr>

  <tr>
    <td>Email</td>
    <td><?php echo $A_email;?></td>
  </tr>
  <tr>
    <td>Carrera</td>
    <td><?php echo $A_carrera;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </tbody>
   <tfoot>
    	<tr>
        	<td class="rounded-foot-left"><em>...</em></td>
        	<td class="rounded-foot-right"><?php echo "$img $msj";?></td>
        </tr>
   	</tfoot>
</table>
</div>
</body>
</html>