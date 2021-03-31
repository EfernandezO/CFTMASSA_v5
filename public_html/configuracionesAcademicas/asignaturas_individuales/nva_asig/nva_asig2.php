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
	include("../../../../funciones/funcion.php");
	include("../../../../funciones/conexion.php");
	
	if(DEBUG){var_export($_POST); echo"<br>";}
	
		$nivel=mysql_real_escape_string($_POST["nivel"]);
		$nombre_asignatura=mysql_real_escape_string($_POST["nombre_asignatura"]);
		$array_carrera=mysql_real_escape_string($_POST["carrera"]);
		$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
		$fsede=mysql_real_escape_string($_POST["fsede"]);
	
	$error=1;
	$mostrar=0;
	
	if(($nombre_asignatura!="")and($fsede!="")and($carrera!=""))
	{
		//buscamos que no este
		$cons="SELECT * FROM asignatura WHERE asignatura='$nombre_asignatura' AND sede='$fsede' AND id_carrera='$id_carrera'";
		$sql=mysql_query($cons)or die(mysql_error());
		$num_asig=mysql_num_rows($sql);
		if(DEBUG){ echo"$cons<br>Num coincidencias: $num_asig<br>";}
		mysql_free_result($sql);
		if($num_asig>0)
		{
			//asignatura existe
			$error="AI3";
		}
		else
		{
			//asignatura no esta en bdd
			$cons_in="INSERT INTO asignatura (id_carrera, carrera, nivel, asignatura, sede) VALUES('$id_carrera', '$carrera', '$nivel', '$nombre_asignatura', '$fsede')";
			if(DEBUG){ echo"$cons_in<br>";}
			else
			{
				if(mysql_query($cons_in))
				{$error="AI4";}
				else
				{
					if($mostrar==1)
					{
						echo"Error: ".mysql_error();
					}	
					$error="AI5";
				}
			}
		}
	}
	mysql_close($conexion);
	$url="../lista_asignaturas_individuales.php?error=$error&id_carrera=$id_carrera&sede=$fsede";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}
}
else
{
	//no post
	if(DEBUG){ echo"NO POST<br>";}
	else{header("location: ");}
}
?>