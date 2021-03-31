<?php
//////////////////////XAJAX/////////////////
@require_once ("../../../Edicion_carreras/libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("datos_beca_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD_BECA");
////////////////////////////////////////////

function ACTUALIZA_CANTIDAD_BECA($id_beca, $num_posicion)
{
	include("../../../funciones/conexion.php");
		$objResponse = new xajaxResponse();
		
		$objetivo="beca_valor_porcentaje_".$num_posicion;
		if($id_beca>0)
		{
			$cons="SELECT * FROM becas WHERE id='$id_beca' LIMIT 1";
			$sql=mysql_query($cons) or die(mysql_error());
				$DB=mysql_fetch_assoc($sql);
					$beca_tipo_aporte=$DB["beca_tipo_aporte"];
					$beca_aporte_valor=$DB["beca_aporte_valor"];
					$beca_aporte_porcentaje=$DB["beca_aporte_porcentaje"];
					
					switch($beca_tipo_aporte)
					{
						case"valor":
							$aux_valor_escribir=$beca_aporte_valor;
							break;
						case"porcentaje":
							$aux_valor_escribir=$beca_aporte_porcentaje;
							break;
					}
					
			mysql_free_result($sql);

		}
		else
		{ $aux_valor_escribir=0;}
		$objResponse->Assign($objetivo,"value",$aux_valor_escribir);
	mysql_close($conexion);	
	return $objResponse;
}
$xajax->processRequest();
?>