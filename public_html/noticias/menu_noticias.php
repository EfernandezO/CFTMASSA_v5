<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Menu de Noticias</title>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	
	<script src="../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
	
	<style type="text/css">
<!--
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
-->
    </style></head>
	<body>
	<h1 id="banner">Administrador - Men&uacute; Noticias </h1>
	<h3>Administre las Noticias que se Ver&aacute;n en la Portada del Sitio </h3>
	<div id="main">
	<ul id="browser" class="filetree">
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /><a href="nva_noticia/nueva1_3.php"> Ingresar Noticia </a> </strong>(local)</li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /> <a href="edita_noticia/edita_not1.php">Editar Noticias   </a> (local)</li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /> <a href="http://www.cftmass.cl/OKALIS/inicio_sesion_simple.php?key=<?php echo md5("noticias".date("d-m-Y"));?>">Edicion Noticias cftmass.cl</a> (sitio)</li>
	  <li class="Estilo1"><img src="../imagenes/retornar_2.jpg" alt="&lt;-" width="20" height="20" /><a href="../Administrador/ADmenu.php">Volver al Men&uacute;  </a></li>
	</ul>	
	</div>
	
	</body>
</html>