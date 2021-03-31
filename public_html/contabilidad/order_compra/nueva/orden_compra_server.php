<?php
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("orden_compra_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
////////////////////////////////////////////
function BUSCA_PROVEEDOR($rut_proveedor, $razon_social, $direccion, $ciudad)
{
	$msj_informacion="";
	require("../../../../funciones/funciones_varias.php");
	require("../../../../funciones/funcion.php");
	$objResponse = new xajaxResponse();
	//-------------------------------------------------------------//
	///recuperacion de datos
	$formulario_razon_social=strtolower(str_inde($razon_social));
	$formulario_direccion=strtolower(str_inde($direccion));
	$formulario_ciudad=strtolower(str_inde($ciudad));
	//--------------------------------------------------------------//
	//verificacion de rut
	if(!empty($rut_proveedor))
	{
		$rut_proveedor=str_inde(str_replace(".","",$rut_proveedor));
		$rut_proveedor=strtoupper($rut_proveedor);
		$array_rut_proveedor=explode("-",$rut_proveedor);
		if(count($array_rut_proveedor)==2)	
		{
			$dv_correcto_rut_proveedor=validar_rut($array_rut_proveedor[0]);
			if($array_rut_proveedor[1]==$dv_correcto_rut_proveedor)
			{ $rut_correcto=true; $msj_informacion.="Rut Proveedor Correcto<br>";}
			else
			{ $rut_correcto=false; $msj_informacion.="Rut Proveedor Incorrecto<br>";}	
		}
		else
		{
			$rut_correcto=false; $msj_informacion.="Rut Proveedor Formato Incorrecto<br>";
		}
	}
	else
	{
		$rut_correcto=false;
		$msj_informacion.="Rut Proveedor vacio<br>";
		 ///restablesco datos
		//$objResponse->Assign("proveedor_rut","value","0");
		$objResponse->Assign("proveedor_razon_social","value","");
		$objResponse->Assign("proveedor_direccion","value","");
		$objResponse->Assign("proveedor_ciudad","value","");
		$objResponse->Assign("proveedor_id","value","0");
	}
	//--------------------------------------------------------------------///
	
	if($rut_correcto)
	{
		if(DEBUG){$objResponse->Alert("RUT proveedor Correcto: $rut_proveedor...");}
		require("../../../../funciones/conexion_v2.php");
			$cons="SELECT * FROM proveedores WHERE rut='$rut_proveedor' LIMIT 1";
			$sql=$conexion_mysqli->query($cons);
			$error_mysqli=$conexion_mysqli->error;
			if(DEBUG){$objResponse->Alert("$cons\n $error_mysqli");}	
			//------------------------------------------------------------------------///
			if(!$error_mysqli)
			{ $num_registros=$sql->num_rows;}
			else
			{ $num_registros=0;}
			//---------------------------------------------------------------//
			if($num_registros>0)
			{
				$msj_informacion.="Proveedor Encontrado en Sistema<br>";
				$PR=$sql->fetch_assoc();
				$aux_proveedor_id=$PR["id_proveedor"];
				$aux_proveedor_rut=$PR["rut"];
				$aux_proveedor_razon_social=$PR["razon_social"];
				$aux_proveedor_direccion=$PR["direccion"];
				$aux_proveedor_ciudad=$PR["ciudad"];
				
				///muestro datos
				$objResponse->Assign("proveedor_rut","value",$aux_proveedor_rut);
				$objResponse->Assign("proveedor_razon_social","value",$aux_proveedor_razon_social);
				$objResponse->Assign("proveedor_direccion","value",$aux_proveedor_direccion);
				$objResponse->Assign("proveedor_ciudad","value",$aux_proveedor_ciudad);
				$objResponse->Assign("proveedor_id","value",$aux_proveedor_id);
				$sql->free();
			}
			else
			{
				 $msj_informacion.="Proveedor NO Encontrado en Sistema<br>";
				 if(DEBUG){$objResponse->Alert("Proveedor No encontrado...\n Restablecer ");}	
				 ///restablesco datos
				//$objResponse->Assign("proveedor_rut","value","0");
				$objResponse->Assign("proveedor_razon_social","value","");
				$objResponse->Assign("proveedor_direccion","value","");
				$objResponse->Assign("proveedor_ciudad","value","");
				$objResponse->Assign("proveedor_id","value","0");
				 
			}
		
		@mysql_close($conexion);				
		$conexion_mysqli->close();
	}
	else
	{ 
		if(DEBUG){$objResponse->Alert("Rut proveedor Incorrecto");}
		 ///restablesco datos
		//$objResponse->Assign("proveedor_rut","value","0");
		$objResponse->Assign("proveedor_razon_social","value","");
		$objResponse->Assign("proveedor_direccion","value","");
		$objResponse->Assign("proveedor_ciudad","value","");
		$objResponse->Assign("proveedor_id","value","0");
	}
	
	$objResponse->Assign("div_debug","innerHTML",$msj_informacion);
	return $objResponse;
}
//-----------------------------------------------------------------------------//
function VERIFICAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$msj_informacion="<strong>DATOS PROVEEDOR</strong><br>";
	///---------------------------------------------------//
	//proveedor
	$proveedor_rut=$FORMULARIO["proveedor_rut"];
	$proveedor_razon_social=$FORMULARIO["proveedor_razon_social"];
	$proveedor_id=$FORMULARIO["proveedor_id"];
	$proveedor_direccion=$FORMULARIO["proveedor_direccion"];
	$proveedor_ciudad=$FORMULARIO["proveedor_ciudad"];
	///---------------------------------------------------//
	
	if($proveedor_id>0)
	{ $proveedor_ok=true; $msj_informacion.="Proveedor OK<br>";}
	else
	{
		$proveedor_ok=true;
		if(empty($proveedor_razon_social)){ $proveedor_ok=false; $msj_informacion.="Razon Social Proveedor Error<br>";}
		if(empty($proveedor_rut)){ $proveedor_ok=false; $msj_informacion.="Rut Proveedor Error<br>";}
		if(empty($proveedor_direccion)){ $proveedor_ok=false; $msj_informacion.="Direccion Proveedor Error<br>";}
		if(empty($proveedor_ciudad)){ $proveedor_ok=false; $msj_informacion.="Ciudad Proveedor Error<br>";}
		if($proveedor_ok){ $msj_informacion.="Proveedor Nuevo OK<br>";}
	}
	//-----------------------------------------------------------//
	//solicitudes
	$msj_informacion.="<br><strong>Solicitante</strong><br>";
	$solicitantes_ok=true;
	
	$solicitud_unidad=$FORMULARIO["solicitante_unidad"];
	$solicitud_id_responsable=$FORMULARIO["solicitante_id_responsable"];
	$solicitud_cotizacion=$FORMULARIO["solicitante_cotizacion"];
	$solicitud_condicion_pago=$FORMULARIO["solicitante_condicion_pago"];
	
	if(empty($solicitud_unidad)){$solicitantes_ok=false; $msj_informacion.="Unidad Solicitante Error<br>";}
	if($solicitud_id_responsable<=0){$solicitantes_ok=false; $msj_informacion.="Solicitante Responsable Error<br>";}
	if($solicitantes_ok){ $msj_informacion.="Solicitante ok<br>";}
	//-----------------------------------------------------------------//
	//item
	$msj_informacion.="<br><strong>ITEM</strong><br>";
	if(isset($FORMULARIO["item_cantidad"]))
	{
		$array_item_cantidad=$FORMULARIO["item_cantidad"];
		$array_item_unidad_medida=$FORMULARIO["item_unidad_medida"];
		$array_item_descripcion=$FORMULARIO["item_descripcion"];
		$array_item_valor=$FORMULARIO["item_valor"];
	
		
		$item_ok=true;
		foreach($array_item_cantidad as $indice => $aux_item_cantidad)
		{
			$tupla_correcta=true;
			
			$aux_item_unidad_medida=$array_item_unidad_medida[$indice];
			$aux_item_descripcion=$array_item_descripcion[$indice];
			$aux_item_valor=$array_item_valor[$indice];
			
			if((!is_numeric($aux_item_cantidad))or($aux_item_cantidad<=0)){ $tupla_correcta=false; $msj_informacion.="$indice -> cantidad invalida<br>";}
			if(empty($aux_item_unidad_medida)){ $tupla_correcta=false; $msj_informacion.="$indice -> unidad medida invalida<br>";}
			if(empty($aux_item_descripcion)){ $tupla_correcta=false; $msj_informacion.="$indice -> descripcion invalida<br>";}
			if((!is_numeric($aux_item_valor))or($aux_item_valor<0)){ $tupla_correcta=false; $msj_informacion.="$indice -> valor invalida<br>";}
			
			if(!$tupla_correcta)
			{
				$item_ok=false;
				break;
			}
		
		}
	}
	else
	{ $item_ok=false; $msj_informacion.="Sin Item Creados...<br>";}
	//-------------------------------------------------------------//
	if(($proveedor_ok)and($item_ok)and($solicitantes_ok))
	{
		$msj_informacion.="Datos Correctos se puede continuar...<br>";
		$objResponse->script("c=confirm('Seguro(a) desea Crear esta Orden...?'); if(c){document.getElementById('frm').submit();}");
	}
	else
	{ $msj_informacion.="Datos INcorrectos NO se puede continuar...<br>";}
	
	//----------------------------------------------------------//
	$objResponse->Assign("div_debug","innerHTML",$msj_informacion);
	return $objResponse;
}
$xajax->processRequest();
?>