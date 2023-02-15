<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Menu de Observaciones</title>
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
	<h1 id="banner">Administrador -Hoja de Vida</h1>
	<h3>Administre las Noticias que se Ver&aacute;n en la Portada del Sitio </h3>
	<div id="main">
	<ul id="browser" class="filetree">
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /> <a href="observaciones/seleccion_alumno.php">Buscar Alumno</a></strong></li>
	  <li class="Estilo1"><img src="../imagenes/retornar_2.jpg" alt="&lt;-" width="20" height="20" />
      	<?php
		if($_SESSION["USUARIO"]["privilegio"]=="Docente")
		{
			echo'<a href="../Docentes/okdocente.php">Volver al Men&uacute;  </a>';
		}
		else
		{
			
			echo'<a href="../Administrador/ADmenu.php">Volver al Men&uacute;  </a>';
		}	
		?>
      </li>
	</ul>	
	</div>
	
	</body>
</html>