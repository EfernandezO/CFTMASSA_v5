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
	$continuar=true;
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_POST); echo"<br>";}
	
		$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
		$nombre=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre"]);
		$descripcion=mysqli_real_escape_string($conexion_mysqli, $_POST["descripcion"]);
		$visible_alumno=mysqli_real_escape_string($conexion_mysqli, $_POST["visible_alumno"]);
		$visible_exalumno=mysqli_real_escape_string($conexion_mysqli, $_POST["visible_exalumno"]);
		$visible_docente=mysqli_real_escape_string($conexion_mysqli, $_POST["visible_docente"]);
		$visible_jefe_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["visible_jefe_carrera"]);
		
		$utilizar_para_evaluacion_docente=mysqli_real_escape_string($conexion_mysqli, $_POST["utilizar_para_evaluacion_docente"]);
		$utilizar_para_evaluacion_JC_D=mysqli_real_escape_string($conexion_mysqli, $_POST["utilizar_jefecarrera_docente"]);
		$utilizar_para_evaluacion_JC=mysqli_real_escape_string($conexion_mysqli, $_POST["utilizar_JC"]);
		$utilizar_para_autoevaluacion_docente=mysqli_real_escape_string($conexion_mysqli, $_POST["utilizar_para_autoevaluacion_docente"]);
		
		if($continuar)
		{
			$cons_UP="UPDATE encuestas_main SET visible_alumno='$visible_alumno', visible_exalumno='$visible_exalumno', visible_docente='$visible_docente', visible_jefe_carrera='$visible_jefe_carrera', nombre='$nombre', descripcion='$descripcion', utilizar_para_evaluacion_docente='$utilizar_para_evaluacion_docente', utilizar_para_evaluacion_JC_D='$utilizar_para_evaluacion_JC_D', utilizar_para_evaluacion_JC='$utilizar_para_evaluacion_JC', utilizar_para_autoevaluacion_docente='$utilizar_para_autoevaluacion_docente' WHERE id_encuesta='$id_encuesta' LIMIT 1";
			
			
			///////
			///armo consulta para cambiar estado de las demas encuestas.
			$hay_mas_campos=false;
			if($utilizar_para_evaluacion_docente=="1")
			{$campo_1="utilizar_para_evaluacion_docente='0'"; $hay_mas_campos=true;}
			else{ $campo_1="";}
			
			if($utilizar_para_evaluacion_JC_D=="1")
			{$campo_2="utilizar_para_evaluacion_JC_D='0'"; if($hay_mas_campos){$campo_2=", ".$campo_2;} $hay_mas_campos=true;}
			else{ $campo_2="";}
			
			if($utilizar_para_evaluacion_JC=="1")
			{$campo_4="utilizar_para_evaluacion_JC='0'"; if($hay_mas_campos){$campo_4=", ".$campo_4;} $hay_mas_campos=true;}
			else{ $campo_4="";}
			
			if($utilizar_para_autoevaluacion_docente=="1")
			{$campo_3="utilizar_para_autoevaluacion_docente='0'"; if($hay_mas_campos){$campo_3=", ".$campo_3;} $hay_mas_campos=true;}
			else{ $campo_3="";}
			
			$cons_UP2="UPDATE encuestas_main SET $campo_1 $campo_2 $campo_3 $campo_4 WHERE id_encuesta<>'$id_encuesta'";
			
			
			if(DEBUG){ echo"UP --->$cons_UP<br><br>UP2--->$cons_UP2<br>";}
			else{
					$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
					
					///cambio estado de las demas encuestas 
					if($hay_mas_campos)
					{$conexion_mysqli->query($cons_UP2)or die($conexion_mysqli->error);}
					  ///////////////registr evento/////////////////////
					 include("../../../../funciones/VX.php");
					 $evento="Modifica Encuesta id_encuesta: $id_encuesta";
					 REGISTRA_EVENTO($evento);
					 ///////////////////////////////////////////////////
				}
			
			
			
			$url="../../gestion_encuesta.php?error=M0";
			if(DEBUG){ echo"FIN<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		else
		{
			$url="edita_encuesta1.php?id_encuesta=$id_encuesta&error=$error";
			if(DEBUG){ echo"ERROR: $error<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		
	@mysql_close($conexion);	
	$conexion_mysqli->close();
}
else
{ header("location: ../../gestion_encuesta.php");}
//////////*****/////////
?>