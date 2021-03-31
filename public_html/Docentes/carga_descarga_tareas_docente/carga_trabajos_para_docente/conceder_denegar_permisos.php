<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("conceder_denegar_permisos.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");


function PERMISOS($permiso, $id_usuario, $id_trabajo)
{
	
	$html="";
	$objResponse = new xajaxResponse();
	$div='div_informacion';
	
	if(DEBUG){$html.="Permiso:$permiso<br>id_usuario: $id_usuario<br>id_trabajo:$id_trabajo<br>";}
		
	if(is_numeric($id_usuario)){$continuar_1=true;}
	else{$continuar_1=false;}
	
	if(is_numeric($id_trabajo)){$continuar_2=true;}
	else{ $continuar_2=false;}
	
	if($continuar_1 and $continuar_2)
	{
		$div_solicitante="div_".$id_usuario;
		switch($permiso)
		{
			case 1:
				//DAR PERMISO
				$cons_X="INSERT INTO tareas_docente_permisos (id_trabajo, id_usuario) VALUES ('$id_trabajo', '$id_usuario')";
				if(DEBUG){$html.="Dar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS(0, '.$id_usuario.', '.$id_trabajo.'); return false;">OK</a>';
				break;
			case 0:
				//quitar permiso
				$cons_X="DELETE FROM tareas_docente_permisos WHERE id_usuario='$id_usuario' AND id_trabajo='$id_trabajo' LIMIT 1";
				if(DEBUG){$html.="quitar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS(1, '.$id_usuario.', '.$id_trabajo.'); return false;">X</a>';
				break;	
		}
		require("../../../../funciones/conexion_v2.php");
			if(DEBUG){ $html.="--->$cons_X<br>";}
			else{ $conexion_mysqli->query($cons_X) or die($conexion_mysqli->error);}
		$conexion_mysqli->close();
	}
	else
	{$objResponse->Alert("Datos Incorrectos NO se puede continuar");}
		
	$objResponse->Assign($div,"innerHTML",$html);
	$objResponse->Assign($div_solicitante,"innerHTML",$check);
	return $objResponse;
}

$xajax->processRequest();
?>