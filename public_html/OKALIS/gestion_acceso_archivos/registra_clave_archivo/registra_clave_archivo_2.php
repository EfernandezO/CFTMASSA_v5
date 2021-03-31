<?php
//-----------------------------------------//
	require("../../seguridad.php");
	require("../../okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", false);
	define("CLAVE_REGISTRO","KJs-asf_$5/*456sdf");
//-----------------------------------------//	
if(DEBUG){ var_dump($_POST);}

if($_POST)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	$clave_archivo=mysqli_real_escape_string($conexion_mysqli, $_POST["clave_archivo"]);
	$clave_registro=mysqli_real_escape_string($conexion_mysqli, $_POST["clave_registro"]);
	$nombre_modulo=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_modulo"]);
	$categoria_modulo=mysqli_real_escape_string($conexion_mysqli, $_POST["categoria_modulo"]);
	//------------------------------------------------------------------------------------//
	//verificar clave archivo
	if(!empty($clave_archivo))
	{
		$cons_1="SELECT COUNT(clave_archivo) FROM okalis_archivos WHERE clave_archivo='$clave_archivo'";
		$sqli_1=$conexion_mysqli->query($cons_1) or die($conexion_mysqli->error);
			$AR=$sqli_1->fetch_row();
			$coincidencias_clave_archivo=$AR[0];
			if(empty($coincidencias_clave_archivo)){ $coincidencias_clave_archivo=0;}
		$sqli_1->free();
		if($coincidencias_clave_archivo>0){$permitir_clave_archivo=false; if(DEBUG){ echo"Clave Archivo Incorrecta<br>";} $error=2;}
		else{ $permitir_clave_archivo=true; if(DEBUG){ echo"Clave Archivo OK<br>";}}
	}
	else
	{
		$permitir_clave_archivo=false; 
		if(DEBUG){ echo"Clave Archivo Vacia<br>";}
		$error=1;
	}
	//---------------------------------------------------------------------------------//	
	
	//------------------------------------------------------------------------------------//
	//verificar nombre_modulo
	if(!empty($nombre_modulo))
	{
		$cons_2="SELECT COUNT(nombre_modulo) FROM okalis_archivos WHERE nombre_modulo='$nombre_modulo'";
		$sqli_2=$conexion_mysqli->query($cons_2) or die($conexion_mysqli->error);
			$NM=$sqli_2->fetch_row();
			$coincidencias_nombre_modulo=$NM[0];
			if(empty($coincidencias_nombre_modulo)){ $coincidencias_nombre_modulo=0;}
		$sqli_2->free();
		if($coincidencias_nombre_modulo>0){$permitir_nombre_modulo=false; if(DEBUG){ echo"Nombre Modulo incorrecto<br>";} $error=4;}
		else{ $permitir_nombre_modulo=true; if(DEBUG){ echo"Nombre Modulo OK<br>";}}
	}
	else
	{
		$permitir_nombre_modulo=false;
		if(DEBUG){ echo"Nombre Modulo Vacio<br>";}
		$error=3;
	}
	//---------------------------------------------------------------------------------//	
	
	//------------------------------------------------------------------------------------//
	//verificar clave registro
	if($clave_registro==CLAVE_REGISTRO){ $permitir_clave_registro=true; if(DEBUG){ echo"Clave Registro OK<br>";}}
	else{ $permitir_clave_registro=false; $error=5; if(DEBUG){ echo"Clave Registro Incorrecta<br>";}}
	//---------------------------------------------------------------------------------//	
	
	if(($permitir_clave_archivo)and($permitir_clave_registro)and($permitir_nombre_modulo))
	{
		//guardo datos del modulo
			$cons_IN="INSERT INTO okalis_archivos (clave_archivo, nombre_modulo, categoria) VALUES ('$clave_archivo', '$nombre_modulo', '$categoria_modulo')";
			if(DEBUG){ echo"---> $cons_IN<br>"; $id_archivo="id_archivo";}
			else{$conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error); $id_archivo=$conexion_mysqli->insert_id;}
			
			$url="../relacion_usuario_archivo/relacion_usuario_archivo_1.php?id_archivo=".base64_encode($id_archivo);
			
	}
	else
	{
		$url="registra_clave_archivo_1.php?clave_archivo=".base64_encode($clave_archivo)."&error=$error";
	}
	
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{}
?>