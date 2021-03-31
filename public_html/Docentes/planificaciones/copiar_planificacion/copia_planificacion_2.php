<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->importar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if(DEBUG){ var_dump($_GET);}
if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");

	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	//datos actuales
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$cod_asignatura=base64_decode($_GET["asignatura"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo"]);
	
	
	//datos planificacion a importar
	$id_funcionario_old=base64_decode($_GET["id_funcionario_old"]);	
	
	$semestre_old=base64_decode($_GET["semestre_old"]);
	$year_old=base64_decode($_GET["year_old"]);
	$sede_old=base64_decode($_GET["sede_old"]);
	$id_carrera_old=base64_decode($_GET["id_carrera_old"]);
	$cod_asignatura_old=base64_decode($_GET["asignatura_old"]);
	$jornada_old=base64_decode($_GET["jornada_old"]);
	$grupo_curso_old=base64_decode($_GET["grupo_old"]);
	
		//busco planificacion en otra jornada
	$cons="SELECT * FROM planificaciones WHERE semestre='$semestre_old' AND year='$year_old' AND sede='$sede_old' AND id_carrera='$id_carrera_old' AND cod_asignatura='$cod_asignatura_old' AND jornada='$jornada_old' AND id_funcionario='$id_funcionario_old' AND grupo='$grupo_curso_old'";
	
	$sqli=$conexion_mysqli->query($cons);
	$num_registro=$sqli->num_rows;
	
	if(DEBUG){ echo"--> $cons<br>N. $num_registro<br>";}
	$fecha_actual=date("Y-m-d");
	
	if($num_registro>0)
	{
		$aux=0;
		while($P=$sqli->fetch_assoc())
		{
			$aux++;
			$P_semestre=$P["semestre"];
			$P_year=$P["year"];
			$P_sede=$P["sede"];
			$P_id_funcionario=$P["id_funcionario"];
			
			
			$P_id_carrera=$P["id_carrera"];
			$P_cod_asignatura=$P["cod_asignatura"];
			$P_id_planificacion=$P["id_planificacion"];
			$P_id_programa=$P["id_programa"];
			$P_numero_semana=$P["numero_semana"];
			$P_horas_semana=$P["horas_semana"];
			$P_actividad=$P["actividad"];
			$P_implemento=$P["implemento"];
			$P_evaluacion=$P["evaluacion"];
			$P_bibliografia=$P["bibliografia"];
			$P_contenido_tematico_opcional=$P["contenido_tematico_opcional"];
			
			$campos="id_funcionario, id_programa, sede, id_carrera, cod_asignatura, jornada, grupo, semestre, year, numero_semana, horas_semana, actividad, implemento, evaluacion, bibliografia, fecha_generacion";
			if($P_id_programa==0){ $campos.=", contenido_tematico_opcional";}
			
			$valores="'$id_usuario_actual','$P_id_programa', '$sede', '$P_id_carrera', '$P_cod_asignatura', '$jornada', '$grupo_curso', '$semestre', '$year', '$P_numero_semana', '$P_horas_semana', '$P_actividad', '$P_implemento', '$P_evaluacion', '$P_bibliografia', '$fecha_actual'";
			if($P_id_programa==0){ $valores.=", '$P_contenido_tematico_opcional'";}
			
			
			$CONS_IN="INSERT INTO planificaciones ($campos) VALUES ($valores)";
			
			
			if(DEBUG){ echo"$aux-->$CONS_IN<br>"; $error="debug";}
			else
			{ 
				$error="CP1";
				$conexion_mysqli->query($CONS_IN)or die($conexion_mysqli->error);
			}
		}
		//---------------------//
		$evento="Copia Registros de Planificacion desde [$semestre_old semestre - $year_old cod_asignatura: $cod_asignatura_old id_carrera: $id_carrera_old id_funcionario: $id_funcionario_old] hacia [$semestre semestre - $year cod_asignatura: $cod_asignatura id_carrera: $id_carrera id_funcionario: $id_usuario_actual] ";
		if(DEBUG){ echo"<br>EVENTO: $evento<br>";}
		REGISTRA_EVENTO($evento);
		//------------------------//
		$URL="copia_planificacion_3.php?id_carrera=$id_carrera&cod_asignatura=$cod_asignatura&semestre=$semestre&year=$year&jornada=$jornada&grupo_curso=$grupo_curso&sede=$sede&error=$error";
		if(DEBUG){ echo"URL: $URL<br>";}
		else{ header("location: $URL");}
	}
}
else
{ header("location: ../index.php");}
?>
