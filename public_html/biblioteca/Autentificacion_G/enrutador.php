<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Biblioteca | Acceso</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:83px;
	z-index:1;
	left: 30%;
	top: 96px;
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
<h1 id="banner">Biblioteca - C.F.T. Massachusetts</h1>
<div id="apDiv1"><br />
  <img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" /><br />
  Usuario Autentificado<br />
  Correctamente<br />
  <br />
  si no continua automaticamnete<br />
click <a href="#" onclick="fcnClose();">aqui</a><br />
</div>
</body>
</html>