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

if(DEBUG){ var_dump($_POST);}

if($_POST)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	
	$id_programa=$_POST["id_programa"];
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$sede=$_POST["sede"];
	$numero_unidad=$_POST["numero_unidad"];
	$nombre_unidad=$_POST["nombre_unidad"];
	$cantidad_horas=$_POST["cantidad_horas"];
	$contenido=$_POST["contenido"];
	$tipo=$_POST["tipo"];
	
	$fecha_actual=date("Y-m-d");
	$cod_user=$_SESSION["USUARIO"]["id"];
	
	$cons_UP="UPDATE programa_estudio SET numero_unidad='$numero_unidad', tipo='$tipo', nombre_unidad='$nombre_unidad', cantidad_horas='$cantidad_horas', contenido='$contenido', fecha_generacion='$fecha_actual', cod_user='$cod_user' WHERE id_programa='$id_programa' LIMIT 1";
	
	if(DEBUG){ echo"--->$cons_UP<br>";}
	else
	{
		if($conexion_mysqli->query($cons_UP)) 
		{ 
			$error="PE4";
			$evento="Edita contenido Programa de Estudio id_carrera:$id_carrera cod_asignatura:$cod_asignatura";
			@REGISTRA_EVENTO($evento);
		}
		else
		{ $error="PE5";}
	}
	
	$url="edita_programa_3.php?error=$error&id_carrera=$id_carrera&cod_asignatura=$cod_asignatura";
	
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}
?>