<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pago Honorario Docente</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 75px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:25px;
	z-index:2;
	left: 5%;
	top: 340px;
	text-align:center;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:19px;
	z-index:3;
	left: 5%;
	top: 268px;
	text-align: center;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	//setTimeout("fcnClose()",1500);
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
setTimeout("BATIR()",1500);
</script>
<!--FIN CIERRE-->
</head>
<?php 
if(isset($_GET["error"]))
{$error=$_GET["error"];}
else{ $error="";}


if(isset($_GET["id_honorario"]))
{ $id_honorario=$_GET["id_honorario"];}
else{$id_honorario=0;}



$img_ok='<img src="../../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
$mostrar_boton=false;
switch($error)
{
	
	case"CBP0":
		$msj="Boleta Honorario Cargada Exitosamente";
		$img=$img_ok;
		break;
	case"CBP1":
		$msj="Error, Archivo no Cargado";
		$img=$img_error;
		break;	
	case"CBP2":
		$msj="Error, Archivo no vinculado";
		$img=$img_error;
		break;		
	case"CBP3":
		$msj="Error, Archivo no Enviado";
		$img=$img_error;
		break;			
	default:
		$msj="";
		$img="";	
}
?>
<body>
<h1 id="banner">Administrador - Pago Honorario Docente</h1>
<div id="apDiv1">
  <table width="70%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="93" align="center"><?php echo "$img </br> $msj";?></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv3"><a href="#" class="button_R" onclick="CERRAR();">Cerrar</a></div>
</body>
</html>