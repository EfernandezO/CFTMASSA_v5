<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_GET['file']) || empty($_GET['file'])) {
 exit();
}
require("../../../funciones/VX.php");
$root = "../../CONTENEDOR_GLOBAL/tareas_trabajos_docentes/";


if(isset($_GET["T_id"])){$T_id=base64_decode($_GET["T_id"]);}
else{ $T_id=0;}

$file = basename(base64_decode($_GET['file']));
$file=str_replace("/","",$file);
$extencion_archivo=explode(".",$file);
$extencion_archivo=end($extencion_archivo);
$extencion_archivo=strtolower($extencion_archivo);
$array_extenciones_permitidas=array("zip", "rar", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "bmp", "jpg", "jpeg", "png");

$path = $root.$file;
$type = '';
 
if(in_array($extencion_archivo, $array_extenciones_permitidas))
{  
	if (is_file($path)) 
	{
		$evento="descarga archivo ($file) T_id [$T_id] carga_descarga_tarea_docente";
		REGISTRA_EVENTO($evento);
		
	 $size = filesize($path);
	 if (function_exists('mime_content_type')) {$type = mime_content_type($path);} 
	 else if (function_exists('finfo_file')) 
	 {
		 $info = finfo_open(FILEINFO_MIME);
		 $type = finfo_file($info, $path);
		 finfo_close($info);
	 }
	 if ($type == '') {$type = "application/force-download";}
	 // Definir headers
	 header("Content-Type: $type");
	 header("Content-Disposition: attachment; filename=$file");
	 header("Content-Transfer-Encoding: binary");
	 header("Content-Length: " . $size);
	 // Descargar archivo
	 readfile($path);
	} else {
	 die("El archivo no existe.");
	}
} else {
 die("El archivo no existe.");
}
?>