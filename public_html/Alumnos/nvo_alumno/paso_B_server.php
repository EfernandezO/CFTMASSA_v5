<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_carreras_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_LICEOS");
$xajax->register(XAJAX_FUNCTION,"CARGA_COMUNA_LICEO");
////////////////////////////////////////////

function CARGA_COMUNA_LICEO($region)
{
	$objResponse = new xajaxResponse();
	$div_resultado="div_ciudad";
	$html='<select name="liceo_ciudad" id="liceo_ciudad" onchange="xajax_CARGA_LICEOS('.$region.',this.value)">';
	$html.='<option value="0">Seleccione...</option>';
		$array_carrera=array();
		require("../../../funciones/conexion_v2.php");
		   $res="SELECT DISTINCT(comuna) FROM liceos WHERE region='$region' ORDER by comuna";
		   $sqli=$conexion_mysqli->query($res);
		   while($C=$sqli->fetch_assoc()) 
		   {
				$auxComuna=$C["comuna"];
				$html.='<option value="'.$auxComuna.'">'.$auxComuna.'</option>';
			
		   }
		   $sqli->free();
		    $conexion_mysqli->close();
	$html.='</select>';
		
     
	$objResponse->Assign($div_resultado,"innerHTML",$html);
	return $objResponse;
}

function CARGA_LICEOS($region, $comuna)
{
	$objResponse = new xajaxResponse();
	$div_resultado="div_liceo";
	$html='<select name="liceo" id="liceo">';
	$html.='<option value="0">Seleccione...</option>';
		$array_carrera=array();
		require("../../../funciones/conexion_v2.php");
		   $res="SELECT idLiceo, nombreEstablecimiento FROM liceos WHERE region='$region' AND comuna='$comuna' ORDER by nombreEstablecimiento";
		   $sqli=$conexion_mysqli->query($res)or die($conexion_mysqli->error);
		   while($L=$sqli->fetch_assoc()) 
		   {
			    $auxIdLiceo=$L["idLiceo"];
				$auxNombreEstablecimiento=$L["nombreEstablecimiento"];
				$html.='<option value="'.$auxIdLiceo.'">'.$auxNombreEstablecimiento.'</option>';
			
		   }
	$html.='</select>';
		
     $sqli->free();
	 $conexion_mysqli->close();
	$objResponse->Assign($div_resultado,"innerHTML",$html);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>