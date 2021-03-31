<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Notas_Semestrales_v1->grabar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Notas Semestrales</title>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 30%;
	top: 126px;
}
</style>
</head>
<?php
$msj="";
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	switch($error)
	{
		case"N0":
			$msj="Notas Modificadas Exitosamente...";
			break;
	}
}
?>
<body>
<h1 id="banner">Administrador - Notas Semestrales Alumnos V 1.4</h1>
<div id="link"><br><a href="../buscador_alumno_BETA/HALL/index.php" class="button">
Volver al Menu</a></div>
<div id="apDiv1">
<table width="100%" border="1">
<thead>
  <tr>
    <th>Informacion</th>
    </tr>
   </thead>
   <tbody> 
  <tr>
    <td height="68" align="center"><?php echo $msj;?></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
    </tbody>
</table>
</div>
</body>
</html>