<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
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
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/VX.php");
	
	$PATH="../../../CONTENEDOR_GLOBAL/ACTAS/";
	
	$id_acta=mysqli_real_escape_string($conexion_mysqli, $id_acta);
	$cons_A="SELECT sede, id_carrera, jornada, semestre, year, tipo, archivo FROM actas WHERE id_acta='$id_acta' LIMIT 1";
	if(DEBUG){ echo"--> $cons_A<br>";}
	$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$AC=$sqli_A->fetch_assoc();
		$AC_archivo=$AC["archivo"];
		$AC_sede=$AC["sede"];
		$AC_id_carrera=$AC["id_carrera"];
		$AC_jornada=$AC["jornada"];
		$AC_semestre=$AC["semestre"];
		$AC_year=$AC["year"];
		$AC_tipo=$AC["tipo"];
	$sqli_A->free();
	
	$ruta_archivo=$PATH.$AC_archivo;	
	if(DEBUG){ echo"Ruta archivo: $ruta_archivo<br>";}
	
	if(unlink($ruta_archivo)){ $archivo_eliminado=true; if(DEBUG){ echo"Archivo Eliminado<br>";}}
	else{ $archivo_eliminado=false; if(DEBUG){ echo"Archivo No Eliminado<br>";} $error="AC2";}
	
	if($archivo_eliminado)
	{
		$cons_D="DELETE FROM actas WHERE id_acta='$id_acta' LIMIT 1";
		if($conexion_mysqli->query($cons_D))
		{ 
			if(DEBUG){ echo"Acta borrada de BBDD<br>";} 
			$error="AC3";
			$evento="Elimina Acta $AC_sede id_carrera: $AC_id_carrera jornada: $AC_jornada semestre: $AC_semestre year: $AC_year tipo: $AC_tipo";
			REGISTRA_EVENTO($evento);
		}
		else{if(DEBUG){ echo"Acta NO borrada de BBDD<br>";} $error="AC4";}
	}
	//----------------------------------------------------------------------//
	$URL="eliminar_acta_2.php?error=$error";
	if(DEBUG){ echo"URL: $URL";}
	else{ header("location: $URL");}
}
else
{ echo"No se puede continuar<br>";}
?>