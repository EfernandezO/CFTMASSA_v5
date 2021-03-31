<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_comprobar_egresados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("cambiar_situacion_a_egresado.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CAMBIO_SITUACION_ACADEMICA");
////////////////////////////////////////////
function CAMBIO_SITUACION_ACADEMICA($id_alumno, $id_carrera, $indice)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/VX.php");
	$div="boton_".$indice;
	//$objResponse->Alert("id_alumno: $id_alumno id_carrera: $id_carrera");
	$nueva_situacion="EG";
	list($alumno_es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO($id_alumno, $id_carrera);
	if($alumno_es_egresado)
	{
		$cons_UP="UPDATE alumno SET situacion='$nueva_situacion', year_egreso='$year_egreso' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
		if($conexion_mysqli->query($cons_UP))
		{
			$evento="Cambia de situacion academica a Alumno [V -> $nueva_situacion] (compueba egresado) id_alumno: $id_alumno id_carrera: $id_carrera";
			$descripcion="Cambio de situacion academica a Egresado periodo [$semestre_egreso - $year_egreso]";
			REGISTRA_EVENTO($evento);
			REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion",$descripcion);
			$objResponse->Assign($div,"innerHTML",$nueva_situacion);
		}
		else
		{$objResponse->Assign($div,"innerHTML","Error No se puede Cambiar");}
	}
	else
	{
	}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();		
?>