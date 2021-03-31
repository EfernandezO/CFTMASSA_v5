<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1_EDICION");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)	
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_dump($_POST);}
	$error="debug";
	
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_POST["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada"]);
	$grupo=mysqli_real_escape_string($conexion_mysqli, $_POST["grupo"]);
	$valor_hora=mysqli_real_escape_string($conexion_mysqli, $_POST["valor_hora"]);
	$numero_horas=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_horas"]);
	$total=mysqli_real_escape_string($conexion_mysqli, $_POST["total"]);
	$numero_cuotas=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_cuotas"]);
	$AS_id=mysqli_real_escape_string($conexion_mysqli, $_POST["AS_id"]);
	$estado=mysqli_real_escape_string($conexion_mysqli, $_POST["estado"]);
	
	$asignacion_semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["asignacion_semestre"]);
	$asignacion_year=mysqli_real_escape_string($conexion_mysqli, $_POST["asignacion_year"]);
	
	$fecha_generacion=date("Y-m-d");
	$cod_user=$_SESSION["USUARIO"]["id"];
	
	$cons_UP="UPDATE toma_ramo_docente SET sede='$sede', id_carrera='$id_carrera', cod_asignatura='$cod_asignatura', jornada='$jornada', grupo='$grupo', valor_hora='$valor_hora', numero_horas='$numero_horas', numero_cuotas='$numero_cuotas', total='$total', condicion='$estado', fecha_generacion='$fecha_generacion', cod_user='$cod_user', semestre='$asignacion_semestre', year='$asignacion_year' WHERE id='$AS_id' LIMIT 1";
	
	if(DEBUG){ echo"--->$cons_UP<br>";}
	else{
		if($conexion_mysqli->query($cons_UP))
		{ 
			$error="AS1";
			//------------------------------------------------//
			include("../../../../funciones/VX.php");
			$evento="Modifica Asignaciones id_asignacion: $AS_id sede: $sede id_carrera: $id_carrera cod_asignatura: $cod_asignatura n. cuotas: $numero_cuotas periodo[ $asignacion_semestre - $asignacion_year]";
			REGISTRA_EVENTO($evento);
			
			
			$cons_X="SELECT id_funcionario FROM toma_ramo_docente WHERE id='$AS_id' LIMIT 1";
			$sqli_X=$conexion_mysqli->query($cons_X)or die($conexion_mysqli->error);
			$F=$sqli_X->fetch_assoc();
				$id_funcionario=$F["id_funcionario"];
			$sqli_X->free();
				
			$descripcion="Modifica Asignacion id_asignacion: $AS_id sede: $sede id_carrera: $id_carrera cod_asignatura: $cod_asignatura jornada: $jornada n. cuotas: $numero_cuotas estado: $estado Total: $total numero Horas: $numero_horas";
			REGISTRO_EVENTO_FUNCIONARIO($id_funcionario, "notificacion", $descripcion);
			//-------------------------------------------------//
		}
		else
		{ $error="AS2"; echo"E".$conexion_mysqli->error;}
	}
	

	$conexion_mysqli->close();
	
	
}
else
{ $error="AS3";}

$url="edicion_asignacion_3.php?error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
?>