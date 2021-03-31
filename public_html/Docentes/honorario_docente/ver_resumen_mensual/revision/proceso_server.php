<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"APRUEBA_CONTABILIDAD");
$xajax->register(XAJAX_FUNCTION,"DESAPRUEBA_CONTABILIDAD");


function APRUEBA_CONTABILIDAD($posicion, $id_honorario)
{
	
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
		require("../../../../../funciones/conexion_v2.php");
		$objResponse = new xajaxResponse();
		$div='div_estado_'.$posicion;
		$div_2='div_user_'.$posicion;
		$div_3='div_fecha_'.$posicion;
				
		$funcion_boton='onclick="xajax_DESAPRUEBA_CONTABILIDAD('.$posicion.', '.$id_honorario.'); return false;"';
		$msj_boton='title="click para indicar que NO esta disponible para pago"';
		$html='<a href="#" class="button" '.$funcion_boton.' '.$msj_boton.'>ok</a>';
				
		if(($id_honorario>0)and(is_numeric($id_honorario)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
		
		if($continuar)
		{
			$nuevo_estado="ok";
			$cons_UP="UPDATE honorario_docente SET generado_contabilidad='$nuevo_estado', fecha_generado_contabilidad='$fecha_actual', id_user_generado_contabilidad='$id_usuario_actual' WHERE id_honorario='$id_honorario' LIMIT 1";
			if($conexion_mysqli->query($cons_UP))
			{
				$objResponse->Assign($div,"innerHTML",$html);
				$objResponse->Assign($div_2,"innerHTML",$id_usuario_actual);
				$objResponse->Assign($div_3,"innerHTML",$fecha_actual);
			}
			else{ if(DEBUG){$objResponse->alert($conexion_mysqli->error);}}
		}
		
		$conexion_mysqli->close();
		mysql_close($conexion);
		return $objResponse;
	
}
function DESAPRUEBA_CONTABILIDAD($posicion, $id_honorario)
{
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		require("../../../../../funciones/conexion_v2.php");
		$objResponse = new xajaxResponse();
		$div='div_estado_'.$posicion;
				
		$funcion_boton='onclick="xajax_APRUEBA_CONTABILIDAD('.$posicion.', '.$id_honorario.'); return false;"';
		$msj_boton='title="click para indicar que esta disponible para pago"';
		$html='<a href="#" class="button_R" '.$funcion_boton.' '.$msj_boton.'>Aprobar</a>';
				
		if(($id_honorario>0)and(is_numeric($id_honorario)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
		
		$cons_Hx="SELECT estado FROM honorario_docente WHERE id_honorario='$id_honorario' LIMIT 1";
		$sqli_Hx=$conexion_mysqli->query($cons_Hx);
		$DHx=$sqli_Hx->fetch_assoc();
			$H_estado=$DHx["estado"];
			if(empty($H_estado)){$H_estado="pendiente";}
		$sqli_Hx->free();
		
		if($H_estado=="pendiente")	
		{ $continuar_2=true;}
		else
		{ $continuar_2=false; $objResponse->alert("Imposible Cambiar de Estado Honorario ya a sido Entregado!!!");}
		
		if($continuar and $continuar_2)
		{
			$nuevo_estado="pendiente";
			$cons_UP="UPDATE honorario_docente SET generado_contabilidad='$nuevo_estado', fecha_generado_contabilidad='$fecha_actual', id_user_generado_contabilidad='$id_usuario_actual' WHERE id_honorario='$id_honorario' LIMIT 1";
			if($conexion_mysqli->query($cons_UP))
			{$objResponse->Assign($div,"innerHTML",$html);}
			else{ if(DEBUG){$objResponse->alert($conexion_mysqli->error);}}
		}
		
		$conexion_mysqli->close();
		mysql_close($conexion);
		return $objResponse;
	
}
$xajax->processRequest();
?>