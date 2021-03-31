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
if($_GET)
{
	
	if((isset($_GET["id_encuesta"]))and(isset($_GET["id_pregunta"]))and(isset($_GET["id_pregunta_hija"])))
	{
		$id_encuesta=$_GET["id_encuesta"];
		$id_pregunta=$_GET["id_pregunta"];
		$id_pregunta_hija=$_GET["id_pregunta_hija"];
		
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta))and(is_numeric($id_pregunta_hija)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}

if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_GET); echo"<br><br>";}
	
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_GET["id_encuesta"]);
	$id_pregunta=mysqli_real_escape_string($conexion_mysqli, $_GET["id_pregunta"]);
	$id_pregunta_hija=mysqli_real_escape_string($conexion_mysqli, $_GET["id_pregunta_hija"]);
	
	/////////
	//borrar registro de ramo
	$cons_B="DELETE FROM encuestas_pregunta_hija WHERE id_pregunta_hija='$id_pregunta_hija' AND id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' LIMIT 1";
	if(DEBUG){ echo"1.-$cons_B<br>";}
	else
	{
			$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
			 ///////////////registr evento/////////////////////
			 include("../../../../funciones/VX.php");
			 $evento="Elimina Alternatica id_pregunta_hija: $id_pregunta_hija de Pregunta id_pregunta: $id_pregunta de la encuesta id_encuesta: $id_encuesta";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////////////////////////////////
	}
	//////
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	
	$url="../ver_preguntas_hijas.php?id_encuesta=$id_encuesta&id_pregunta=$id_pregunta&error=E0";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../ver_preguntas_hijas.php");}
?>