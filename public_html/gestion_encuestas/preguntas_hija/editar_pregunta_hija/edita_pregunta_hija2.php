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
	if(DEBUG){ var_dump($_POST); echo"<br>";}
	
		$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
		$id_pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_pregunta"]);
		$id_pregunta_hija=mysqli_real_escape_string($conexion_mysqli, $_POST["id_pregunta_hija"]);
		$contenido=mysqli_real_escape_string($conexion_mysqli, $_POST["contenido"]);
		$quitar_p=mysqli_real_escape_string($conexion_mysqli, $_POST["quitar_p"]);
		$respuesta_anexa=mysqli_real_escape_string($conexion_mysqli, $_POST["respuesta_anexa"]);
		$posicion=mysqli_real_escape_string($conexion_mysqli, $_POST["posicion"]);
		
		if($quitar_p=="si")
		{
			$contenido=substr($contenido,3);
			if(DEBUG){echo"|$contenido|<br>";}
			$largo_contenido=strlen($contenido);
			$contenido=substr($contenido,0,$largo_contenido-4);
		}
		
		
		if($continuar)
		{
			$cons_UP="UPDATE encuestas_pregunta_hija SET posicion='$posicion', contenido='$contenido', respuesta_anexa='$respuesta_anexa' WHERE id_pregunta_hija='$id_pregunta_hija' AND id_pregunta='$id_pregunta' AND id_encuesta='$id_encuesta' LIMIT 1";
			if(DEBUG){ echo"UP --->$cons_UP<br>";}
			else
			{ 
				$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
				 ///////////////registr evento/////////////////////
				 include("../../../../funciones/VX.php");
				 $evento="Modifica Alternativa id_pregunta_hija: $id_pregunta_hija de Pregunta id_pregunta: $id_pregunta de la encuesta id_encuesta: $id_encuesta";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////////////////////////////////
			}
			
			
			
			$url="../ver_preguntas_hijas.php?id_encuesta=$id_encuesta&id_pregunta=$id_pregunta&error=M0";
			if(DEBUG){ echo"FIN<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		else
		{
			$url="edita_pregunta_hija1.php?id_encuesta=$id_encuesta&id_pregunta=$id_pregunta&id_pregunta_hija=$id_pregunta_hija&error=$error";
			if(DEBUG){ echo"ERROR: $error<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		
	@mysql_close($conexion);	
	$conexion_mysqli->close();
}
else
{ header("location: ../ver_preguntas_hijas.php");}
//////////*****/////////
?>