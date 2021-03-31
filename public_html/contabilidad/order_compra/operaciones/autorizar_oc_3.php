<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
$msj="";
$img="";
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" />';
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	switch($error)
	{
		case"OC_A0":
			$msj="Orden de Compra Autorizada...";
			$img=$img_ok;;
			break;
		case"OC_A1":
			$msj="Falla Al Autorizar Orden de Compra";
			$img=$img_error;
			break;	
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Autorizar OC</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 59px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:32px;
	z-index:2;
	left: 5%;
	top: 215px;
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
<h1 id="banner">Autorizar - Orden de Compra</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th width="100%">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center"><?php echo $img.$msj;?></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>