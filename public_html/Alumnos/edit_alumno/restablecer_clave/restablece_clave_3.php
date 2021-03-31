<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Restablecer_clave_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Edicion de Alumnos - Final</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:309px;
	height:183px;
	z-index:1;
	left: 267px;
	top: 119px;
}
a:link {
	color: #6699CC;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699CC;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699CC;
}
-->
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function fcnClose()
{
	//alert("Se va a cerrar SexyLightbox");
	// Función necesaria para cerrar la ventana modal
	window.parent.SexyLightbox.close();
	// Función necesaria para actualizar la ventana padre
	window.parent.document.location.reload();
}
setTimeout("fcnClose()",1500);
</script>
<!--FIN CIERRE-->
</head>

<body>
<h1 id="banner">Administrador - Restablece Clave</h1>
<?php
if($_GET)
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" />';
	switch($error)
	{
		case"RC0":
			$msj="Clave Restablecida, Ma_ +  rut";
			$img=$img_ok;
			break;
		case"RC1":
			$msj="Fallo al Restablecer Clave";
			$img=$img_error;
			break;		
	}
}
?>
<div id="mensaje_error">
  <div align="center">
    <p>&nbsp;</p>
    <table width="80%" border="1">
    <thead>
      <tr>
        <th><div align="center"><strong>INFORMACION</strong></div></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td><div align="center"><?php echo "$msj $img<br>";?></div>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
