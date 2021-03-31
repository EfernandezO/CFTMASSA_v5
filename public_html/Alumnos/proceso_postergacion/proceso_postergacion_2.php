<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->proceso_postergacion_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$error="";
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		
		
		$postergacion_id=mysqli_real_escape_string($conexion_mysqli, $_POST["postergacion_id"]);
		$postergacion_motivo=mysqli_real_escape_string($conexion_mysqli, $_POST["postergacion_motivo"]);
		$postergacion_descripcion=strtolower(mysqli_real_escape_string($conexion_mysqli, $_POST["postergacion_descripcion"]));
		$postergacion_semestres_suspencion=mysqli_real_escape_string($conexion_mysqli, $_POST["semestres_suspencion"]);
		$postergacion_semestres=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
		$postergacion_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		
		
		if(is_numeric($postergacion_id))
		{
			if($postergacion_id>0)
			{$existe_proceso_postergacion=true;}
			else
			{$existe_proceso_postergacion=false;}
		}
		else
		{$existe_proceso_postergacion=false;}
	}
		
	if($existe_proceso_postergacion)	
	{$cons_1="UPDATE proceso_postergacion SET motivo='$postergacion_motivo', semestres_suspencion='$postergacion_semestres_suspencion', observacion='$postergacion_descripcion', fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual', semestre_postergacion='$postergacion_semestres', year_postergacion='$postergacion_year' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_postergacion='$postergacion_id' LIMIT 1"; if(DEBUG){ echo"Actualiza Registro Postergacion<br>";}
	$error="RA";
	$evento="Actualiza Proceso de Postergacion Alumno $id_alumno carrera $id_carrera";
	}
	else
	{ $cons_1="INSERT INTO proceso_postergacion (id_alumno, id_carrera, semestre_postergacion, year_postergacion, motivo, semestres_suspencion, observacion, fecha_generacion, cod_user) VALUES ('$id_alumno', '$id_carrera', '$postergacion_semestres', '$postergacion_year', '$postergacion_motivo', '$postergacion_semestres_suspencion', '$postergacion_descripcion', '$fecha_hora_actual', '$id_usuario_actual')"; if(DEBUG){ echo"CREA REGISTRO POstergacion<br>";} $error="RC"; 	 $evento="Crea Proceso Postergacion Alumno $id_alumno carrera $id_carrera";}
	
	if(DEBUG){ echo"--->$cons_1<br>";}
	else
	{
		if($conexion_mysqli->query($cons_1))
		{
			if(DEBUG){ echo"Consulta ejecutada bien<br>";}
			$error.="0";
			REGISTRA_EVENTO($evento);
			$tipo_registro="Retiro";
			$descripcion="Creacion Proceso Postergacion, Cambio condicion Alumno a Postergado";
			REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
			///cambio de situacion a alumno
			$cons_A="UPDATE alumno SET situacion='P' WHERE id='$id_alumno' LIMIT 1";
			$conexion_mysqli->query($cons_A);
		}
		else
		{
			if(DEBUG){ echo"Consulta NO ejecutada<br>";}
			$error.="1";
		}
	}
		
	$conexion_mysqli->close();	
	
	$url="proceso_postergacion_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ echo"Sin Datos...<br>";}
?>