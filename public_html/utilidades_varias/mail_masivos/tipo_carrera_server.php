<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_Email_Masivo_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("tipo_carrera_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"DATOS_CARRERA");
////////////////////////////////////////////

function DATOS_CARRERA($tipo_programa)
{
	 include("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	$res="SELECT id, carrera FROM carrera WHERE tipo_programa='$tipo_programa'";
   $result=mysql_query($res)or die("tipo carrera: ".mysql_error());
   $select='<select name="carrera" id="carrera">';
   while($row = mysql_fetch_assoc($result)) 
   {
		$id_carrera=$row["id"];
		$carrera=$row["carrera"];
			$select.='<option value="'.$id_carrera.'_'.$carrera.'">'.$carrera.'</option>';
    }
	$select.='<option value="0_todas">Todas</option></select>';
    mysql_free_result($result); 
    mysql_close($conexion); 
	
	$objResponse->Assign("div_carrera","innerHTML",$select);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>