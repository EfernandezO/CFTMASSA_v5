<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Devolucion de Libros</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
.Estilo2 {
	font-size: 18px;
	font-weight: bold;
}
.Estilo3 {color: #0080C0}
 
-->
</style>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:28px;
	z-index:1;
	left: 5%;
	top: 128px;
}
-->
</style>
</head>
<?php
if(isset($_GET["error"]))
{
	$hay_errores=true;
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png">';
	switch($error)
	{
		case"D0":
			$msj="Libro Devuelto...";
			$IMG=$img_ok;
			break;
	}
}
else
{$hay_errores=false;}
?>
<body>
<h1 id="banner">Administrador - Biblioteca</h1>
<div id="apDiv1">
  <div align="center">
  	  <p>
  	    <?php
		if($hay_errores)
		{ echo"$msj $IMG";}
    	?>
      </p>
  	  <div id="button-box">
  	    <p>&nbsp;</p>
  	    <p><a href="../enrutador_main.php?destino=biblioteca" class="button_G">Volver a Biblioteca</a></p>
  	  </div>
  	    <p>&nbsp;</p>
     
  	</div>
  </div>
</body>
</html>