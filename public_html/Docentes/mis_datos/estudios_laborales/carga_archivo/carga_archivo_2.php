<?php
//--------------CLASS_okalis------------------//
require("../../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->setDisplayErrors(true);
$O->DEBUG=DEBUG;
$O->ruta_conexion="../../../../../funciones/";
$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
$O->PERMITIR_ACCESO_USUARIO();

if(DEBUG){ var_dump($_FILES);}
if((isset($_FILES["archivo"]))and(isset($_POST["E_id"])))
{
	$volver_a_index=false;
	$E_id=$_POST["E_id"];
	$id_funcionario=$_POST["id_funcionario"];
	
	$path="../../../../CONTENEDOR_GLOBAL/docente_estudios/";
	//$path="/home/cftmassa/public_html/CONTENEDOR_GLOBAL/docente_estudios/";
	$array_formatos_compatibles=array("jpg", "jpeg", "png", "gif");
	$peso_maximo=10000000;///peso maximo archivo cargado
	
	$nombre_archivo=$_FILES["archivo"]["name"];
	$tmp_nombre=$_FILES["archivo"]["tmp_name"];
	$peso_archivo=$_FILES["archivo"]["size"];
	
	$extencion_archivo=explode(".",$nombre_archivo);
	$extencion_archivo=end($extencion_archivo);
	
	$nombre_archivo_new="registro_estudio_".$id_funcionario."_".$E_id."X.$extencion_archivo";
	//$nombre_archivo_new="img.jpg";
	
	if(DEBUG){ echo"Extencion: $extencion_archivo PESO: $peso_archivo (MAXIMO: $peso_maximo)<br>";}
	$destino=$path.$nombre_archivo_new;
	
	if((in_array($extencion_archivo,$array_formatos_compatibles)))
	{
		if(DEBUG){ echo"Extencion: $extencion_archivo <br>Destino: $destino<br> tmp: $tmp_nombre<br> destino: $destino<br>";}
		if(move_uploaded_file($tmp_nombre, $destino))
		{
			include("../../../../../funciones/conexion_v2.php");
			include("../../../../../funciones/VX.php");
			$cons_UP="UPDATE personal_registro_estudios SET archivo='$nombre_archivo_new' WHERE id='$E_id' LIMIT 1";
			if(DEBUG){ echo" $cons_UP<br>";}
			else{ 
					$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
					$evento="Carga Archivo a estudios de Personal";
					REGISTRA_EVENTO($evento);
				}
			$error="C0";
			
			//intento cambiar permiso de archivo
			chmod($destino, 777);
		}
		else{ $volver_a_index=true; $error="C1";}
	}
	else
	{$volver_a_index=true; $error="C2";}
	//-----------------------------------------------------------//
	if($volver_a_index)
	{
		if(DEBUG){ echo"PRESENCIA DE ERROR volver a Index<br>ERROR: $error<br>";}
		else{ header("location: carga_archivo_1.php?error=$error");}
	}
	else
	{
		if(DEBUG){ echo"Ir a Mensaje Final Error:$error<br>";}
		else{header("location: carga_final.php?error=$error");}
	}
}
else
{ if(DEBUG){ echo"NO FILE<br>";} else{header("location: carga_archivo_1.php");}}
?>