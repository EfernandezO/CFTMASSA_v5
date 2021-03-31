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

if($_POST)
{$continuar_1=true;}
else{ $continuar_1=false;}

if($_FILES)
{ $continuar_2=true;}
else{ $continuar_2=false;}

if($continuar_1 and $continuar_2)
{
	$error="debug";
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	
	if(DEBUG){ var_dump($_POST); var_dump($_FILES);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/VX.php");
	
	$tipo=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo"]);
	
	$carpeta="../../CONTENEDOR_GLOBAL/alumno_documentos";
	$prefijo="DA_".$id_alumno."_";
	
	list($archivo_cargado, $nombre_archivo_cargado)=CARGAR_ARCHIVO($_FILES["archivo"],$carpeta,$prefijo);
	
	if($archivo_cargado)
	{
		$cons="INSERT INTO alumno_documentos (id_alumno, tipo, archivo, fecha_generacion, cod_user) VALUES ('$id_alumno', '$tipo', '$nombre_archivo_cargado', '$fecha_hora_actual', '$id_usuario_actual')";
		
		if(DEBUG){ echo"---> $cons<br>";}
		else
		{ 
			if($conexion_mysqli->query($cons))
			{ 
				$error="DA0";
				$evento="Carga Documento a Alumno id_alumno:$id_alumno";
				REGISTRA_EVENTO($evento);
			}
			else{ $conexion_mysqli->error; $error="DA1";}
		}
	}
	else
	{ $error="DA2";}
	
	$conexion_mysqli->close();
	
	$url="carga_documento_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
?>