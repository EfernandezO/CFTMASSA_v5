<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Biblioteca | Imagenes</title>
<link rel="stylesheet" type="text/css" href="resource/lightbox.css"/>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script type="text/javascript" charset="UTF-8" src="resource/lightbox_plus.js"></script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:548px;
	height:115px;
	z-index:1;
	left: 19px;
	top: 126px;
}
a {
	font-size: 10px;
	color: #0099FF;
}
a:visited {
	color: #009AFF;
	text-decoration: none;
}
a:hover {
	color: #FF0000;
	text-decoration: none;
}
a:active {
	color: #009AFF;
	text-decoration: none;
}
a:link {
	text-decoration: none;
}
#Layer2 {
	position:absolute;
	width:35px;
	height:18px;
	z-index:1;
	left: 863px;
	top: 127px;
}
.Estilo2 {font-size: 16px}
.Estilo3 {
	font-size: 18px;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
}
/*Example 5*/
#containerEx5 {
background: #333;
padding: 50px;
margin-top: 50px;
}
#ex5 {
width: 700px;
margin: 0 auto;
min-height: 300px;
}
#ex5 img {
margin: 25px;
opacity: 0.8;
border: 10px solid #eee;
/*Transition*/
-webkit-transition: all 0.5s ease;
-moz-transition: all 0.5s ease;
-o-transition: all 0.5s ease;
/*Reflection*/
-webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(.7, transparent), to(rgba(0,0,0,0.1)));
}
#ex5 img:hover {
opacity: 1;
/*Reflection*/
-webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(.7, transparent), to(rgba(0,0,0,0.4)));
/*Glow*/
-webkit-box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
-moz-box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
} 
#apDiv2 {
	position:absolute;
	width:40%;
	height:24px;
	z-index:1;
	left: 30%;
	text-align: center;
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Biblioteca - C.F.T. Massachusetts</h1>
<div id="link"><br>
<a href="../index.php" class="button">Volver a Selecci&oacute;n </a><br />
<br />
<a href="javascript:history.go(-1)" class="button">Volver al Listado</a>
</div>

<?php
	$imagen_predeterminada="../../img/pre.gif";
if($_GET)
{
		$id_libro=$_GET["id"];
		$aux=0;
		$aux_tr=0;
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/funcion.php");
		$id_libro=str_inde($id_libro,"NO");
		//si id esta limpio
		if(($id_libro!="NO")&&(is_numeric($id_libro)))
		{
			$path="../../CONTENEDOR_GLOBAL/biblioteca_img/";
			//consulto nombre de libro en cuestion
			$consLL="SELECT nombre FROM biblioteca WHERE id_libro='$id_libro'";
			$sqlNL=$conexion_mysqli->query($consLL)or die($conexion_mysqli->error);
			$DL=$sqlNL->fetch_assoc();
			$nombre_l=ucwords(strtolower($DL["nombre"]));
			$sqlNL->free();
			
			//Busco si tiene archivos asociados de l tipo imagenimagenes
			$consB="SELECT * FROM biblioteca_asociados WHERE id_libro='$id_libro' AND tipo_archivo='imagen'";
			$sqlB=$conexion_mysqli->query($consB)or die("ERROR de BUSQUEDA: <br>".$conexion_mysqli->error);
			$num_asociados=$sqlB->num_rows;
?>
<div id="apDiv2"><?php echo $nombre_l;?></div>
<div id="containerEx5">
  <div id="ex5">            			
<?php			
			if($num_asociados>0)
			{
				//ordeno los resultados en variables
				while($ASO=$sqlB->fetch_assoc())
				{
					$titulo=$ASO["titulo"];
					$nombre_imagen=$ASO["archivo"];
					$ruta=$path.$nombre_imagen;
					if(!file_exists($ruta))
					{
						$ruta=$imagen_predeterminada;
					}
					
				
					$aux++;
					echo'
      					<a href="'.$ruta.'" rel="lightboxZOOM_1" title="'.$nombre_l.'" class="vertical">
							<img src="'.$ruta.'" alt="" width="89" height="48" border="2" />
							 </a>';
				}
				
			}
			else
			{
				//sin imagenes
				echo"<b>Lo Sentimos, El Libro: $nombre_l No tiene imagenes Vinculadas....</b>";
			}
		
  echo'</table>';
  				$sqlB->free();
		}
		else
		{
			echo"<b>NO Encontrado... </b>";
		}	
		$conexion_mysqli->close();
}
else
{
	echo"NO Hay Libro para Seleccionar<br>";
}	
?>
</div>
</div>
</body>
</html>