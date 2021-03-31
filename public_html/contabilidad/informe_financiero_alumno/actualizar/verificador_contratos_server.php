<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////--/XAJAX/----////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');

$xajax->register(XAJAX_FUNCTION,"CONFIRMAR");
///////////////----------------////////////////////////////
function CONFIRMAR($FORMULARIO)
{
	$continuar_1=true;
	
	
	$objResponse = new xajaxResponse();
	
	$saldo_a_favor=$FORMULARIO["saldo_a_favor"];
	$BNM=$FORMULARIO["beca_nuevo_milenio"];
	$aporte_BNM=$FORMULARIO["aporte_beca_nuevo_milenio"];
	$BET=$FORMULARIO["beca_excelencia"];
	$aporte_BET=$FORMULARIO["aporte_beca_excelencia"];
	$arancel=$FORMULARIO["arancel"];
	$cantidad_beca=$FORMULARIO["cantidad_beca"];
	$porcentaje_desc_beca=$FORMULARIO["porcentaje_desc_beca"];
	$porcentaje_desc_contado=$FORMULARIO["porcentaje_desc_contado"];
	
	$total=$FORMULARIO["total"];
	$contado=$FORMULARIO["contado"];
	$cheque=$FORMULARIO["cheque"];
	$linea_credito=$FORMULARIO["linea_credito"];
	$excedentes=$FORMULARIO["excedentes"];
	
	$matricula=$FORMULARIO["matricula_contrato"];
	$forma_pago_matricula=$FORMULARIO["forma_pago_matricula"];
	
	//var_dump($FORMULARIO);
	
	if($forma_pago_matricula=="EXCEDENTE"){$saldo_a_favor=($saldo_a_favor-$matricula);}
	
	
	//comprobar linea credito
	$aportes_alumno=($saldo_a_favor+$aporte_BET+$aporte_BNM);
	
	$totalizado_desc_beca=($porcentaje_desc_beca*$arancel)/100;
	$totalizado_desc_contado=($porcentaje_desc_contado*$arancel)/100;
	
	$descuentos_alumno=($cantidad_beca+$totalizado_desc_beca+$totalizado_desc_contado);
	
	$aux_total=(($arancel - $aportes_alumno)-$descuentos_alumno);
	if($aux_total<0){$aux_excedente=($total*-1); $total=0;}else{$aux_excedente=0;}
	
	$aux_linea_credito=($total-$contado-$cheque);
	//------------------------------------------------------------------------------------//
	$objResponse->Alert("INFORMACION\n\n total aportes al alumno: $aportes_alumno\n descuentos alumno: $descuentos_alumno \n TOTAL : $aux_total");
	
	
	if($aux_total!=$total){ $continuar_1=false; $objResponse->Alert("Total descuadrado...[$total <> $aux_total] Verificar.");}
	if($aux_linea_credito!=$linea_credito){ $continuar_1=false; $objResponse->Alert("Linea Credito Descuadrada...[$linea_credito <> $aux_linea_credito] Verificar.");}
	if($aux_excedente!=$excedentes){$continuar_1=false; $objResponse->Alert("Excedente descuadrado...[$excedentes <> $aux_excedente] Verificar.");}
	
	
	$continuar_1=true;
	if(($continuar_1))
	{
		$objResponse->script("document.getElementById('frm').submit();");
	}
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>