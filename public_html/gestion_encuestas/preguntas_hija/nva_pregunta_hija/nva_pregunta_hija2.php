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
	$error="G0";
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	require("../../../../funciones/conexion_v2.php");	
	if(DEBUG){ var_dump($_POST); echo"<br><br>";}
	
		$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
		$id_pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_pregunta"]);
		$contenido=mysqli_real_escape_string($conexion_mysqli, $_POST["contenido"]);
		$quitar_p=mysqli_real_escape_string($conexion_mysqli, $_POST["quitar_p"]);
		$respuesta_anexa=mysqli_real_escape_string($conexion_mysqli, $_POST["respuesta_anexa"]);
		
		//numero de preguntas que van
		$consN="SELECT COUNT(id_pregunta_hija) FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
		$sqliN=$conexion_mysqli->query($consN);
		$NP=$sqliN->fetch_row();
		$numeroPreguntas=$NP[0];
		if(empty($numeroPreguntas)){$numeroPreguntas=0;}
		$sqliN->free();
		$posicion=$numeroPreguntas+1;
		
		if($quitar_p=="si")
		{
			$contenido=substr($contenido,3);
			if(DEBUG){echo"|$contenido|<br>";}
			$largo_contenido=strlen($contenido);
			$contenido=substr($contenido,0,$largo_contenido-4);
		}
		
		$cons_INP="INSERT INTO encuestas_pregunta_hija (id_encuesta, id_pregunta, posicion, respuesta_anexa, contenido, cod_user) VALUES ('$id_encuesta', '$id_pregunta', '$posicion', '$respuesta_anexa', '$contenido', '$id_usuario_actual')";
		
		if(DEBUG){ echo"--->$cons_INP<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_INP)or die($conexion_mysqli->error);
			$id_pregunta_hija_new=$conexion_mysqli->insert_id;
			 ///////////////registr evento/////////////////////
			 include("../../../../funciones/VX.php");
			 $evento="Agrega Alternativa id_pregunta_hija: $id_pregunta_hija_new a Pregunta id_pregunta: $id_pregunta de la encuesta id_encuesta: $id_encuesta";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////////////////////////////////
		}
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="../ver_preguntas_hijas.php?id_encuesta=$id_encuesta&id_pregunta=$id_pregunta&error=$error";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}	
//////////*****/////////
?>