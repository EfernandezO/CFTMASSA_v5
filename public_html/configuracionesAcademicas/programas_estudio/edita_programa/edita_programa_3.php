<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Programa Estudio</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 97px;
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
	top: 342px;
	text-align: center;
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
<?php 
if(isset($_GET["error"]))
{$error=$_GET["error"];}
else{ $error="";}



$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
switch($error)
{
	case"PE4":
		$msj="Programa Estudio Modificado";
		$img=$img_ok;
		break;
	case"PE5":
		$msj="Imposible Modificar Programa Estudios";
		$img=$img_error;
		break;
	default:
		$msj="";
		$img="";	
}

?>
<body>
<h1 id="banner">Administrador -Programa Estudio</h1>
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