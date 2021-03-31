<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
	
// Check for a degraded file upload, this means SWFUpload did not load and the user used the standard HTML upload
$used_degraded = false;
$resume_id = "";
if (isset($_FILES["resume_degraded"]) && is_uploaded_file($_FILES["resume_degraded"]["tmp_name"]) && $_FILES["resume_degraded"]["error"] == 0) {
    $resume_id = $_FILES["resume_degraded"]["name"];
    $used_degraded = true;
}

// Check for the file id we should have gotten from SWFUpload
if (isset($_POST["hidFileID"]) && $_POST["hidFileID"] != "" )
 {
	$resume_id = $_POST["hidFileID"];
 }

 if ($resume_id == "") 
 { 
		$error=2;
 }
 else
 { 
		$error=0;
		if ($used_degraded) 
		{
			$error=1;
		 } 
		
 } 
  //echo "E-> $error<br>";
if(DEBUG){var_dump($_POST);}
 if($error==0)
 {	
 	include("../../../../../funciones/conexion.php");
	include("../../../../../funciones/funcion.php");
		$titulo=str_inde($_POST["titulo"]);
		$id_libro=str_inde($_POST["id_libro"]);
		$archivo_asociado=$_POST["hidFileID"];
		$fecha_mysql=date("Y-m-d");
		@$extencion=end(explode(".",$archivo_asociado));
		switch ($extencion)
		{
			case "pdf":
				$tipo_archivo="pdf";
				break;
			case "jpg":
				$tipo_archivo="imagen";
				break;
			case "jpeg":
				$tipo_archivo="imagen";
				break;		
			default:
				$tipo_archivo="NN";
				break;		
		}
		
		$cons_in="INSERT INTO biblioteca_asociados (id_libro, titulo, tipo_archivo, archivo, fecha) VALUES('$id_libro', '$titulo', '$tipo_archivo', '$archivo_asociado', '$fecha_mysql')";
		if(DEBUG){echo "$cons_in<br>";}
		if(!mysql_query($cons_in))
		{
			$error=3;
			echo mysql_error();
		}
		else
		{
			 /////Registro ingreso///
		 	include("../../../../../funciones/VX.php");
		 	$evento="Agrega archivo Asociado Libro -> $id_libro ($tipo_archivo)";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////
		}

	mysql_close($conexion);
 }
 if(DEBUG){}
 else
	{@header("location: index.php?error=$error&id_libro=$id_libro");}
 ?>