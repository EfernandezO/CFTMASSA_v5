<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("gestion_actas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_GET["id_acta"]))
{
	$id_acta=base64_decode($_GET["id_acta"]);
	if(is_numeric($id_acta)){ $continuar=true;}
}

if($continuar)
{
	$ruta='../../CONTENEDOR_GLOBAL/ACTAS/';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$evento="Revisa acta escaneada id_acta: $id_acta";
	REGISTRA_EVENTO($evento);
	
	$cons="SELECT archivo FROM actas WHERE id_acta='$id_acta' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$I=$sqli->fetch_assoc();
		$I_archivo=$I["archivo"];
	$sqli->free();
	$conexion_mysqli->close();
	
	$extencion_archivo=explode(".",$I_archivo);
	$extencion_archivo=end($extencion_archivo);
	$extencion_archivo=strtolower($extencion_archivo);
	$array_extenciones_permitidas=array("zip", "rar", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "bmp", "jpg", "jpeg", "png");
	$ruta_archivo=$ruta.$I_archivo;
	if(DEBUG){ echo"id_acta: $id_acta archivo: $I_archivo<br>Ruta: $ruta_archivo<br>";}

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