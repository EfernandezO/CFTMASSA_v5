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
if($_GET)
{
	$id_libro=$_GET["id_libro"];
	$error=$_GET["error"];
	if(is_numeric($id_libro))
	{
		$link="../archivos_asociados/carga_asociados/index.php?id_libro=$id_libro";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Graba libro final</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:8;
	left: 5%;
	top: 193px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
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
-->
</style>
</head>

<body>
<h1 id="banner">Biblioteca - Ingreso Libro Final</h1>
<div id="link"><span class="Estilo1"><br />
<a href="../../menu_biblioteca.php" class="button">Volver al Menu</a></span></div>
<div id="Layer6" style="position:absolute; left:30%; top:73px; width:40%; height:90px; z-index:7">
  <div align="center">
    <p><font color="#000099"> MENU GENERAL<br />
      SISTEMA INTEGRADO BIBLIOTECA<br />
      C.F.T. MASSACHUSETTS<br />
      TALCA - LINARES</font></p>
  </div>
</div>
<p>&nbsp;</p>
<div id="Layer1">
  <div align="center">
    <span class="Estilo1">
    <?php
if($error==0)
{
	?>
    <tt>El Libro ha sido Exitosamente guardado</tt><img src="../../../BAses/Images/ok.png" width="29" height="26" /><br />
    <br />
<br>
    <a href="<?php echo $link;?>" class="button_G">Asociar Archivos??
    </a><br /><br /><br />
    <?php
}
else
{
?>
    <tt>El libro No pudo se Guardado Intentelo más Tarde</tt>
    <img src="../../../BAses/Images/b_drop.png" width="16" height="16" />
    <?php
}
?>
    <br />
    <a href="../../menubiblio.php"><br />
    </a></span></div>
</div>
</body>
</html>