<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Registros_ingresos_empresa_boleta_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////--/XAJAX/----////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONFIRMAR");
///////////////----------------////////////////////////////
function CONFIRMAR($FORMULARIO)
{
	$continuar_1=false;
	$continuar_2=false;
	$continuar_3=false;
	
	$objResponse = new xajaxResponse();
	
	$valor=$FORMULARIO["fvalor"];
	$glosa=$FORMULARIO["fglosa"];
	$forma_pago=$FORMULARIO["forma_pago"];
	
	$cheque_numero=$FORMULARIO["cheque_numero"];
	
	$id_cta_cte=$FORMULARIO["id_cta_cte"];
	
	if(is_numeric($valor)){ $continuar_1=true;}
	else{ $objResponse->Alert("ingrese Valor");}
	
	if(!empty($glosa)){ $continuar_2=true;}
	else{ $objResponse->Alert("ingrese Glosa");}
	
	switch($forma_pago)
	{
		case"efectivo":
			$continuar_3=true;
			break;
		case"cheque":
			if(!empty($cheque_numero)){ $continuar_3=true;}
			else{ $objResponse->Alert("ingrese Numero de Cheque");}
			break;
		case"deposito":
			if($id_cta_cte>0){ $continuar_3=true;}
			else{ $objResponse->Alert("debe Haber una cta cte para continuar");}
			break;
		default:
				
	}
	
	
	if($continuar_1 and $continuar_2 and $continuar_3)
	{
		if(DEBUG){ $objResponse->Alert("Envia Formulario OK");}
		else{$objResponse->script("document.getElementById('frm').submit();");}
	}
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>