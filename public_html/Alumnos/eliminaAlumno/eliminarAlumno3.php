<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Eliminacion_registro_Alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Eliminar Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:104px;
	z-index:1;
	left: 5%;
	top: 100px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 273px;
}
#apDiv2 {
	border: medium solid #39C;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:28px;
	z-index:3;
	left: 30%;
	top: 332px;
	text-align: center;
}
</style>
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
</head>
<?php
$actualizar_sesion=false;
$msj="";
$img="";
$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="x" />';
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	switch($error)
	{
		case"RC0":
			$msj="Alumno Eliminado Correctamente";
			$actualizar_sesion=true;
			$img=$img_ok;
			unset($_SESSION["SELECTOR_ALUMNO"]);
			break;
		case"RC1":
			$msj="ERROR al Eliminar a Alumno";
			$img=$img_error;
			break;			
	}
}

//------------------------------------------//
if($actualizar_sesion)
{$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]=false;}
?>
<body>
<h1 id="banner">Administrador - Alumno Pendiente</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center"><?php echo $img.$msj;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>