<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="debug";
if($_GET)
{
	if(isset($_GET["id_carrera"]))
	{
		$id_carrera=$_GET["id_carrera"];
		if(is_numeric($id_carrera)){ $continuar_1=true;}
		else{ $continuar_1=false;}
	}
	else
	{ $continuar_1=false;}
	
	if(isset($_GET["cod_asignatura"]))
	{
		$cod_asignatura=$_GET["cod_asignatura"];
		if(is_numeric($cod_asignatura)){ $continuar_2=true;}
		else{ $continuar_2=false;}
	}
	else
	{ $continuar_2=false;}
	
	if(isset($_GET["id_programa"]))
	{
		$id_programa=$_GET["id_programa"];
		if(is_numeric($id_programa))
		{ $continuar_3=true;}
		else{ $continuar_3=false;}
	}
	else
	{ $continuar_3=false;}
	
	$sede=$_GET["sede"];
	
	if($continuar_1 and $continuar_2 and $continuar_3)
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if($continuar)
	{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/VX.php");
		$cons_DEL="DELETE FROM programa_estudio WHERE id_programa='$id_programa' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' LIMIT 1";
		if(DEBUG){ echo"--->$cons_DEL<br>";}
		else{ 
				if($conexion_mysqli->query($cons_DEL))
				{ 
					$error="PE2";
					$evento="Elimina Contenido Programa de Estudios id_carrera: $id_carrera AND cod_asignatura: $cod_asignatura";
					@REGISTRA_EVENTO($evento);
				}
				else
				{ $error="PE3";}
			}
		
		@mysql_close($conexion);
		$conexion_mysqli->close();
		
		$url="../ver_programa_estudio.php?error=$error&id_carrera=$id_carrera&cod_asignatura=$cod_asignatura&sede=$sede";
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
	}
}
?>