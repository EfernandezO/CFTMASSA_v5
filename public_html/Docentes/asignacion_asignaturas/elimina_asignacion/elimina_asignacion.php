<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1_ELIMINAR");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, $_GET["id_funcionario"]);
	$id_asignacion=mysqli_real_escape_string($conexion_mysqli, $_GET["id_asignacion"]);
//----------------------------------------------------------------------------------------------------------------------------//	
	$cons_X="SELECT id_carrera, jornada, grupo, cod_asignatura, sede, semestre, year FROM toma_ramo_docente WHERE id='$id_asignacion' LIMIT 1";
	$sqli_X=$conexion_mysqli->query($cons_X)or die($conexion_mysqli->error);
	$AS=$sqli_X->fetch_assoc();
		$AS_id_carrera=$AS["id_carrera"];
		$AS_jornada=$AS["jornada"];
		$AS_grupo=$AS["grupo"];
		$AS_cod_asignatura=$AS["cod_asignatura"];
		$AS_sede=$AS["sede"];
		$AS_semestre=$AS["semestre"];
		$AS_year=$AS["year"];
	$sqli_X->free();
//--------------------------------------------------------------------------------------------------------------------------//	
	$cons_D="DELETE FROM toma_ramo_docente WHERE id='$id_asignacion' LIMIT 1";
	
	if(DEBUG){ $error="debug";}
	else
	{
		if($conexion_mysqli->query($cons_D))
		{ 
			$error="A1";
			//------------------------------------------------//
			include("../../../../funciones/VX.php");
			$evento="Elimina Asignacion id_asignacion: $id_asignacion";
			REGISTRA_EVENTO($evento);
			
				
			$descripcion="Elimina Asignacion id_asignacion: $id_asignacion id_carrera: $AS_id_carrera jornada: $AS_jornada Grupo: $AS_grupo cod_asignatura: $AS_cod_asignatura sede: $AS_sede Semestre: $AS_semestre Year: $AS_year";
			REGISTRO_EVENTO_FUNCIONARIO($id_funcionario, "notificacion", $descripcion);
			//---------------------------------------//
		}
		else
		{ $error="A2";}
	}
	@mysql_close($conexion);  
	$conexion_mysqli->close();
	
	$url="../asignacion_asignaturas_docente_1.php?fid=".base64_encode($id_funcionario)."&error=$error";
	
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}
else
{ header("location: ../../lista_funcionarios.php");}
?>