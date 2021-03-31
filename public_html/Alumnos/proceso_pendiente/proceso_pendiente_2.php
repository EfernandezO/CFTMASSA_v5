<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_Pendiente_alumno_v1");
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
		
		$pendiente_semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
		$pendiente_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		
		$pendiente_id=mysqli_real_escape_string($conexion_mysqli, $_POST["pendiente_id"]);
		$pendiente_motivo=mysqli_real_escape_string($conexion_mysqli, $_POST["pendiente_motivo"]);
		$pendiente_descripcion=strtolower(mysqli_real_escape_string($conexion_mysqli, $_POST["pendiente_descripcion"]));
		
		
		if(is_numeric($pendiente_id))
		{
			if($pendiente_id>0)
			{$existe_proceso_retiro=true;}
			else
			{$existe_proceso_retiro=false; if(DEBUG){ echo"pendiente_id 0<br>";}}
		}
		else
		{$existe_proceso_retiro=false;}
	}
		
	if($existe_proceso_retiro)	
	{$cons_1="UPDATE proceso_pendiente SET motivo='$pendiente_motivo', observacion='$pendiente_descripcion', semestre='$pendiente_semestre', year='$pendiente_year', fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_pendiente='$pendiente_id' LIMIT 1"; if(DEBUG){ echo"Actualiza Registro PENDIENTE<br>";}
	$error="RA";
	$evento="Actualiza Proceso de PENDIENTE Alumno $id_alumno carrera $id_carrera";
	}
	else
	{ $cons_1="INSERT INTO proceso_pendiente (id_alumno, id_carrera, motivo, observacion, semestre, year, fecha_generacion, cod_user) VALUES ('$id_alumno', '$id_carrera', '$pendiente_motivo', '$pendiente_descripcion', '$pendiente_semestre', '$pendiente_year', '$fecha_hora_actual', '$id_usuario_actual')"; if(DEBUG){ echo"CREA REGISTRO PENDIENTE<br>";} $error="RC"; $evento="Crea Proceso PENDEINTE Alumno $id_alumno carrera $id_carrera";}
	
	if(DEBUG){ echo"--->$cons_1<br>";}
	else
	{
		if($conexion_mysqli->query($cons_1))
		{
			if(DEBUG){ echo"Consulta ejecutada bien<br>";}
			$error.="0";
			REGISTRA_EVENTO($evento);
			$tipo_registro="Pendiente";
			$descripcion="Creacion Proceso Pendiente, Cambio condicion Alumno a Pendiente";
			REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
			///cambio de situacion a alumno
			$cons_A="UPDATE alumno SET situacion='PENDIENTE' WHERE id='$id_alumno' LIMIT 1";
			$conexion_mysqli->query($cons_A);
		}
		else
		{
			if(DEBUG){ echo"Consulta NO ejecutada<br>";}
			$error.="1";
			//echo"ERROR.".$conexion_mysqli->error;
		}
	}
		
	@mysql_close($conexion);
	$conexion_mysqli->close();	
	
	$url="proceso_pendiente_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ echo"Sin Datos...<br>";}
?>