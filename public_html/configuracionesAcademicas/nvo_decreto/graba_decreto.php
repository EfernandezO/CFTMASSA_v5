<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->DECRETOS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{

	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	$error="DG";
	$opcion=mysqli_real_escape_string($conexion_mysqli, $_POST["opcion"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$decreto=mysqli_real_escape_string($conexion_mysqli, $_POST["decreto"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	
	switch($opcion)
	{
		case"actualizar":
			$consX="UPDATE certificados SET decreto='$decreto' WHERE id_carrera='$id_carrera' AND sede='$sede' LIMIT 1";
			$continuar=true;
			break;
		case"insertar":
			$consB="SELECT COUNT(id) FROM certificados WHERE id_carrera='$id_carrera' and sede='$sede'";
			$sqlB=$conexion_mysqli->query($consB)or die($conexion_mysqli->error);
				$DD=$sqlB->fetch_row();
				$coincidencias=$DD[0];
				if(empty($coincidencias)){ $coincidencias=0;}
				if(DEBUG){ echo"$consB<br>N:$coincidencias<br>";}
			$sqlB->free();
				$consX="INSERT INTO certificados (decreto, id_carrera, sede)VALUES('$decreto', '$id_carrera','$sede')";
				if($coincidencias>0){ $continuar=false;}
				else{$continuar=true;}
			break;
		default:
			$continuar=false;	
	}
	//------------------------------------------------------------------------//
	if($continuar)
	{
		if(DEBUG){ echo"$consX<br>";}
		else
		{
			if($conexion_mysqli->query($consX))
			{ 
				$error="D0";
				include("../../../funciones/VX.php");
				$evento="$opcion Decreto en carrera id_carrera:$id_carrera Sede: $sede";
				REGISTRA_EVENTO($evento);
			}
			else
			{ $error="D1";}
		}
	}
	else
	{if(DEBUG){ echo"No Continuar...<br>";}}	
	$conexion_mysqli->close();
	@mysql_close($conexion);
	$url="../index.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
?>
