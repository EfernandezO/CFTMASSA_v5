<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	if(DEBUG){ var_dump($_GET);}
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_GET["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$id_planificacion=mysqli_real_escape_string($conexion_mysqli, $_GET["id_planificacion"]);
	
	if((is_numeric($id_planificacion))and($id_planificacion>0))
	{
		$cons_D="DELETE FROM planificaciones WHERE id_planificacion='$id_planificacion' LIMIT 1";
		if(DEBUG){ echo"---> $cons_D<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_D); 
			$error="PE0";
			require("../../../../funciones/VX.php");
			$evento="Elimina Registro Planificacion AUTO id_carrera: $id_carrera cod_asignatura: $cod_asignatura Sede:$sede";
			REGISTRA_EVENTO($evento);
		}
		//------------------------------------------------------------------------------//
		//////////////////////////////////////////
			//reordena nuemro de semana
			$cons_R="SELECT * FROM planificaciones WHERE id_funcionario='$id_usuario' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year' AND sede='$sede' ORDER by numero_semana, id_planificacion";
			$sqli_R=$conexion_mysqli->query($cons_R)or die($conexion_mysqli->error);
			$num_registros=$sqli_R->num_rows;
			$aux=1;
			if($num_registros>0)
			{
				while($P=$sqli_R->fetch_assoc())
				{
					$P_id=$P["id_planificacion"];
					$P_numero_semana=$P["numero_semana"];
					$cons_corrige="UPDATE planificaciones SET numero_semana='$aux' WHERE id_planificacion='$P_id' LIMIT 1";
					
					if(DEBUG){ echo"<br>id planificacion: $P_id<br>numero semana actual: $P_numero_semana<br>numero semana nuevo: $aux<br>";}
					if($P_numero_semana==$aux)
					{
						if(DEBUG){ echo"Numero de semana sin variacion no actualizar<br>";}
					}
					else
					{
						if(DEBUG){ echo"Numero de semana con variacion, actualizar<br>";}
						
						if(DEBUG){ echo"-->$cons_corrige<br>";}
						else{ $conexion_mysqli->query($cons_corrige);}
					}
					$aux++;
					
				}
			}
			else
			{
				if(DEBUG){ echo"Sin Registros, in necesaria la reordenacion automatica<br>";}
			}
			$sqli_R->free();
	}
	
	$conexion_mysqli->close();
	
	$url="../ver_planificaciones.php?id_carrera=$id_carrera&semestre=$semestre&year=$year&sede=$sede&cod_asignatura=$cod_asignatura&jornada=$jornada&grupo_curso=$grupo_curso&error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}