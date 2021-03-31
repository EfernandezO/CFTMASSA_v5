<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$array_certificados=array("alumno_regular", "titulo", "egresado");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Creacion Solicitud</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 66px;
	text-align: center;
}
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
<h1 id="banner">Administrador - Crea Solicitudes</h1>
<div id="apDiv1">
  <table width="60%" border="1" align="center">
  <thead>
    <tr>
      <th>Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center">
       <?php
 if(isset($_GET["error"]))
 {
	 $error=$_GET["error"];
	 $img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	 $img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
	 switch($error)
	 {
		 case"S0":
		 		$msj="Solicitud Creada...";
				$img=$img_ok;
		 	break;
		 case"S1":
		 		$msj="No se puede Crear solicitud, ya existe una no autorizada...";
				$img=$img_error;
		 	break;
		 case"S2":
		 		$msj="";
		 	break;	
		 case"S3":
			$msj="Solicitud Creada... Y Autorizada por Gratuidad...";
			$img=$img_ok;
		break;	
		default:
			$msj="";	
			$img="";
	 }
	 echo $img.$msj;
 }
 ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
  
</div>
</body>
</html>