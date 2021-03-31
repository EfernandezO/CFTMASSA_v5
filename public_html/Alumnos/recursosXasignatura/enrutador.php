<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG",false);
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
$continuar=false;
if(DEBUG){echo"-----------------------------------------------------------------<br>";}
if(isset($_GET["ruta"]))
{
	$I_archivo=base64_decode($_GET["ruta"]);
	if(DEBUG){ echo"Archivo llegada: $I_archivo<br>";}
	 $continuar=true;
}
else
{
	if(DEBUG){ echo"sin Datos<br>";}
}

if($continuar)
{
	$ruta = "../../CONTENEDOR_GLOBAL/cargaXasignatura/";
	require("../../../funciones/VX.php");
	$evento="Revisa recursos descargable X Asignatura archivo [$I_archivo]";
	REGISTRA_EVENTO($evento);
	
	
	
	$extencion_archivo=explode(".",$I_archivo);
	$extencion_archivo=end($extencion_archivo);
	$extencion_archivo=strtolower($extencion_archivo);
	$array_extenciones_permitidas=array("zip", "rar", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "bmp", "jpg", "jpeg", "png");
	$ruta_archivo=$ruta.$I_archivo;
	if(DEBUG){ echo"archivo: $I_archivo<br>Ruta: $ruta_archivo<br>";}

	$type = '';
 
		if(in_array($extencion_archivo, $array_extenciones_permitidas))
		{
			if(DEBUG){ echo"Extension aceptada<br>";}
			if(is_file($ruta_archivo)) 
			{
			 if(DEBUG){ echo"Archivo Existe OK<br>";}
			 $size = filesize($ruta_archivo);
			 if(DEBUG){ echo"size:$size<br>";}
			 // Definir headers
			 if(DEBUG){ }
			 else
			 {
				header('refresh:3 ; url='.$ruta_archivo.''); 
				echo'<img src="../../BAses/Images/logo.png" width="334" height="80" alt="logo_cft" /><br>';
				echo"<strong>Informacion</strong><br>Extension Archivo: $extencion_archivo<br>Peso:$size<br><br>";
				echo'Si la Visualizacion del Documento no Comienza en 3 Segundos, Haga click <a href="'.$ruta_archivo.'">Aqui</a>';
				echo"<br><br>C.F.T Massachusetts - ".date("Y");
			 }
			} 
			else {die("El archivo no existe.");}
		} 
		else {die("Extension Incorrecta del archivo.");}
			
	}
else {die("El archivo no existe.");}
?>