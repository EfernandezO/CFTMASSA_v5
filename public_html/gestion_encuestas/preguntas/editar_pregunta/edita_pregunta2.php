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
		$id_pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_pregunta"]);
		$pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["pregunta"]);
		$tipo_pregunta=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo"]);
		
		$posicion=mysqli_real_escape_string($conexion_mysqli, $_POST["posicion"]);
		
		$quitar_p=$_POST["quitar_p"];
		
		if($quitar_p=="si")
		{
			$pregunta=substr($pregunta,3);
			if(DEBUG){echo"|$pregunta|<br>";}
			$largo_pregunta=strlen($pregunta);
			$pregunta=substr($pregunta,0,$largo_pregunta-4);
		}
		
		
		if($continuar)
		{
			$cons_UP="UPDATE encuestas_pregunta SET posicion='$posicion', pregunta='$pregunta', tipo='$tipo_pregunta' WHERE id_pregunta='$id_pregunta' AND id_encuesta='$id_encuesta' LIMIT 1";
			if(DEBUG){ echo"UP --->$cons_UP<br>";}
			else
			{ 
				$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
				 ///////////////registr evento/////////////////////
				 include("../../../../funciones/VX.php");
				 $evento="Edita Pregunta de encuesta id_encuesta: $id_encuesta id_pregunta: $id_pregunta";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////////////////////////////////
			}
			
			
			
			$url="../ver_preguntas.php?id_encuesta=$id_encuesta&error=M0";
			if(DEBUG){ echo"FIN<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		else
		{
			$url="edita_pregunta1.php?id_encuesta=$id_encuesta&id_pregunta=$id_pregunta&error=$error";
			if(DEBUG){ echo"ERROR: $error<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		
	@mysql_close($conexion);	
	$conexion_mysqli->close();
}
else
{ header("location: ../ver_preguntas.php");}
//////////*****/////////
?>