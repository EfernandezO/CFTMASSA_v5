<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Documentacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


$msj="";
$img="";
$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
if($_GET)
{
	if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		switch($error)
		{
			case "DA0":
				$msj="Documento Cargado Correctamente... :D";
				$img=$img_ok;
				break;
			case "DA1":
				$msj="Falla en Consulta, intentelo más Tarde...";
				$img=$img_error;
				break;
			case "DA2":
				$msj="Falla al Cargar Archivo, intentelo más Tarde...";
				$img=$img_error;
				break;		
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Carga Documento Final</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 163px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:36px;
	z-index:2;
	left: 199px;
	top: 158px;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	setTimeout("CERRAR()",500);
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
setTimeout("BATIR()",500);
</script>
<!--FIN CIERRE-->
</head>
<body>
<h1 id="banner">Administrador - Carga Documentación Alumno Final</h1>
<div id="apDiv1">
<form action="carga_documento_2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th width="100%">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td align="center"><?php echo $msj.$img;?></td>
    </tr>
    </tbody>
  </table>
</form>  
</div>
</body>
</html>