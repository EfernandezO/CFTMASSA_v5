<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->Borrar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	
	include("../../../../../funciones/conexion_v2.php");
	include("../../../../../funciones/VX.php");
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	$id_evaluacion=base64_decode($_GET["id_evaluacion"]);
	if(isset($_GET["id_alumno"]))
	{
		$id_alumno_destacado=base64_decode($_GET["id_alumno"]);
		$dato_get="&id_alumno=".base64_encode($id_alumno_destacado);
	}
	else{ $dato_get="";}
	
	$cons_1="DELETE FROM notas_parciales_evaluaciones WHERE id='$id_evaluacion' LIMIT 1";
	$cons_2="DELETE FROM notas_parciales_registros WHERE id_evaluacion='$id_evaluacion'";
	if(DEBUG){ echo"$cons_1<br>$cons_2<br>";}
	else
	{
		$conexion_mysqli->query($cons_1);
		$conexion_mysqli->query($cons_2);
		mysql_query($cons_2)or die("2-->".mysql_error());
	}
	
	$evento="Elimina Evaluacion ID-> $id_evaluacion carrera: $id_carrera Sede: $sede cod_asignatura: $cod_asignatura";
	REGISTRA_EVENTO($evento);
	$conexion_mysqli->close();
	
	$url='../lista_evaluaciones.php?sede='.base64_encode($sede).'&id_carrera='.base64_encode($id_carrera).'&jornada='.base64_encode($jornada).'&grupo='.base64_encode($grupo).'&cod_asignatura='.base64_encode($cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).$dato_get."&error=0";
	if(DEBUG){ echo"<br>URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../../index.php");}
?>