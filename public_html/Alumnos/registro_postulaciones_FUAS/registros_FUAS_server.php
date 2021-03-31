<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("REGISTRO_FUAS");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registros_FUAS_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"REGISTRA_FUAS");


function REGISTRA_FUAS($id_alumno, $year, $estado_fuas)
{	
	require("../../../funciones/conexion_v2.php");
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$objResponse = new xajaxResponse();
	$div='div_resultado';
	$html='';
	//-----------------------------------------------//
	 include("../../../funciones/VX.php");
	 $evento="registro FUAS id_alumno: $id_alumno year: $year estado: $estado_fuas";
   	 REGISTRA_EVENTO($evento);
	//---------------------------------------------------//
	
	//borro si existe registro previo
	$cons_D="DELETE FROM registros_FUAS WHERE id_alumno='$id_alumno' AND year='$year' LIMIT 1";
	$conexion_mysqli->query($cons_D);
	///creo registro nuevo
	
	$cons_IN="INSERT INTO registros_FUAS (id_alumno, year, estado_fuas, tipo_usuario,  cod_user, fecha_generacion) VALUES ('$id_alumno', '$year', '$estado_fuas', 'admin', '$id_usuario_actual', '$fecha_hora_actual')";
	if($conexion_mysqli->query($cons_IN)){ $html.="Registro Guardado para el $year";}
	else{$html.="ERROR al Registrar el $year";}
	
	
	$objResponse->Assign('div_tipo_usuario_'.$year,"innerHTML","admin");
	$objResponse->Assign('div_usuario_'.$year,"innerHTML",$id_usuario_actual);
	$objResponse->Assign('div_fecha_'.$year,"innerHTML",$fecha_hora_actual);
	
	$objResponse->Assign($div,"innerHTML",$html);
	@mysql_close($conexion);	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>