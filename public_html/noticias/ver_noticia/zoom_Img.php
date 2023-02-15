<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Zoom Imagen</title>
</head>

<body>

<?
if(!$_GET)
{

  
}
else
{
 $seleccion="../image_not/";
  $seleccion.=$_GET["seleccion"];
//calculo ancho alto para cargar dinamicamente capa e imagen
$X=getimagesize($seleccion); 

$xx=$X[0]."px";
$yy=$X[1]."px";
//echo"$xx $yy";
}

?>
<div id="Layer1" style="position:absolute; left:82px; top:72px; width:<? echo $xx;?>; height:<? echo $yy ;?>; z-index:7"><img src=<? echo $seleccion?> <? echo $X[3]; ?> />
  <div align="center"></div>
  <div align="center">
    <input type="submit" name="Submit" value="CERRAR" onclick="window.close()" />
  </div>
</div>
</body>
</html>