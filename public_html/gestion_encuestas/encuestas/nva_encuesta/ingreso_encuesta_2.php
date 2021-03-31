<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
if($_POST)
{
	include("../../../../funciones/funcion.php");
	require("../../../../funciones/conexion_v2.php");	
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$nombre_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_encuesta"]);
	$descripcion_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["descripcion_encuesta"]);
	$continuar=true;
	
	$error="";
	
	//veo si acepto nuevo nombre
	if(($nombre_encuesta=="Sin Registro")or($nombre_encuesta==""))
	{$continuar=false;}
	
	
		if($continuar)
		{
			$error="CC0";
			$consXX="INSERT INTO encuestas_main (visible_alumno, visible_exalumno, visible_docente, visible_jefe_carrera, nombre, descripcion, fecha_generacion, cod_user) VALUES('off', 'off', 'off', 'off', '$nombre_encuesta', '$descripcion_encuesta', '$fecha_actual', '$id_usuario_actual')";
			if(DEBUG){ echo"->$consXX<br>"; $id_carrera_new="D";}
			else
			{
				$conexion_mysqli->query($consXX)or die($conexion_mysqli->error);
				$id_encuesta_new=$conexion_mysqli->insert_id;
				 ///////////////registr evento/////////////////////
				 include("../../../../funciones/VX.php");
				 $evento="Crea Nueva Encuesta id_encuesta: $id_encuesta_new";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////////////////////////////////
			}

			
		}
		else
		{
			if(DEBUG){ echo"No se puede Continuar...<br>";}
			$error="CC1";
		}
		
		@mysql_close($conexion);
		$conexion_mysqli->close();
		if(DEBUG){ echo"Error: $error<br>";}
		else{header("location: ../../gestion_encuesta.php?error=$error");}
}
else
{ header("location: ../../gestion_encuesta.php");}
?>