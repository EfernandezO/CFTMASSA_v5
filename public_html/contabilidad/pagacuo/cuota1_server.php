<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("cuota1_server.php");

$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"cambia_estado_edicion");
$xajax->register(XAJAX_FUNCTION,"identifica_cuota");
////////////////////////////////////////////
function cambia_estado_edicion($valor, $datos)
{
	
	$aux_datos=base64_decode($datos);
	$datos_F=unserialize($aux_datos);
	//var_dump($datos_F);
	$objResponse = new xajaxResponse();
	$cantidad_letras=count($datos_F["numero_letra"]);
	if($valor=="Activar Edicion")
	{
		$html_boton='<input name="boton_edicion" type="button" value="Desactivar Edicion" onclick="xajax_cambia_estado_edicion(this.value, document.getElementById(\'datos_ocultos\').value);return false;"/>';
		$html_boton_agregar='<a href="../letras/nueva_letra/index.php"><img src="../../imagenes/agregar.png" alt="[+]"  border="0" title="Agregar Letra"></a>';
		
		/*$aux=1;
		foreach($datos_F["numero_letra"] as $n => $valor)
		{
			$aux_div="div_".$aux;
			$html_anular='<a href="anular.php?numletra='.$valor.'">[-]</a>';
			$objResponse->Assign($aux_div,"innerHTML",$html_anular);
			$aux++;
		}
		*/
	}
	else
	{
		$html_boton='<input type="button" name="boton_edicion" value="Activar Edicion"  onclick="xajax_cambia_estado_edicion(this.value, document.getElementById(\'datos_ocultos\').value);return false;"/>';
		$html_boton_agregar='';
	}
	
	
	$objResponse->Assign("div_edicion","innerHTML",$html_boton);
	$objResponse->Assign("espacio_boton","innerHTML",$html_boton_agregar);
	return $objResponse;
}
$xajax->processRequest();
?>