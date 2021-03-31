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
	include("../../../../funciones/conexion_v2.php");	
	if(DEBUG){ var_export($_POST); echo"<br><br>";}
	
		$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
		$pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["pregunta"]);
		$tipo=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo"]);
		$posicion=mysqli_real_escape_string($conexion_mysqli, $_POST["posicion"]);
		$quitar_p=$_POST["quitar_p"];
		
		if($quitar_p=="si")
		{
			$pregunta=substr($pregunta,3);
			if(DEBUG){echo"|$pregunta|<br>";}
			$largo_pregunta=strlen($pregunta);
			$pregunta=substr($pregunta,0,$largo_pregunta-4);
		}
		
		$cons_INP="INSERT INTO encuestas_pregunta (id_encuesta, pregunta, tipo, posicion) VALUES ('$id_encuesta', '$pregunta', '$tipo', '$posicion')";
		
		if(DEBUG){ echo"--->$cons_INP<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_INP)or die($conexion_mysqli->error);
			$id_pregunta_new=$conexion_mysqli->insert_id;
			 ///////////////registr evento/////////////////////
			 include("../../../../funciones/VX.php");
			 $evento="Agrega Pregunta id_pregunta: $id_pregunta_new a la Encuesta id_encuesta: $id_encuesta";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////////////////////////////////
		}
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="../ver_preguntas.php?id_encuesta=$id_encuesta&error=$error";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}	
//////////*****/////////
?>