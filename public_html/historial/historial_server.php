<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("revision_historial_general_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("historial_server.php");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ID_FUNCIONARIO");
////////////////////////////////////////////
function BUSCA_ID_FUNCIONARIO($fecha)
{
	
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	
	$campo_select='<select name="usuario" id="usuario">';
	$cons="SELECT DISTINCT(id_user), tipo_usuario FROM historial WHERE DATE(fecha_hora)='$fecha' ORDER BY id_user";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if(DEBUG){$objResponse->Alert("$cons\n num registros: $num_reg");}
	if($num_reg>0)
	{
		if($num_reg>1){$campo_select.='<option value="0">0_Todos</option>';}
		while($F=$sqli->fetch_row())
		{
			$F_id=$F[0];
			$F_tipo_usuario=$F[1];
			if($F_tipo_usuario=="alumno")
			{
				$cons_A="SELECT nombre, apellido_P, apellido_M FROM alumno WHERE id='$F_id' LIMIT 1";
				$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$DA=$sqli_A->fetch_assoc();
				$nombre_usuario=$DA["nombre"]." ".$DA["apellido_P"]." ".$DA["apellido_M"];
				$sqli_A->free();
			}
			else
			{$nombre_usuario=NOMBRE_PERSONAL($F_id);}
			$datos=$F_tipo_usuario."-".$F_id;
			
			$campo_select.='<option value="'.$datos.'">['.$F_tipo_usuario.'] '.$nombre_usuario.'</option>';
		}
	}
	else
	{ $campo_select.='<option value="">sin datos</option>';}
	
	$campo_select.='</select>';
	$sqli->free();
	$conexion_mysqli->close();			 
	
	$objResponse->Assign("div_funcionario","innerHTML",$campo_select);
	return $objResponse;
}
//-----------------------------------------------------------------------------//
$xajax->processRequest();
?>