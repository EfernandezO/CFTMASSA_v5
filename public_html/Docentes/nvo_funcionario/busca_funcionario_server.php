<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Crea_funcionario_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_funcionario_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_RUT_FUNCIONARIO");
//-----------------------------------------------//
function BUSCAR_RUT_FUNCIONARIO($rut)
{
	$rut=strtolower($rut);
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_varias.php");
	$div='div_rut';
	$msj_rut="";
	$mostrar_boton=false;
	$objResponse = new xajaxResponse();
	
	if(RUT_OK($rut))
	{
		$rut_disponible=RUT_DISPONIBLE($rut, "personal");
		if($rut_disponible){$msj_rut="..."; $mostrar_boton=true;}
		else{ $msj_rut="Rut ya registrado";}
	}
	else{ $msj_rut="Rut Incorrecto...";}
	
	//------------------------------------------------------------------//
	//boton
	$objResponse->Assign('rut','value',$rut);
	$objResponse->Assign($div,"innerHTML",$msj_rut);
	if($mostrar_boton)
	{ $html_boton='<a href="#" class="button_G" onclick="confirmar();">Grabar</a>';}
	else{ $html_boton=$msj_rut;}
	$objResponse->Assign("div_boton","innerHTML",$html_boton);
	//-----------------------------------------------------------------//
	return $objResponse;
}

$xajax->processRequest();
?>