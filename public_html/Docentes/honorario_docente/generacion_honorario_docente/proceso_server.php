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
$xajax = new xajax("proceso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA_HONORARIO");


function COMPRUEBA_HONORARIO($sede, $mes, $year, $year_generacion)
{
	
	
		require("../../../../funciones/conexion_v2.php");
		$objResponse = new xajaxResponse();
		$div='div_x';
		$cons_M="SELECT * FROM honorario_docente WHERE sede='$sede' AND mes_generacion='$mes' AND year_generacion='$year_generacion'";
		$sqli=$conexion_mysqli->query($cons_M)or die($conexion_mysqli->error);
		$num_coincidencias=$sqli->num_rows;
		
		$html_boton='<a href="#" class="button_G" onclick="CONFIRMAR();">Revisar Honorarios</a>';
		$html_msj="Ya Fue Generado Anteriormente...<br>Para la sede: $sede el periodo [$mes-$year_generacion]";
		if($num_coincidencias>0)
		{ $mostrar_boton=false;}
		else
		{ $mostrar_boton=true;}
		
		if($mostrar_boton)
		{
			$objResponse->Assign($div,"innerHTML",$html_boton);
		}
		else
		{
			$objResponse->Assign($div,"innerHTML",$html_msj);
		}
		
		$conexion_mysqli->close();
		return $objResponse;
	
}
$xajax->processRequest();
?>