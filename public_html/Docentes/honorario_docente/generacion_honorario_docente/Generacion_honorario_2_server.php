<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("Generacion_honorario_2_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ASIGNACION_CAMBIO_ESTADO");


function ASIGNACION_CAMBIO_ESTADO($id_funcionario, $indice_asignacion, $condicion_actual)
{
	
	
		require("../../../../funciones/conexion_v2.php");
		$objResponse = new xajaxResponse();
		
		
		if($condicion_actual=="on"){ $nueva_condicion="off";}
		else{ $nueva_condicion="on";}
		
		$html_condicion='<a href="#" onclick="xajax_ASIGNACION_CAMBIO_ESTADO('.$id_funcionario.', '.$indice_asignacion.', \''.$nueva_condicion.'\'); return false;">'.$nueva_condicion.'</a>';
		
		$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice_asignacion]["condicion"]=$nueva_condicion;
		
		
		$total_a_pagar=0;
		foreach($_SESSION["HONORARIO"][$id_funcionario]["asignaciones"] as $x => $aux_array)
		{
			$aux_condicion=$aux_array["condicion"];
			$aux_total_base=$aux_array["total_base"];
			$aux_cargo=$aux_array["cargo"];
			$aux_abono=$aux_array["abono"];
			$aux_horas_mensuales=$aux_array["horas_mensuales"];
			$aux_valor_hora=$aux_array["valor_hora"];
			
			$total_base=($aux_horas_mensuales*$aux_valor_hora);
			$total_cargo=($aux_cargo*$aux_valor_hora);
			$total_abono=($aux_abono*$aux_valor_hora);
			
			$aux_total_asignatura=($total_base-$total_cargo)+$total_abono;
			
			if($aux_condicion=="on")
			{ $total_a_pagar+=$aux_total_asignatura;}
		}
		
		$_SESSION["HONORARIO"][$id_funcionario]["total_a_pagar"]=$total_a_pagar;
		//-------------------------------------------------------------------------------///
		
		$div_total_a_pagar='total_pagar_'.$id_funcionario;
		$div_AS_condicion='AS_condicion_'.$id_funcionario.'_'.$indice_asignacion;
		
		$objResponse->Assign($div_total_a_pagar,"innerHTML","<strong>$ ".number_format($total_a_pagar,0,",",".")."</strong>");
		$objResponse->Assign($div_AS_condicion,"innerHTML",$html_condicion);
		//------------------------------------------------------------------------------//
		
		
		$SUMA_TOTAL=0;
		foreach($_SESSION["HONORARIO"] as $X_id_funcionario => $aux_datos)
		{
			$X_total_a_pagar=$aux_datos["total_a_pagar"];
			$SUMA_TOTAL+=$X_total_a_pagar;
		}
		
		$objResponse->Assign("SUMA_TOTAL","innerHTML","$ ".number_format($SUMA_TOTAL,0,",","."));
		
		$conexion_mysqli->close();
		return $objResponse;
		
		
		
}
$xajax->processRequest();
?>