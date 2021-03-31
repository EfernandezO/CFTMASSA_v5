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

if(isset($_GET["id_encuesta"]))
{
	if(is_numeric($_GET["id_encuesta"]))
	{ $continuar=true;}
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
	
	/////////
	//borrar registro
	$cons_B="DELETE FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
	$cons_B2="DELETE FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta'";
	$cons_B3="DELETE FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta'";
	
	if(DEBUG){ echo"1.-$cons_B<br>$cons_B2<br>$cons_B3<br>";}
	else
	{
		$conexion_mysqli->query($cons_B)or die("Encuestas ".$conexion_mysqli->error);
		$conexion_mysqli->query($cons_B2)or die("Preguntas".$conexion_mysqli->error);
		$conexion_mysqli->query($cons_B3)or die("Preguntas Hija".$conexion_mysqli->error);
		
		 ///////////////registr evento/////////////////////
		 include("../../../../funciones/VX.php");
		 $evento="Elimina Encuesta id_encuesta: $id_encuesta";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////////////////////////////
	}
	//////
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	
	$url="../../gestion_encuesta.php?error=E0";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ if(DEBUG){ echo"Continuar: no<br>";}else{header("location: ../../gestion_encuesta.php");}}
?>