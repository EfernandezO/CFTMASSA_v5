<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//

$continuar=false;
if($_GET)
{
	$year=base64_decode($_GET["year"]);
	$semestre=base64_decode($_GET["semestre"]);
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	
	$continuar=true;
}

if($continuar)
{
	$ruta='../../../CONTENEDOR_GLOBAL/PLANIFICACIONES_D/';
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	
	$evento="Revisa planificacion cargada ADMINISTRADOR periodo $sede [$semestre - $year] id_carrera: $id_carrera cod_asignatura: $cod_asignatura jornada: $jornada grupo: $grupo id_funcionario: $id_funcionario";
	REGISTRA_EVENTO($evento);
	
	$cons="SELECT archivo FROM planificaciones_v2 WHERE sede='$sede' AND year='$year' AND semestre='$semestre' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND id_funcionario='$id_funcionario' LIMIT 1";
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
	if(DEBUG){ echo"PLANIFICACION: archivo: $I_archivo<br>Ruta: $ruta_archivo<br>";}

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
				echo'<img src="../../../BAses/Images/logo.png" width="334" height="80" alt="logo_cft" /><br>';
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