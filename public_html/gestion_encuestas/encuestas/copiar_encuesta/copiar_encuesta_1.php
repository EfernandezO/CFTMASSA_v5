<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_GET["id_encuesta"]))
{
	if(is_numeric($_GET["id_encuesta"]))
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}

$error="CE0";

if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_GET); echo"<br><br>";}
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_GET["id_encuesta"]);
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];


     ///////////////registr evento/////////////////////
		 include("../../../../funciones/VX.php");
		 $evento="Copia Encuesta id_encuesta: $id_encuesta";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////////////////////////////

	
	///ENCUESTA PRINCIPAL
	//------------------------------------------------------------------------------//
	$cons_1="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
		$A=$sqli->fetch_assoc();
		
		$E_visible_alumno=$A["visible_alumno"];
		$E_visible_exalumno=$A["visible_exalumno"];
		$E_visible_docente=$A["visible_docente"];
		$E_utilizar_para_evaluar_docente=$A["utilizar_para_evaluacion_docente"];
		$E_nombre=$A["nombre"]." [COPIA]";
		$E_descripcion=$A["descripcion"];
		$E_visible_jefe_carrera=$A["visible_jefe_carrera"];
	$sqli->free();	
	
	$cons_IN_1="INSERT INTO encuestas_main (visible_alumno, visible_exalumno, visible_docente,visible_jefe_carrera, utilizar_para_evaluacion_docente, nombre, descripcion, fecha_generacion, cod_user) VALUES ('$E_visible_alumno', '$E_visible_exalumno', '$E_visible_docente', '$E_visible_jefe_carrera', '$E_utilizar_para_evaluar_docente', '$E_nombre', '$E_descripcion', '$fecha_actual', '$id_usuario_actual')";
	
	 echo"COPIANDO ENCUESTAS:<br> -->$cons_IN_1<br>"; 
	
		if($conexion_mysqli->query($cons_IN_1))
		{$encuesta_OK=true; $id_encuesta_new=$conexion_mysqli->insert_id; if(DEBUG){ echo" Encuesta OK<br>";}}
		else
		{ $encuesta_OK=false; $id_encuesta_new="E";  echo"Encuesta ERROR<br>".$conexion_mysqli->error ; $error="CE1";}
	
	
//------------------------------------------------------------------------------------------------------------//	
//ENCUESTA PREGUNTA
//--------------------------------------------------------------------------------------------//
	if($encuesta_OK)
	{
		echo"Buscar Preguntas....<br>";
		$cons_2="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' ORDER by id_pregunta";
		$sqli_P=$conexion_mysqli->query($cons_2)or die($conexion_mysqli->error);
		$num_preguntas=$sqli_P->num_rows;
		 echo"---> $cons_2<br>num_preguntas: $num_preguntas<br>";
		
		if($num_preguntas>0)
		{
			echo"PREGUNTAS<br><br>";
			while($P=$sqli_P->fetch_assoc())
			{
				$id_pregunta=$P["id_pregunta"];
				$P_posicion=$P["posicion"];
				if(empty($P_posicion)){$P_posicion=1;}
				$P_pregunta=mysqli_real_escape_string($conexion_mysqli, $P["pregunta"]);
				$P_pregunta=str_replace("'",'"',$P_pregunta);
				$P_tipo=$P["tipo"];
				
				
				$cons_IN_2="INSERT INTO encuestas_pregunta (id_encuesta, posicion, pregunta, tipo) VALUES ('$id_encuesta_new', '$P_posicion', '$P_pregunta', '$P_tipo')";
				 echo"--------->$cons_IN_2<br>";
				
					if($conexion_mysqli->query($cons_IN_2))
					{ $pregunta_OK=true; echo"Pregunta OK<br>"; $id_pregunta_new=$conexion_mysqli->insert_id;}
					else
					{ $pregunta_OK=false; echo"Pregunta Error<br>".$conexion_mysqli->error; $id_pregunta_new="P"; $error="CE1";}
				
				//---------------------------------------------------------------------------------------//
				
				if($pregunta_OK)
				{
					//PREGUNTAS_HIJA alternativas
					$cons_3="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
					$sqli_HP=$conexion_mysqli->query($cons_3)or die($conexion_mysqli->error);
					$num_preguntas_hija=$sqli_HP->num_rows;
					if($num_preguntas_hija>0)
					{
						 echo"ALTERNATIVAS<br><br>";
						while($PH=$sqli_HP->fetch_assoc())
						{
							$PH_respuesta_anexa=$PH["respuesta_anexa"];
							$PH_contenido=$PH["contenido"];
							
							$cons_IN_3="INSERT INTO encuestas_pregunta_hija (id_encuesta, id_pregunta, respuesta_anexa, contenido, cod_user) VALUES ('$id_encuesta_new', '$id_pregunta_new', '$PH_respuesta_anexa', '$PH_contenido', '$id_usuario_actual')";
							
							echo"=======>$cons_IN_3<br>";
								if($conexion_mysqli->query($cons_IN_3)){ $ALTERNATICA=true;  echo"ALTERNATICA OK<br>";}
								else{ $ALTERNATICA=false; if(DEBUG){ echo"ALTERNATIVA ERROR<br>".$conexion_mysqli->error;} $error="CE1";}
							
						}
					}
				}
				
				
			}
		}
		else
		{ echo"Sin Preguntas para Copiar<br>";}
	}
	
	
	//header("location: ../../gestion_encuesta.php?error=$error");
	
}
else
{ if(DEBUG){ echo"Continuar: no<br>";}else{header("location: ../../gestion_encuesta.php");}}
?>