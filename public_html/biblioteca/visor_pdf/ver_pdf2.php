<?php 
	session_start();
	 $acceso_biblio=$_SESSION["acceso_biblio"];
	 if($acceso_biblio!="SI")
	 {
	 	header("location: ../seleccion_libro.php");
	 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Visor PDF</title>
<?php
if($_GET)
{
	$ruta_pdf=base64_decode(urldecode($_GET["ruta"]));
	$id_libro=$_GET["id_libro"];
	//echo"$ruta_pdf<br>";
	if(is_numeric($id_libro))
	{
		include("../../../funciones/conexion.php");
		$consLL="SELECT nombre FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
			$sqlNL=mysql_query($consLL)or die(mysql_error());
			$DL=mysql_fetch_assoc($sqlNL);
			$nombre_l=ucwords(strtolower($DL["nombre"]));
			mysql_free_result($sqlNL);
		mysql_close($conexion);	
	}
}
?>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:92px;
	height:147px;
	z-index:1;
	left: 568px;
	top: 1px;
}
#Layer1 {
	color: #CCCCCC;
}
-->
</style>
<?php echo "<strong>$nombre_l</strong><br>";?>
<iframe src="http://docs.google.com/gview?url=<?php echo $ruta_pdf;?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
</head>

<body>
<div id="Layer1"></div>
  <div align="center">
    <input type="button" name="Submit" value="Cerrar Ventana"  onclick="window.close();"/>
  </div>
</body>
</html>