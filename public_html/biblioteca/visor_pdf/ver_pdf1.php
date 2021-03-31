<?php
	 session_start();
	 $mostrar=false;
if(isset($_SESSION["acceso_biblio"]))
{
	 $acceso_biblio=$_SESSION["acceso_biblio"];
	 if($acceso_biblio=="SI")
	 { $mostrar=true;}
	 else{ $mostrar=false;}
}
else
{ $mostrar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Biblioteca | pdf</title>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.3/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.v2.3.mootools.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.css"/>
  <script type="text/javascript">
    window.addEvent('domready', function(){
      SexyLightbox = new SexyLightBox({color:'black', dir: '../../libreria_publica/sexy_lightbox/Mootools/sexyimages'});
    });
  </script>
<style type="text/css">
<!--
.Estilo2 {font-size: 16px}
#Layer2 {	position:absolute;
	width:46px;
	height:18px;
	z-index:1;
	left: 101px;
	top: 6px;
}
#titulo font {
	background-position: center;
	text-align: center;
	padding-bottom: 100px;
}
#titulo font {
	padding-bottom: 100px;
}

#Layer1 {	position:absolute;
	width:35px;
	height:18px;
	z-index:1;
	left: 14px;
	top: 80px;
}
.msj {
	border: thin solid #FF0000;
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
#containerEx5 #ex5 .msj tt {
	color: #FFF;
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
	$imagen_predeterminada="../../imagenes/PDF.jpg";
if($_GET)
{
		
		$id_libro=$_GET["id"];
		$aux=0;
		$aux_tr=0;
		$url_actual="ver_pdf1.php?id=$id_libro";
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/funcion.php");
		$id_libro=str_inde($id_libro,"NO");
		//si id esta limpio
		if(($id_libro!="NO")&&(is_numeric($id_libro)))
		{
			//con url completa tiene que ser
			$path="http://200.28.135.221/CFTMASSA/www/CONTENEDOR_GLOBAL/biblioteca_pdf/";
			//consulto nombre de libro en cuestion
			$consLL="SELECT nombre FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
			$sqlNL=mysql_query($consLL)or die(mysql_error());
			$DL=mysql_fetch_assoc($sqlNL);
			$nombre_l=ucwords(strtolower($DL["nombre"]));
			mysql_free_result($sqlNL);
			
			//Busco si tiene archivos asociados de l tipo imagenimagenes
			$consB="SELECT * FROM biblioteca_asociados WHERE id_libro='$id_libro' AND tipo_archivo='pdf'";
			$sqlB=mysql_query($consB)or die("ERROR de BUSQUEDA: <br>".mysql_error());
			$num_asociados=mysql_num_rows($sqlB);
			?>
<div id="apDiv2"><?php echo $nombre_l;?></div>
<div id="containerEx5">
  <div id="ex5">            			
<?php	
			
			$aux=2;
			if($num_asociados>0)
			{
				//ordeno los resultados en variables
				while($ASO=mysql_fetch_assoc($sqlB))
				{
					$titulo=$ASO["titulo"];
					$nombre_pdf=$ASO["archivo"];
					$ruta=$nombre_pdf;//solo mando nombre esta vez
					$ruta_envia=$ruta;
					//echo"$ruta";
					//muestro imagenes
					//echo"--> $aux<br>";
					$aux++;
					
					//----------------***********-----------------------
					if(!$mostrar)
					{
						$ruta_envia_M="";
						$id_libro_M="X=Autentificar_Primero";
						$destino="#";
						$target="";
					}
					else
					{
						
						$ruta_envia_M=base64_encode($ruta_envia);//codifico ruta en este caso solo nombre de pdf
						$id_libro_M=$id_libro;
						$target="_blank";
						//$destino="ver_pdf2.php?ruta=$ruta_envia_M&id_libro=$id_libro_M";
						$destino="visor_pdf2x.php?pdf=$ruta_envia_M";
					}
					//----------------***********-----------------------
					echo'
      					<a href="'.$destino.'"  title="'.$titulo.'" target="'.$target.'" ><img src="'.$imagen_predeterminada.'" alt="PDF" width="30" height="30"  border="0" /></a>';
				}
				
			}
			else
			{
				//sin archivos
				echo"<b>Lo Sentimos, El Libro No tiene Archivos Asociados....</b>";
			}
		
		if(!$mostrar)
		{
  			echo'<div class="msj"><br><tt>Contenido Bloqueado. Para ver Primero Autentificarse 
			<a href="../Autentificacion_G/index.php?url='.base64_encode($url_actual).'?TB_iframe=true&height=300&width=600" rel="sexylightbox" class="button">Entrar</a></tt></br><br></div>';
		}
  echo'</table></div>';
  		mysql_free_result($sqlB);
		}
		else
		{
			echo"<b>NO Encontrado... </b>";
		}	
		mysql_close($conexion);
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