<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("modifica_boleta_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//--------------------------------------------------///
if(isset($_GET["error"]))
{$error=$_GET["error"];}
else{ $error="";}
//--------------------------------------------------///
$img_ok='<img src="../../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
$mostrar_boton=false;
switch($error)
{
	case"MB0":
		$msj="Boleta Modificada Exitosamente";
		$mostrar_boton=true;
		$img=$img_ok;
		break;
	case"Mb1":
		$msj="Fallo al intentar Modificar Boleta";
		$img=$img_error;
		break;
	default:
		$msj="";
		$img="";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<title>Modifica Boleta</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:80%;
	height:115px;
	z-index:1;
	left: 10%;
	top: 126px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 427px;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	setTimeout("CERRAR()",1500);
}
function CERRAR()
{
	//alert("Se va a cerrar SexyLightbox");
	// Función necesaria para cerrar la ventana modal
	//window.parent.lightbox.close();
	
	window.parent.jQuery.lightbox().close();
	// Función necesaria para actualizar la ventana padre
	window.parent.document.location.reload();
}
setTimeout("BATIR()",1500);
</script>
<!--FIN CIERRE-->
</head>

<body>
<h1 id="banner">Administrador - Modifica Boleta</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th>Informacion</th>
      </tr>
    </thead>
    <tbody>
    <tr>
      <td><?php echo "$img </br> $msj";?></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>

</div>

</body>
</html>