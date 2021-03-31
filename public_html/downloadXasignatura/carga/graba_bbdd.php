<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
	define("DEBUG", false);
 if (!$_FILES) 
 { 
 	//datos no recibidos
	header("location: index.php?error=2");		
 }
 else 
 {
 	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_varias.php");
 
 	if(DEBUG){ var_dump($_POST); $error="DEBUG";}
	///////////////////////////////////
	$tabla="contenedor_archivos";
	$path_pruebas="../../CONTENEDOR_GLOBAL/cargaXasignatura/";
	$GRABAR=true;
	
	$cargar=false;
	///////////////////////////////////////
	//obtengo extencion
	@$tipo_archivo=strtolower(end(explode(".",$_FILES["archivo"]["name"])));
	//nombre del temporal cargado
	$temporal=$_FILES["archivo"]["tmp_name"];
	//obtengo nombre del archivo
	$array_nombre=explode(".",$_FILES["archivo"]["name"]);
	//armo nombre para archivo
	$nombre_archivo=md5(CARACTERES_RAROS(strtolower($array_nombre[0])))."_(".rand(1111, 9999).").".$tipo_archivo;
	//var_dump($_FILES);
	//echo "----->$tipo_archivo-<br>";
	switch($tipo_archivo)
	{
		case"doc":
			$cargar=true;
			break;
		case"pdf":
			$cargar=true;
			break;		
		case"docx":
			$cargar=true;
			break;	
		case"xls":
			$cargar=true;
			break;	
		case"xlsx":
			$cargar=true;
			break;	
		case"ppt":
			$cargar=true;
			break;
		case"pptx":
			$cargar=true;
			break;						
			
	}
	if($cargar)
	{
		$ruta=$path_pruebas.$nombre_archivo;
		//echo"$ruta<br>$temporal<br>";
		if(move_uploaded_file($temporal,$ruta))
		{$archivoCargado=true;}
		else
		{$archivoCargado=false;}
	}
	else
	{$archivoCargado=false;}
	
	
	/////////////////////////
	if(!$archivoCargado){ $GRABAR=false;}
	
	$sede=mysqli_real_escape_string($conexion_mysqli,$_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli,$_POST["carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli,$_POST["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli,$_POST["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli,$_POST["grupo_curso"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli,$_POST["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli,$_POST["year"]);
  	$fecha_X=date("Y-m-d");
	$titulo=mysqli_real_escape_string($conexion_mysqli,$_POST["titulo"]);
	


	
	$descripcion=str_inde($_POST["descripcion"]);
	
	$seccion="archivosXasignatura";
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];

	$campos="seccion, semestre, year, grupo_curso, jornada, titulo, descripcion, id_carrera, cod_asignatura, sede, archivo, fecha_generacion, cod_user";
	$valores="'$seccion', '$semestre', '$year', '$grupo_curso', '$jornada', '$titulo', '$descripcion', '$id_carrera', '$cod_asignatura', '$sede', '$nombre_archivo', '$fecha_X', '$id_usuario_activo'";
	$cons="INSERT INTO $tabla ($campos) VALUES($valores)";
	if(DEBUG){ echo"<br><br>--> $cons<br>";}
	
	
	
	if($GRABAR)
	{
		if(DEBUG){ echo"GRABANDO EN BBDD...";}
		else
		{
			if($conexion_mysqli->query($cons))
			{$error=0;}
			else
			{$error=1;}
		}
	}
	else{
			if(DEBUG){ echo"No se INICIO el PROCESO DE GRABACION en la BBDD...<br>";}
			 $error=2;
			}
	/////////////////////////////////
	$conexion_mysqli->close();
	$url="index.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else
	{ header("location: $url");}
  }
?>