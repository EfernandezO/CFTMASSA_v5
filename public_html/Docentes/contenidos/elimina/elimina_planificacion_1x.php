<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	
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
		 echo'<script languaje="javascript">
			c=confirm(\'Desea corregir automaticamente el numero de Semana..?\n\n si acepta, realiza una nueva numeracion al numero de semana de los registro que ya existen eliminando los espacios entre numero de semana\n en caso contrario solo elimina la planificacion actual, sin afectar a las demás\');
			if(c)
			{window.location="elimina_planificacion_auto.php?id_carrera='.$id_carrera.'&asignatura='.$cod_asignatura.'&jornada='.$jornada.'&grupo='.$grupo_curso.'&sede='.$sede.'&semestre='.$semestre.'&year='.$year.'&id_planificacion='.$id_planificacion.'"}
			else
			{ window.location="elimina_planificacion_normal.php?id_carrera='.$id_carrera.'&asignatura='.$cod_asignatura.'&jornada='.$jornada.'&grupo='.$grupo_curso.'&sede='.$sede.'&semestre='.$semestre.'&year='.$year.'&id_planificacion='.$id_planificacion.'"}
			</script>';
	}
}