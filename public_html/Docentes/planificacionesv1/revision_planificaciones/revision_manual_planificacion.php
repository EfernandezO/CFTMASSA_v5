<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_planificaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
if($_GET)
{
		require("../../../../funciones/conexion_v2.php");
		
	$fecha_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	$estado=$_GET["estado"];
	
	$cons_R="SELECT COUNT(id_revision) FROM planificaciones_revision_manual WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo'";
			$sqli_R=$conexion_mysqli->query($cons_R);
			$RP=$sqli_R->fetch_row();
				$num_registros=$RP[0];
				if(empty($num_registros)){ $num_registros=0;}
				if(DEBUG){ echo"Registros Previos: $num_registros<br>";}
			$sqli_R->free();
		//---------------------------------------------------------------------------//	
			if(($estado==1)or($estado==0))
			{ $continuar=true;}
			else
			{ $continuar=false;}
		//-------------------------------------------------------------------------///
		
		if($continuar)	
		{
			if($num_registros>0)
			{
				//actualizar
				$cons_X="UPDATE planificaciones_revision_manual SET estado='$estado', fecha_generacion='$fecha_actual', cod_user='$id_usuario_actual' WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo'";
			}
			else
			{
				//insertar
				$cons_X="INSERT INTO planificaciones_revision_manual (sede, semestre, year, id_carrera, cod_asignatura, jornada, grupo, estado, fecha_generacion, cod_user) VALUES ('$sede', '$semestre', '$year', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$estado', '$fecha_actual', '$id_usuario_actual')";
			}
			
			if(DEBUG){ echo"----> $cons_X<br>";}
			else{ $conexion_mysqli->query($cons_X);}
			
			$url="revision_planificaciones_1.php?sede=$sede&semestre=$semestre&year=$year";
			if(DEBUG){echo"URL: $url<br>";}
			else{ header("location: $url");}
		}
		else
		{
			$url="revision_planificaciones_1.php";
			if(DEBUG){echo"URL: $url<br>";}
			else{ header("location: $url");}
		}
		
		$conexion_mysqli->close();
}
else
{
		$url="revision_planificaciones_1.php";
		if(DEBUG){echo"URL: $url<br>";}
		else{ header("location: $url");}
}