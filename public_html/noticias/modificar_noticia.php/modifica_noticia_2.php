<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");
define("DEBUG", false);
if($_POST)
{
	$formatos_permitidos=array("jpg", "jpeg", "png", "bmp", "gif");
	include("../../../funciones/conexion.php");
	if(DEBUG){ var_export($_POST);}
	$idN=$_POST["idN"];
	
	$fdia=$_POST["fdia"];
	$fmes=$_POST["fmes"];
	$fano=$_POST["fano"];
	if(checkdate($fmes,$fdia,$fano))
	{
		$fecha="$fano-$fmes-$fdia";
	}
	else
	{
		$fecha=date("Y-m-d");
	}
	
	
	
	$fautor=mysql_real_escape_string($_POST["fautor"]);
	$autor=ucwords(strtolower($fautor));
	$ftitulo=mysql_real_escape_string($_POST["ftitulo"]);
	$titulo=ucwords(strtolower($ftitulo));
	$fbreve=mysql_real_escape_string($_POST["fbreve"]);
	$breve=ucwords(strtolower($fbreve));
	$noticia=$_POST["txt_noticia"];
	$fruta_image=$_POST["fruta_image"];
	$imagen_actual=$_POST["imagen_actual"];
	$path="../image_not/";
	$ruta_img_actual=$path.$imagen;
	$campo_img="";
	
	//verifico si hay imagen nueva cara cargar//////////////////
	if((isset($_FILES["imagen_nueva"]))and($_FILES["imagen_nueva"]["error"]==0))
	{
		if(DEBUG){var_export($_FILES);}
		$array_nombre_archivo=explode(".",$_FILES["imagen_nueva"]["name"]);
		
		$nombre_archivo=strtolower($array_nombre_archivo[0]);
		$nombre_archivo=str_replace(" ","_",$nombre_archivo);
		$nombre_archivo=str_replace("(","",$nombre_archivo);
		$nombre_archivo=str_replace(")","",$nombre_archivo);
		$extencion_archivo=end($array_nombre_archivo);
		$extencion_archivo=strtolower($extencion_archivo);
		
		$nombre_archivo=$nombre_archivo."(".rand("0000","9999").").".$extencion_archivo;
		$temp_archivo=$_FILES["imagen_nueva"]["tmp_name"];
		
		$ruta_img_nueva=$path.$nombre_archivo;
		
		if(in_array($extencion_archivo,$formatos_permitidos))
		{
			if(DEBUG){ echo"<b>$nombre_archivo</b> Formato Compatible... cargar<br>";}
			if(move_uploaded_file($temp_archivo,$ruta_img_nueva))
		 	{
				$campo_img=", imagen='$nombre_archivo'";
				@unlink($ruta_img_actual);
			}
		}
		else
		{
			if(DEBUG){ echo"Archivo NO compatible...<br>";} 
		}
	}
	else{ if(DEBUG){ echo"Sin Imagen para cargar...<br>";}}
	///////------------------------------------------////////////
	
	$campo_valor="fecha='$fecha', autor='$autor', titulo='$titulo', breve='$breve', noticia='$noticia' $campo_img";
	
	$cons_UP="UPDATE noticias SET $campo_valor WHERE idn='$idN' LIMIT 1";
	if(DEBUG){ echo "<br><br>$cons_UP<br>";}
	else{mysql_query($cons_UP)or die(mysql_error());}
	mysql_close($conexion);
	
	if(!DEBUG){header("location: ../edita_noticia/edita_not1.php?error=0");}
}
else
{
	if(!DEBUG){ header("location: ../edita_noticia/edita_not1.php");}
}
?>