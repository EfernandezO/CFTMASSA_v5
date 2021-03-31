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
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$sede=$_POST["sede"];
	$numero_unidad=$_POST["numero_unidad"];
	$nombre_unidad=$_POST["nombre_unidad"];
	$cantidad_horas=$_POST["cantidad_horas"];
	$contenido=$_POST["contenido"];
	
	$fecha_actual=date("Y-m-d");
	$cod_user=$_SESSION["USUARIO"]["id"];
	
	$campos="id_carrera, cod_asignatura, numero_unidad, nombre_unidad, cantidad_horas, contenido, fecha_generacion, cod_user";
	$valores="'$id_carrera', '$cod_asignatura', '$numero_unidad', '$nombre_unidad', '$cantidad_horas', '$contenido', '$fecha_actual', '$cod_user'";
	
	$CONS_IN="INSERT INTO programa_estudio ($campos) VALUES ($valores)";
	
	if(DEBUG){ echo"---> $CONS_IN<br>";}
	else
	{
		 if($conexion_mysqli->query($CONS_IN))
		 {$error="PE0";}
		 else{ $error="PE1";}
		 
		 $evento="Agrega Contenido a Programa de Estudio id_carrera: $id_carrera cod_asignatura:$cod_asignatura";
		 @REGISTRA_EVENTO($evento);
	}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="nvo_programa_3.php?error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"Sin Datos<br>";}
}

?>