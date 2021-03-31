<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_alumno_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ALUMNO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
////////////////////////////////////////////

function BUSCA_ALUMNO($rut)
{
	$rut=strtolower($rut);
	$objResponse = new xajaxResponse();
	$div_resultado="resultado";
	if(!empty($rut))
	{
		require("../../../funciones/conexion_v2.php");
		require("../../../funciones/funciones_varias.php");
		
			$condicionRut="error";
			$msj="";
			
			if(RUT_OK($rut)){
				if(RUT_DISPONIBLE($rut,"alumno")){
					$msj="No hay Registro de este alumno en Nuestro Sistema... :)";
					$condicionRut="ok";
				}
				else{$msj="Alumno Ya registrado, no se puede continuar... :(";}
			}
			else{$msj="Rut invalido, no se puede continuar... :(";}
		
			
			$objResponse->Assign('rut','value',$rut);	
			$objResponse->Assign($div_resultado,"innerHTML",$msj.'<input id="condicionRut" name="condicionRut" type="hidden" value="'.$condicionRut.'"/>');
		$conexion_mysqli->close();
	}
	else
	{
		$objResponse->Assign($div_resultado,"innerHTML","Ingrese Rut...");
	}
	
		//$objResponse->alert("Dato incorrecto o Cantidad mayor a Total...");
	return $objResponse;
}
function VERIFICAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$hayErrores=false;
	$infoErrores="";
	
	require("../../../funciones/funciones_varias.php");
	$auxRut=$FORMULARIO["rut"];
	$auxEmail=$FORMULARIO["correo"];
	$auxRutApoderado=$FORMULARIO["rut_apoderado"];
	//rut
	if(!RUT_OK($auxRut)){ $hayErrores=true; $infoErrores.="Rut Incorrecto\n";}
	elseif(!RUT_DISPONIBLE($auxRut, "alumno")){$hayErrores=true; $infoErrores.="Rut NO Disponible\n";}
	//rut apoderado
	if(!RUT_OK($auxRutApoderado)){ $hayErrores=true; $infoErrores.="Rut Apoderado Incorrecto\n";}
	
	//email
	if(!comprobar_email($auxEmail)){$hayErrores=true; $infoErrores.="Email Incorrecto\n";}
	
	if($hayErrores){ $objResponse->alert("ERRORES DETECTADOS \n".$infoErrores);}
	else{ $objResponse->script('CONTINUAR()');}
	
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>