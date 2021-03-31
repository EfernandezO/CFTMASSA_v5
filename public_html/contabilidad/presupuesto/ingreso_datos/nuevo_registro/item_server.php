<?php
session_start();
/////////////////////--/XAJAX/----////////////////
require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("item_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_ITEM");
///////////////----------------////////////////////////////

function CARGA_ITEM($movimiento)
{
	if($movimiento!="SS")
	{
		$sede=$_SESSION["PRESUPUESTO"]["sede"];
		$div="div_item";
		$select='<select name="item" id="item">';
		
		include("../../../../../funciones/conexion.php");
			$objResponse = new xajaxResponse();
			$cons="SELECT codigo, nombre FROM presupuesto_parametros WHERE movimiento='$movimiento' AND sede='$sede'";
			$sql=mysql_query($cons) or die("item".mysql_error());
			$num_reg=mysql_num_rows($sql);
			if($num_reg>0)
			{
				while($I=mysql_fetch_assoc($sql))
				{
					$codigo=$I["codigo"];
					$nombre=$I["nombre"];
					
					$select.='<option value="'.$codigo.'">'.$nombre.'('.$codigo.')</option>';
				}
			}
			else
			{
				$select.='<option value="SI">Sin Item</option>';
			}
			$select.='</select>';
			$objResponse->Assign($div,"innerHTML",$select);
		mysql_free_result($sql);	
		mysql_close($conexion);
		return $objResponse;
	}	
}
////////////////
$xajax->processRequest();