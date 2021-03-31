<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$msj="";
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	
	switch($error)
	{
		case"C1":
			$msj="Cobranza Registrada Correctamente... :)";
			break;
		case"C2":
			$msj="Error al Registrar Cobranza, Intentelo mas Tarde.. :(";
			break;	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Registro Cobranza</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 163px;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	CERRAR();
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
<h1 id="banner">Finanzas - Nueva Cobranza</h1>

<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th>informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center"><?php echo $msj;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>