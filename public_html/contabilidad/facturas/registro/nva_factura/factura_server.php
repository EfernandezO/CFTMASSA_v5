<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("factura_server.php");
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"REVISAR_OC");
////////////////////////////////////////////
function BUSCA_PROVEEDOR($rut_proveedor, $razon_social, $direccion, $ciudad)
{
	$msj_informacion="";
	$proveedor_seleccionado=false;
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_varias.php");
	require("../../../../../funciones/funcion.php");
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
		
		if(strpos($rut_proveedor,"-"))
		{
			$array_rut_proveedor=explode("-",$rut_proveedor);
			$dv_correcto_rut_proveedor=validar_rut($array_rut_proveedor[0]);	
			if($array_rut_proveedor[1]==$dv_correcto_rut_proveedor)
			{ $rut_correcto=true; $msj_informacion.="Rut Proveedor Correcto<br>"; $objResponse->Assign("proveedor_rut","value",$rut_proveedor);}
			else
			{ $rut_correcto=false; $msj_informacion.="Rut Proveedor Incorrecto<br>";}	
		}
		else
		{$rut_correcto=false; $msj_informacion.="Rut Proveedor Incorrecto sin el caracter (-)<br>";}
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
				$proveedor_seleccionado=true;
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
		
	}
	else
	{ if(DEBUG){$objResponse->Alert("Rut proveedor Incorrecto");}}
	//------------------------------------------------------------------//
	if($proveedor_seleccionado)
	{
		$msj_informacion.="Buscar orden de compra de Proveedor<br>";
			$cons_O="SELECT * FROM orden_compra WHERE id_proveedor='$aux_proveedor_id' ORDER by id_oc desc";
			$sql_O=$conexion_mysqli->query($cons_O);
			$num_oc=$sql_O->num_rows;
			$msj_informacion.="Numero de Orden Relacionadas: $num_oc<br>";
			if($num_oc>0)
			{
				while($P=$sql_O->fetch_assoc())
				{
					$OC_id=$P["id_oc"];
					$cons_A="SELECT COUNT(id_autorizacion) FROM autorizaciones WHERE tipo_autorizado='orden_compra' AND id_autorizado='$OC_id' AND autorizado='si'";
					$sql_A=$conexion_mysqli->query($cons_A);
					$D_A=$sql_A->fetch_row();
					$num_autorizaciones=$D_A[0];
					if(empty($num_autorizaciones)){ $num_autorizaciones=0;}
					$sql_A->free();
					if($num_autorizaciones>0)
					{ $OC_autorizada=true; $msj_informacion.="orden compra $OC_id Autorizada<br>";}
					else
					{ $OC_autorizada=false; $msj_informacion.="orden compra $OC_id NO Autorizada<br>";}
					
					if($OC_autorizada){$array_oc[$OC_id]=$OC_id;}
				}
			}
			else
			{ $array_oc[0]="Sin orden de compra";}
			
		$sql_O->free();
		
		$ordenes='<select name="orden_compra" id="orden_compra" onchange="xajax_REVISAR_OC(this.value);return false;">
            <option value="0" selected="selected">seleccione</option>';
        foreach($array_oc as $n =>$valor)
		{ $ordenes.='<option value="'.$n.'">'.$valor.'</option>';}
       $ordenes.='</select>';
	   $objResponse->Assign("oc_id","innerHTML",$ordenes);
	}				
	$conexion_mysqli->close();
	$objResponse->Assign("div_debug","innerHTML",$msj_informacion);
	return $objResponse;
}
//-----------------------------------------------------------------------------//
function VERIFICAR($FORMULARIO)
{
	$msj_informacion="";
	require("../../../../../funciones/funciones_varias.php");
	require("../../../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	//--------------------------------------------//
	//orden compra
	$OC_id=$FORMULARIO["orden_compra"];
	if($OC_id>0){ $hay_OC=true;}
	else{ $hay_OC=false;}
	///---------------------------------------------------//
	//proveedor
	$proveedor_rut=$FORMULARIO["proveedor_rut"];
	$proveedor_razon_social=$FORMULARIO["proveedor_razon_social"];
	$proveedor_id=$FORMULARIO["proveedor_id"];
	$proveedor_direccion=$FORMULARIO["proveedor_direccion"];
	$proveedor_ciudad=$FORMULARIO["proveedor_ciudad"];
	///---------------------------------------------------//
	//factura
	 $msj_informacion.="<strong>FACTURA</strong><br>";
	$factura_ok=true;
	$factura_total=$FORMULARIO["valor"];
	$factura_codigo=$FORMULARIO["cod_factura"];
	
	if(is_numeric($factura_total))
	{if($factura_total<=0){ $factura_ok=false; $msj_informacion.="Total Factura incorrecto<br>";}}
	else{$factura_ok=false; $msj_informacion.="Total Factura incorrecto<br>";}
	
	if(empty($factura_codigo)){ $factura_ok=false; $msj_informacion.="Numero Factura Vacio<br>";}
	elseif($proveedor_id>0)
	{
		//busco codigo factura
		$cons="SELECT COUNT(id) FROM facturas WHERE cod_factura='$factura_codigo' AND id_proveedor='$proveedor_id'";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$CF=$sqli->fetch_row();
			$coincidencias=$CF[0];
			if(empty($coincidencias)){ $coincidencias=0;}
		$sqli->free();
		
		if($coincidencias>0){  $factura_ok=false; $msj_informacion.="Numero Factura Repetido<br>";}
	}
	
	if($factura_ok){ $msj_informacion.="Factura OK<br>";}
	
	//------------------------------------------------------//
	$msj_informacion.="<strong>DATOS PROVEEDOR</strong><br>";
	if($proveedor_id>0)
	{ $proveedor_ok=true; $msj_informacion.="Proveedor OK<br>";}
	else
	{
		$proveedor_ok=true;
		if(empty($proveedor_razon_social)){ $proveedor_ok=false; $msj_informacion.="Razon Social Proveedor Error<br>";}
		$proveedor_rut=str_replace(".","",$proveedor_rut);
		$proveedor_rut=strtoupper($proveedor_rut);
		
		if(strpos($proveedor_rut,"-"))
		{
			$array_rut_proveedor=explode("-",$proveedor_rut);
			$dv_correcto_rut_proveedor=validar_rut($array_rut_proveedor[0]);	
			if($array_rut_proveedor[1]==$dv_correcto_rut_proveedor)
			{ $msj_informacion.="Rut Proveedor Correcto<br>"; $objResponse->Assign("proveedor_rut","value",$proveedor_rut);}
			else
			{ $proveedor_ok=false; $msj_informacion.="Rut Proveedor Incorrecto<br>";}	
		}
		else
		{$proveedor_ok=false; $msj_informacion.="Rut Proveedor Incorrecto sin el caracter (-)<br>";}
		
		if(empty($proveedor_direccion)){ $proveedor_ok=false; $msj_informacion.="Direccion Proveedor Error<br>";}
		if(empty($proveedor_ciudad)){ $proveedor_ok=false; $msj_informacion.="Ciudad Proveedor Error<br>";}
		if($proveedor_ok){ $msj_informacion.="Proveedor Nuevo OK<br>";}
	}
	
	//-------------------------------------------------------------//
	if(($proveedor_ok)and($factura_ok))
	{
		
		if($hay_OC){ $script_js="c=confirm('Seguro(a) desea Crear esta Factura...?\\n Utilizando Orden de Compra N. $OC_id'); if(c){document.getElementById('frm').submit();}";}
		else{ $script_js="c=confirm('Seguro(a) desea Crear esta Factura...?'); if(c){document.getElementById('frm').submit();}";}
		
		$msj_informacion.="Datos Correctos se puede continuar...<br>";
		$objResponse->script($script_js);
	}
	else
	{ $msj_informacion.="Datos Incorrectos NO se puede continuar...<br>";}
	
	//----------------------------------------------------------//
	$conexion_mysqli->close();
	$objResponse->Assign("div_debug","innerHTML",$msj_informacion);
	return $objResponse;
}
function REVISAR_OC($oc_id)
{
	$objResponse = new xajaxResponse();
	$msj_informacion="<strong>Revisar OC</strong><br>";
	if(is_numeric($oc_id))
	{
		if($oc_id>0){ $continuar=true;}
		else{ $continuar=false;}
	}
	else{ $continuar=false;}
	
	if($continuar)
	{
		require("../../../../../funciones/conexion_v2.php");
		
			$cons_OC="SELECT * FROM orden_compra WHERE id_oc='$oc_id' LIMIT 1";
			if(DEBUG){ $msj_informacion.=$cons_OC;}
			$sql_OC=$conexion_mysqli->query($cons_OC);
			$OC=$sql_OC->fetch_assoc();
				$OC_descripcion=$OC["descripcion"];
				$OC_sede=$OC["sede"];
			$sql_OC->free();
			//------------------------------------------------------------------//
			$cons_OC_item="SELECT * FROM orden_compra_item WHERE id_oc='$oc_id' ORDER by id_item";	
			$sql_oci=$conexion_mysqli->query($cons_OC_item);
			$num_item=$sql_oci->num_rows;
			$msj_informacion.="Numero de Item OC $num_item<br>";
			$TOTAL_OC=0;
			if($num_item>0)	
			{
				while($OCI=$sql_oci->fetch_assoc())
				{
					$I_cantidad=$OCI["cantidad"];
					$I_valor_unitario=$OCI["valor_unitario"];
					
					$aux_total=($I_cantidad*$I_valor_unitario);
					$TOTAL_OC+=$aux_total;
				}
			}
		
			
		$sql_oci->free();	
		$conexion_mysqli->close();
	}
	else
	{
		$OC_descripcion="";
		$TOTAL_OC=0;
		$msj_informacion.="id orden compra no seleccionada...<br>";
	}

	$objResponse->Assign("valor","value",$TOTAL_OC);
	$msj_informacion.="Total OC: $TOTAL_OC<br>";
		
	$objResponse->Assign("oc_descripcion","innerHTML",$OC_descripcion);
	$objResponse->Assign("div_debug","innerHTML",$msj_informacion);
	return $objResponse;
}
$xajax->processRequest();
?>