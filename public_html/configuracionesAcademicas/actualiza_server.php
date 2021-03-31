<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MAIN_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualiza_server.php");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_VALORES");
$xajax->register(XAJAX_FUNCTION,"PERMITE_EDITAR");
define("DEBUG", false);
////////////////////////////////////////////
function ACTUALIZA_VALORES($campo, $id_carrera_hija, $valor_actual, $div_origen, $permite_editar)
{
	if($permite_editar=="si")
	{
		include("../../funciones/conexion.php");
		$div="msj";
		$objResponse = new xajaxResponse();
		$html="";
		if(DEBUG){$html="O: $div_origen =>$campo -> $id_carrera_hija: $valor_actual<br>";}
		
		
		switch($campo)
		{
			case"permite_matriculas":
				if($valor_actual=="si"){$nuevo_valor="no";}
				else{$nuevo_valor="si";}
				///actualizo registro
				$cons_UP="UPDATE hija_carrera_valores SET permite_matriculas='$nuevo_valor' WHERE id='$id_carrera_hija' LIMIT 1";
				if(DEBUG){$html.=$cons_UP;}
				else{ mysql_query($cons_UP)or die(mysql_error());}
				$html_link='<a href="#" onclick="xajax_ACTUALIZA_VALORES(\'permite_matriculas\',\''.$id_carrera_hija.'\', \''.$nuevo_valor.'\', \''.$div_origen.'\', \''.$permite_editar.'\');return false;">'.$nuevo_valor.'</a>';
				$objResponse->Assign($div_origen,"innerHTML",$html_link);
				break;
			case"matricula":
				if(is_numeric($valor_actual))
				{
					$nuevo_valor=$valor_actual;
				$cons_UP="UPDATE hija_carrera_valores SET matricula='$nuevo_valor' WHERE id='$id_carrera_hija' LIMIT 1";
				if(DEBUG){$html.=$cons_UP;}
				else{ mysql_query($cons_UP)or die(mysql_error());}
				$html_div='<div id="'.$div_origen.'" onclick="xajax_PERMITE_EDITAR(\'matricula\',\''.$id_carrera_hija.'\', \''.$nuevo_valor.'\', \''.$div_origen.'\', \''.$permite_editar.'\');return false;">'.$nuevo_valor.'</div>';
				$objResponse->Assign($div_origen,"innerHTML",$html_div);
				}
				else{ $objResponse->Alert("Ingrese Un Valor Correcto para la $campo...");}
				break;	
			case"arancel_1":
				if(is_numeric($valor_actual))
				{
					$nuevo_valor=$valor_actual;
				$cons_UP="UPDATE hija_carrera_valores SET arancel_1='$nuevo_valor' WHERE id='$id_carrera_hija' LIMIT 1";
				if(DEBUG){$html.=$cons_UP;}
				else{ mysql_query($cons_UP)or die(mysql_error());}
				$html_div='<div id="'.$div_origen.'" onclick="xajax_PERMITE_EDITAR(\'arancel_1\',\''.$id_carrera_hija.'\', \''.$nuevo_valor.'\', \''.$div_origen.'\', \''.$permite_editar.'\');return false;">'.$nuevo_valor.'</div>';
				$objResponse->Assign($div_origen,"innerHTML",$html_div);
				}
				else{ $objResponse->Alert("Ingrese Un Valor Correcto para la $campo...");}
				break;
			case"arancel_2":
				if(is_numeric($valor_actual))
				{
					$nuevo_valor=$valor_actual;
				$cons_UP="UPDATE hija_carrera_valores SET arancel_2='$nuevo_valor' WHERE id='$id_carrera_hija' LIMIT 1";
				if(DEBUG){$html.=$cons_UP;}
				else{ mysql_query($cons_UP)or die(mysql_error());}
				$html_div='<div id="'.$div_origen.'" onclick="xajax_PERMITE_EDITAR(\'arancel_2\',\''.$id_carrera_hija.'\', \''.$nuevo_valor.'\', \''.$div_origen.'\', \''.$permite_editar.'\');return false;">'.$nuevo_valor.'</div>';
				$objResponse->Assign($div_origen,"innerHTML",$html_div);
				}
				else{ $objResponse->Alert("Ingrese Un Valor Correcto para la $campo...");}
				break;
		}
		$objResponse->Assign($div,"innerHTML",$html);
		mysql_close($conexion);
		return $objResponse;
	}
}
function PERMITE_EDITAR($campo, $id_carrera_hija, $valor_actual, $div_origen, $permite_editar)
{
	if($permite_editar=="si")
	{
		$objResponse = new xajaxResponse();
		$html="";
		$html='<input name="xxx" id="xxx" type="text" onblur="xajax_ACTUALIZA_VALORES(\''.$campo.'\',\''.$id_carrera_hija.'\', this.value, \''.$div_origen.'\', \''.$permite_editar.'\');return false;" value="'.$valor_actual.'" size="10">';
		$objResponse->Assign($div_origen,"innerHTML",$html);
		return $objResponse;
	}
}
////////////////
$xajax->processRequest();
?>