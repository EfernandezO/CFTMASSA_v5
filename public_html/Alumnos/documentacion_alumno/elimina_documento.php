<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Documentacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_GET["id_documento"]))
{
	$id_documento=base64_decode($_GET["id_documento"]);
	if(is_numeric($id_documento)){ $continuar=true;}
}

if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	$error="debug";
	
	$cons="SELECT id_alumno, archivo FROM alumno_documentos WHERE id_documento='$id_documento' LIMIT 1";
	if(DEBUG){ echo"$cons<br>";}
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$DA=$sqli->fetch_assoc();
		$DA_archivo=$DA["archivo"];
		$DA_id_alumno=$DA["id_alumno"];
	$sqli->free();
	
	$ruta_archivo="../../CONTENEDOR_GLOBAL/alumno_documentos/".$DA_archivo;
	
	if(DEBUG){ $archivo_eliminado=true;}
	else
	{
		if(unlink($ruta_archivo))
		{ $archivo_eliminado=true;}
		else{ $archivo_eliminado=false;}
	}
	
	if($archivo_eliminado)
	{
		$cons_2="DELETE FROM alumno_documentos WHERE id_documento='$id_documento' LIMIT 1";
		if(DEBUG){ echo"--> $cons_2<br>"; }
		else
		{ 
			$conexion_mysqli->query($cons_2); 
			$error="DAE0";
			
			require("../../../funciones/VX.php");
			$evento="Elimina Documento de Alumno id_alumno: $DA_id_alumno";
			REGISTRA_EVENTO($evento);
		}
	}
	$conexion_mysqli->close();
	
	$url="documentacion_alumno.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ echo"No continuar<br>";}
?>