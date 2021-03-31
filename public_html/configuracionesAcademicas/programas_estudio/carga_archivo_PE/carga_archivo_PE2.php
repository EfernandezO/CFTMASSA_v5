<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_dump($_FILES);}
	
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	//-------------------------------------------------------//
	$ruta="../../../CONTENEDOR_GLOBAL/programa_estudios/";//ruta guarda archivos
	//-------------------------------------------------------//
	$nombre_archivo_new="";///no cambiar
	$array_extenciones_permitidas=array("pdf", "doc", "docx");
	
	$archivo=$_FILES["archivo"]["name"];
	$peso=$_FILES["archivo"]["size"];
	$temporal=$_FILES["archivo"]["tmp_name"];
	
	$array_archivo=explode(".",$archivo);
	
	$nombre_archivo=strtolower($array_archivo[0]);
	$extencion_archivo=strtolower(end($array_archivo));
	if(DEBUG){ echo"ARCHIVO: $archivo($peso)<br>Nombre: $nombre_archivo<br>Extencion: $extencion_archivo<br>";}
	///---------------------------------------------------------//
	if(in_array($extencion_archivo, $array_extenciones_permitidas))
	{
		if(DEBUG){ echo"Archivo Valido Continuar<br>";}
		$nombre_archivo_new="PE_".$id_carrera."_".$cod_asignatura."_".rand(1111,9999).".".$extencion_archivo;//nombre nuevo archivo
		$ruta.=$nombre_archivo_new;
		
		if(move_uploaded_file($temporal, $ruta))
		{
			if(DEBUG){ echo"Archivo Cargado: $ruta<br>";}
			$archivo_cargado=true;
			
		}
		else
		{
			if(DEBUG){ echo"Archivo NO cargado Error<br>";}
			$archivo_cargado=false;
			
		}
	}
	else
	{
		if(DEBUG){ echo"Extencion Incorrecta<br>";}
		$archivo_cargado=false;
	}
	//---------------------------------------------------------------------------//
	
	if($archivo_cargado)
	{
		$cons="INSERT INTO programa_estudio_archivo (id_carrera, cod_asignatura, archivo, fecha_generacion, cod_user) VALUES ('$id_carrera', '$cod_asignatura', '$nombre_archivo_new', '$fecha_actual', '$id_usuario_actual')";
		if(DEBUG){ echo"--->$cons<br>";}
		else{ $conexion_mysqli->query($cons);}
		$error="PEA0";
	}
	else
	{ $error="PEA1";}
	//--------------------------------------------------------------------------//
	
	$url="carga_archico_PE3.php?error=$error&id_carrera=$id_carrera&cod_asignatura=$cod_asignatura";
	if(DEBUG){ echo"URL:$url<br>";}
	else{ header("location: $url");}
	
	mysql_close($conexion);
	$conexion_mysqli->close();
	
?>