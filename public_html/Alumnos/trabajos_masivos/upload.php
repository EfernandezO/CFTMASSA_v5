<?php
	// grabo archivos 
	$path_pruebas="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
	$cargar=false;
	if (isset($_FILES["archivo_carga"]) && is_uploaded_file($_FILES["archivo_carga"]["tmp_name"]) && $_FILES["archivo_carga"]["error"] == 0) 
	{
		include("../../../funciones/funciones_varias.php");
		include("../../../funciones/funcion.php");
		//obtengo extencion
		$tipo_archivo=strtolower(end(explode(".",$_FILES["archivo_carga"]["name"])));
		//nombre del temporal cargado
		$temporal=$_FILES["archivo_carga"]["tmp_name"];
		//obtengo nombre del archivo
		$array_nombre=explode(".",$_FILES["archivo_carga"]["name"]);
		//armo nombre para archivo
		$nombre_archivo=CARACTERES_RAROS(strtolower($array_nombre[0]))."_(".rand(1111, 9999).").".$tipo_archivo;
		//var_dump($_FILES);
		//echo "----->$tipo_archivo-<br>";
		switch($tipo_archivo)
		{
			case"xls":
				$cargar=true;
				break;	
			case"xlsx":
				$cargar=true;
				break;			
				
		}
		if($cargar)
		{
			$ruta=$path_pruebas.$nombre_archivo;
			//echo"$ruta<br>$temporal<br>";
			if(move_uploaded_file($temporal,$ruta))
			{
				echo"$ruta";
				//sleep(10);
			}
			else
			{
				echo"fallo_al_cargar";
			}
		}
		else
		{echo"archivo_invalido";}
	}
	
	exit(0);	// If there was an error we don't return anything and the webpage will have to deal with it.
?>