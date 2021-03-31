<?php
//--------------CLASS_okalis------------------//
	require("../../class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Permiso_acceso_a_modulos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("conceder_denegar_permisos.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");
$xajax->register(XAJAX_FUNCTION,"PERMISOS_ROLES");


function PERMISOS($permiso, $id_usuario, $id_archivo, $numeradoDIV="usuario")
{
	
	$html="";
	$objResponse = new xajaxResponse();
	$div='div_informacion';
	
	if(DEBUG){$html.="Permiso:$permiso<br>id_usuario: $id_usuario<br>id_archivo:$id_archivo<br>";}
		
	if(is_numeric($id_usuario)){$continuar_1=true;}
	else{$continuar_1=false;}
	
	if(is_numeric($id_archivo)){$continuar_2=true;}
	else{ $continuar_2=false;}
	
	if($continuar_1 and $continuar_2)
	{
		if($numeradoDIV=="usuario"){$div_solicitante="div_".$id_usuario;}
		else{$div_solicitante="div_".$id_archivo;}
		
		switch($permiso)
		{
			case 1:
				//DAR PERMISO
				$cons_X="INSERT INTO okalis_relacion_usuario_archivo (id_archivo, id_usuario) VALUES ('$id_archivo', '$id_usuario')";
				if(DEBUG){$html.="Dar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS(0, '.$id_usuario.', '.$id_archivo.', \''.$numeradoDIV.'\'); return false;">OK</a>';
				break;
			case 0:
				//quitar permiso
				$cons_X="DELETE FROM okalis_relacion_usuario_archivo WHERE id_usuario='$id_usuario' AND id_archivo='$id_archivo' LIMIT 1";
				if(DEBUG){$html.="quitar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS(1, '.$id_usuario.', '.$id_archivo.', \''.$numeradoDIV.'\'); return false;">X</a>';
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

function PERMISOS_ROLES($permiso, $id_rol, $id_archivo, $numeradoDIV="roles")
{
	
	$html="";
	$objResponse = new xajaxResponse();
	$div='div_informacion';
	
	if(DEBUG){$html.="Permiso:$permiso<br>id_rol: $id_rol<br>id_archivo:$id_archivo<br>";}
		
	if(is_numeric($id_rol)){$continuar_1=true;}
	else{$continuar_1=false;}
	
	if(is_numeric($id_rol)){$continuar_2=true;}
	else{ $continuar_2=false;}
	
	if($continuar_1 and $continuar_2)
	{
		if($numeradoDIV=="roles"){$div_solicitante="div_R".$id_rol;}
		
		switch($permiso)
		{
			case 1:
				//DAR PERMISO
				$cons_X="INSERT INTO okalis_relacion_rol_archivo (id_archivo, id_rol) VALUES ('$id_archivo', '$id_rol')";
				if(DEBUG){$html.="Dar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS_ROLES(0, '.$id_rol.', '.$id_archivo.', \''.$numeradoDIV.'\'); return false;">OK</a>';
				break;
			case 0:
				//quitar permiso
				$cons_X="DELETE FROM okalis_relacion_rol_archivo WHERE id_rol='$id_rol' AND id_archivo='$id_archivo' LIMIT 1";
				if(DEBUG){$html.="quitar permiso<br>".$cons_X;}
				$check='<a href="#" onclick="xajax_PERMISOS_ROLES(1, '.$id_rol.', '.$id_archivo.', \''.$numeradoDIV.'\'); return false;">X</a>';
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