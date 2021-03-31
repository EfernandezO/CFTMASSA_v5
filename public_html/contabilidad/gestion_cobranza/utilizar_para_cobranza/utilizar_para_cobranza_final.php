<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Seleccion_de_alumno_para_realizarle_cobranza_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Seleccionar para Cobranza</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
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
<?php
if($_GET)
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($error)
	{
		case"AI0":
			$msj="Seleccion para Realizacion de Cobranza Modificada";
			$img=$img_ok;
			break;
		case"AI1":
			$msj="Fallo Al intentar Modificar Seleccion para Cobranza";
			$img=$img_error;
			break;		
	}
}
?>
<body>
<h1 id="banner">Administrador - Seleccionar para Cobranza</h1>
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