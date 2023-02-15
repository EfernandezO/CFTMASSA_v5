<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("agregaObservacionAlumno");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	//var_export($_POST);
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	/////////////////////////////////
	$id_alumno=$_POST["id_alumno"];
	$observacion=$_POST["observacion"];
	$origen=$_POST["origen"];
	$tipo_visualizacion=$_POST["tipo_visualizacion"];
	
	if(empty($tipo_visualizacion)){ $tipo_visualizacion="publica";}
	
	
	
	require("../../../../funciones/conexion_v2.php");
	$campos="id_alumno, observacion, fecha, id_user, tipo_visualizacion";
	$valores="'$id_alumno', '$observacion', '$fecha_actual', '$id_user_activo', '$tipo_visualizacion'";
	$cons_IN="INSERT INTO hoja_vida ($campos) VALUES ($valores)";
	
	if(DEBUG){echo"-- > $cons_IN<br>";}
	else
	{
		if($conexion_mysqli->query($cons_IN))
		{ $error=0;}
		else
		{ $error=1;}
	}
	$conexion_mysqli->close();
	
	
	if($origen=="nueva_observacion_MIN")
	{ $url_destino="nva_observacion_MIN_FIN.php?error=$error";}
	else
	{ $url_destino="../hoja_vida.php?id_alumno=$id_alumno&error=$error";}
	if(DEBUG){ echo"FIN: $url_destino<br>";}
	else{header("location: $url_destino");}
}
else
{
	header("location: ../seleccion_alumno.php");
}
?>