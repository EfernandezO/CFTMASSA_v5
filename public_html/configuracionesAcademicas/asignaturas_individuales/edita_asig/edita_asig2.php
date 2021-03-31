<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	$mostrar=true;	
	$error="D";
	//limpio variables
	if(DEBUG){ var_export($_POST); echo"<br>";}
	
		$id_asig=mysql_real_escape_string($_POST["id_asig"]);
		$nombre_asignatura=mysql_real_escape_string($_POST["nombre_asignatura"]);
		$fsede=mysql_real_escape_string($_POST["fsede"]);
		$nivel=mysql_real_escape_string($_POST["nivel"]);
		$array_carrera=mysql_real_escape_string($_POST["fcarrera"]);
		$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
		
	if(is_numeric($id_asig))
	{
		$cons_up="UPDATE asignatura SET asignatura='$nombre_asignatura', sede='$fsede', id_carrera='$id_carrera', carrera='$carrera', nivel='$nivel' WHERE id='$id_asig' AND sede='$fsede' LIMIT 1";
		
		if(DEBUG){echo"$cons_up<br>";}
		else
		{
			if(mysql_query($cons_up))
			{$error="AI0";}
			else
			{
				$error="AI1";
				if($mostrar)
				{echo"ERROR <br>".mysql_error();}
			}
		}
	}
	else
	{
		if(DEBUG){ echo"id_asignatura NO numerico<br>";}
		$error="AI2";
	}	
	
	mysql_close($conexion);
	if(DEBUG){ echo"FIN<br>Error: $error<br>";}
	else{header("location: ../lista_asignaturas_individuales.php?error=$error&id_carrera=$id_carrera&sede=$fsede");}
}
else
{
	if(DEBUG){ echo"Error<br>";}
	else{header("location: ../../../varios/error.php");}
}
?>