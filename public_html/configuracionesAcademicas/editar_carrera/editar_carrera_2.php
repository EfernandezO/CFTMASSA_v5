<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MAIN_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	require("../../../funciones/conexion_v2.php");
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$nombre_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_carrera"]);
	$nombre_titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_titulo"]);
	
	$cons_UP="UPDATE carrera SET carrera='$nombre_carrera', nombre_titulo='$nombre_titulo' WHERE id='$id_carrera' LIMIT 1";
	if(DEBUG){ echo"----> $cons_UP<br>";}
	else
	{
		if($conexion_mysqli->query($cons_UP))
		{ 
			$error="EC0";
			include("../../../funciones/VX.php");
			$evento="Modifica Carrera id_carrera: $id_carrera";
			REGISTRA_EVENTO($evento);
		}
		else
		{ $error="EC1";}
	}
	
	$url="../index.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ echo"sin datos<br>";}
?>