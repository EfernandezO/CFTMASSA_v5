<?php
//--------------CLASS_okalis------------------//
require("../../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->ruta_conexion="../../../../../funciones/";
$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
$O->PERMITIR_ACCESO_USUARIO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Docente - estudios</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv2 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 85px;
	text-align: center;
}
#apDiv2 table tbody tr td {
	text-align: center;
}
-->
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	//setTimeout("fcnClose()",1500);+
	setTimeout("CERRAR()",1000);
}
function CERRAR()
{
	//alert("Se va a cerrar SexyLightbox");
	// Funci�n necesaria para cerrar la ventana modal
	//window.parent.lightbox.close();
	
	window.parent.jQuery.lightbox().close();
	// Funci�n necesaria para actualizar la ventana padre
	window.parent.document.location.reload();
}
setTimeout("BATIR()",500);
</script>
<!--FIN CIERRE-->
</head>
<?php
if($_GET)
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($error)
	{
		case"C0":
			$msj="Imagen Cargada...";
			$img=$img_ok;
			break;
		case"C3":
			$msj="Imagen Restaurada...";
			$img=$img_ok;
			break;	
	}
}
?>
<body>
<h1 id="banner">Docente - Estudios</h1>
<div id="apDiv2">
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th>Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><?php echo "$msj $img";?></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>