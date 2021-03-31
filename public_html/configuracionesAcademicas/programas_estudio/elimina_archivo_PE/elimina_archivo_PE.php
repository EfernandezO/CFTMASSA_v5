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
if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	
	$id_carrera=$_GET["id_carrera"];
	$cod_asignatura=$_GET["cod_asignatura"];
	$id_programa_archivo=$_GET["id_programa_archivo"];
	
	if(is_numeric($id_carrera)){ $continuar_1=true;}
	else{ $continuar_1=false;}
	
	if(is_numeric($cod_asignatura)){ $continuar_2=true;}
	else{ $continuar_2=false;}
	
	if(is_numeric($id_programa_archivo)){ $continuar_3=true;}
	else{ $continuar_3=false;}
	
	
	if($continuar_1 and $continuar_2 and $continuar_3)
	{
		$ruta="../../../CONTENEDOR_GLOBAL/programa_estudios/";
		
		
		$cons="SELECT * FROM programa_estudio_archivo WHERE id_programa_archivo='$id_programa_archivo' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$A=$sqli->fetch_assoc();
			$A_archivo=$A["archivo"];
		$sqli->free();
		
		
		@unlink($ruta.$A_archivo);
		
		$cons_D="DELETE FROM programa_estudio_archivo WHERE id_programa_archivo='$id_programa_archivo' LIMIT 1";
		if(DEBUG){ echo"--->$cons_D<br>";}
		else{ $conexion_mysqli->query($cons_D);}
		
		
		$evento="Elimina Archivo Programa Estudio id_carrera: $id_carrera Cod_asignatura: $cod_asignatura";
		REGISTRA_EVENTO($evento);
		
		$conexion_mysqli->close();
		
		$url="../carga_archivo_PE/carga_archivo_PE1.php?id_carrera=$id_carrera&cod_asignatura=$cod_asignatura";
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
	}
	else
	{
		echo"Sin Acceso<br>";
	}
	
}

?>