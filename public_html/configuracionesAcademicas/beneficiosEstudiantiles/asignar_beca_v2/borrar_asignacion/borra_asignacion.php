<?php
//-----------------------------------------//
	require("../../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_GET)
{
	include("../../../../funciones/VX.php");
	if(DEBUG){var_dump($_GET);}
	$sede=$_GET["sede"];
	$id_carrera=$_GET["id_carrera"];
	$carrera=$_GET["carrera"];
	$year_ingreso=$_GET["year_ingreso"];
	$jornada=$_GET["jornada"];
	$grupo=$_GET["grupo"];
	$niveles=$_GET["niveles"];
	$id_asignacion=base64_decode($_GET["id_asignacion"]);
	$semestre=$_GET["semestre"];
	$year=$_GET["year"];
	$situacion=$_GET["situacion"];
	$id_beca=base64_decode($_GET["id_beca"]);
	$id_alumno=base64_decode($_GET["id_alumno"]);
	
	include("../../../../funciones/conexion.php");
		$cons_DEL="DELETE FROM beca_asignaciones WHERE id='$id_asignacion' LIMIT 1";
		if(DEBUG){ echo "--->$cons_DEL<br>";}
		else{ mysql_query($cons_DEL)or die("BORRAR ".mysql_error());}
	mysql_close($conexion);

	$url="../seleccion_alumnos_asignacion.php?sede=$sede&id_carrera=$id_carrera&carrera=$carrera&ingreso=$year_ingreso&jornada=$jornada&grupo=$grupo&niveles=$niveles&error=2&semestre=$semestre&year=$year&situacion=$situacion";
	
	$evento="Elimina Asignacion BECA (id_Beca $id_beca id_alumno $id_alumno id_carrera $id_carrera)";
	REGISTRA_EVENTO($evento);
	
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}

}
else
{ header("location: ../index.php");}
?>