<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 153px;
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
setTimeout("BATIR()",500);
</script>
<!--FIN CIERRE-->
</head>

<body>
<h1 id="banner">Facturas - Carga Archivos a Factura</h1>
 <?php
  $msj="";
  if(isset($_GET["error"]))
  {
  	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj='<span class="Estilo4">factura Cargada Correctamente</span>';
			break;
		case"1":
			$msj='<span class="Estilo5">Error al Cargar factura</span>';
			break;
		case"2":
			$msj='<span class="Estilo5">Error al Grabar en BBDD...</span>';
			break;	
		case"3":
			$msj='<span class="Estilo5">Datos NO recibidos para Borrar...</span>';
			break;	
		case"4":
			$msj='<span class="Estilo5">Error al Eliminar Intentelo mas tarde...</span>';
			break;	
		case"5":
			$msj='<span class="Estilo4">Imagen Eliminada...</span>';
			break;						
	}
  }
  ?>
<div id="apDiv1">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th>Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="81"><?php echo $msj;?></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>