<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../OKALIS/msj_error/anti_2_login.php");
	define("DEBUG", false);
	 $id_alumno=$_SESSION["USUARIO"]["id"];
	//-----------------------------------------//	
 	 include("../../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	//-----------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Evaluacion Jefe de Carrera</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style>
#apDiv4 {
	position:absolute;
	width:40%;
	height:64px;
	z-index:1;
	left: 30%;
	top: 138px;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	window.parent.document.location.reload();
	setTimeout("CERRAR()",1000);
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
{
	$error=$_GET["error"];
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	
	switch($error)
	{
		case"C0":
			$msj="La Encuesta fue Correctamente Contestada<br>Muchas Gracias por su Colaboracion";
			$img=$img_ok;
			break;
	}
	
}
else
{
	$msj="";
	$img="";
}

?>
<body>
<h1 id="banner">Evaluación Docente</h1>
<div id="apDiv4">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="91" align="center"><?php echo $msj.$img;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>