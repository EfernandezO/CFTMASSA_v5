<?php
   require("../../../SC/seguridad.php");
   require("../../../SC/privilegio2.php");
   define("DEBUG",false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cambio de condicion - Final</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<style>
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#apDiv1 {
	position:absolute;
	width:100%;
	height:115px;
	z-index:1;
	left: 2px;
	top: 124px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Finanzas Cheques</h1>
<div id="link">
  <div id="apDiv1">
    <div align="center">
    <?php
	if($_GET)
	{
    	$error=$_GET["error"];
		switch($error)
		{
			case"0":
				$msj="Cambio Realizado Exitosamente";
				$img='<img src="../../../BAses/Images/ok.png" />';
				break;
			case"1":
				$msj="Error Al intentar Realizar el Cambio de Condicion <br> intentelo mas tarde";
				$img='<img src="../../../BAses/Images/b_drop.png" />';
				break;	
		}
		
		echo $msj." ".$img;
	}	
	?>
    </div>
  </div>
<a href="../index.php">Volver a Seleccion</a></div>
</body>
</html>
