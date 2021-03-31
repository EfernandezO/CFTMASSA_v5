<?php
session_start();
define("DEBUG", false);
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_folio_server.php");
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_FOLIO_CAJA");
////////////////////////////////////////////

function BUSCA_FOLIO_CAJA($nombre_caja, $tipoBoleta)
{
	$div="apDiv1";
	$html="";
	$objResponse = new xajaxResponse();
		require("../../../../../funciones/conexion_v2.php");
			$cons_f="SELECT MAX(folio) FROM boleta WHERE caja='$nombre_caja' AND tipo='$tipoBoleta'";
			$sql_f=$conexion_mysqli->query($cons_f)or die($conexion_mysqli->error);
			$D_f=$sql_f->fetch_row();
			$last_folio=$D_f[0];
			if(DEBUG){ $objResponse->Alert("-->$cons_f\n Ultimo folio: $last_folio");}
			$probable_folio=$last_folio+1;
			$sql_f->free();
			$conexion_mysqli->close();
			$html.='<div align="center"><img src="../../../../BAses/Images/advertencia.png" width="29" height="26" alt="ad" />Probable Folio caja ('.$nombre_caja.')<br><a href="#" onclick="ASIGNAR(\''.$probable_folio.'\')"><strong>'.$probable_folio.'</strong></a></div>';
	
	$objResponse->Assign($div,"innerHTML",$html);		
	return $objResponse;
	///////////////////////
}

////////////////
$xajax->processRequest();
?>