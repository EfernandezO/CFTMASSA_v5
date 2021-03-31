<?php
	//  save files
	include("../../../../../funciones/funciones_varias.php");
	$path_pdf="../../../../CONTENEDOR_GLOBAL/biblioteca_pdf/";
	$path_img="../../../../CONTENEDOR_GLOBAL/biblioteca_img/";
	
	$cargar=false;
	if (isset($_FILES["archivo_asociado"]) && is_uploaded_file($_FILES["archivo_asociado"]["tmp_name"]) && $_FILES["archivo_asociado"]["error"] == 0) {
		
		//obtengo extencion
		@$tipo_archivo=strtolower(end(explode(".",$_FILES["archivo_asociado"]["name"])));
		//nombre del temporal cargado
		$temporal=$_FILES["archivo_asociado"]["tmp_name"];
		//obtengo nombre del archivo
		$array_nombre=explode(".",$_FILES["archivo_asociado"]["name"]);
		//armo nombre para archivo
		$nombre_archivo=md5("Libro_".microtime()).".".$tipo_archivo;
		
		//var_dump($_FILES);
		//echo "-$tipo_archivo-<br>";
		
		switch ($tipo_archivo)
		{
			case "pdf":
				$cargar=true;
				$ruta=$path_pdf;
				break;
			case "jpeg":
				$cargar=true;
				$ruta=$path_img;
				break;
			case "jpg":
				$cargar=true;
				$ruta=$path_img;
				break;			
		}
		if($cargar)
		{
			$ruta.=$nombre_archivo;
				//echo"$ruta<br>$temporal<br>";
			if(move_uploaded_file($temporal,$ruta))
			{
				echo"$nombre_archivo";
				//sleep(10);
			}
			else
			{
				echo"Fallo al Cargar";
			}
		}
		else
		{echo"Archivo Invalido";}
	}
	
	exit(0);	// If there was an error we don't return anything and the webpage will have to deal with it.
?>