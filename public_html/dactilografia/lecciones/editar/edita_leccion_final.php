<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<title>edicion final</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 133px;
	text-align: center;
}
</style>
</head>
<?php
if($_GET)
{
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" />';
	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj="Leccion Modificada Exitosamente";
			$img=$img_ok;
			break;
	}
}
?>
<body>
<h1 id="banner">Dactilografia - Nueva Lecci&oacute;n Final</h1>
<br />
<div id="link"><a href="../lecciones_main.php" class="button">Volver </a></div>
<div id="apDiv1"><?php echo "$msj $img";?></div>
</body>
</html>