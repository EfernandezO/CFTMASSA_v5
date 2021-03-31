<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////--/XAJAX/----////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registra_egresos_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"SELECCION_FORMULARIO");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_FACTURAS");
$xajax->register(XAJAX_FUNCTION,"CARGAR_SALDO_FACTURA");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"UTILIZAR_RUT");
$xajax->register(XAJAX_FUNCTION,"COMPROBANTE_EGRESO_ELIGE_TIPO");
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");

///////////////----------------////////////////////////////
function BUSCA_PROVEEDOR($rutBusqueda){
	 //---------------------------------------------------//
	 $objResponse = new xajaxResponse();
	require("../../../funciones/funciones_varias.php");
	require("../../../funciones/conexion_v2.php");
	 $divDestino="areaProveedor";
	  $campo_proveedor='<select name="proveedor" id="proveedor" onchange="xajax_BUSCAR_FACTURAS(this.value);">';
	 
	 $cons_P="SELECT id_proveedor, rut, razon_social FROM proveedores ORDER BY rut";
	 
	 $sqli_P=$conexion_mysqli->query($cons_P);
	 $num_proveedores=$sqli_P->num_rows;
	 $rutIdentificado=false;
	 $rutValido=false;
	 
	 if(RUT_OK($rutBusqueda)){$rutValido=true;}
	 
	 
		 if($num_proveedores>0)
		 {
			 $campo_proveedor.='<option value="0">Seleccion Proveedor</option>';
			 while($P=$sqli_P->fetch_assoc())
			 {
				 $P_id=$P["id_proveedor"];
				 $P_rut=$P["rut"];
				 $P_razon_social=$P["razon_social"];
				 
				 $selected='';
				 if($rutValido){
				 	if((!empty($rutBusqueda))and($P_rut==$rutBusqueda)){$rutIdentificado=true; $selected='selected="selected"'; $objResponse->script('xajax_BUSCAR_FACTURAS('.$P_id.');');}
				 }
				 
				 $campo_proveedor.='<option value="'.$P_id.'" '.$selected.'>'.$P_rut.' '.$P_razon_social.'</option>';
			 }
		 }
		 else
		 {  $campo_proveedor.='<option value="0">Sin Proveedores Cargados</option>';}
		 $sqli_P->free();
		 $campo_proveedor.='</select>';
		 //---------------------------------------------------------------------------------------//
		 
		 $campo_proveedor.='(busca por rut) <input type="text" name="buscaProveedor" id="buscaProveedor"/>
		 <a href="#"  class="button_R" onclick="xajax_BUSCA_PROVEEDOR(document.getElementById(\'buscaProveedor\').value)">Buscar</a>';
		 
		 //-------------------------------------------------------------------------------------------//
		 if($rutValido){if(!$rutIdentificado){$objResponse->alert('Proveedor NO encontrado, primero debe ser agregado...');}}
		 else{$objResponse->alert('Rut Invalido');}
	
	$objResponse->Assign($divDestino,"innerHTML",$campo_proveedor);
	$conexion_mysqli->close();
	return $objResponse;
}

function SELECCION_FORMULARIO($tipo_documento)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	$fecha_actual=date("Y-d-m");
	$dia_actual=date("d");
	$mes_actual=date("m");
	$year_actual=date("Y");
	
	$html_formulario='';
	$div="div_formulario";
	
	 $campo_dia='<select name="dia">';
	 for($d=1;$d<=31;$d++)
	 {
		 if($d==$dia_actual){$select='selected="selected"';}
		 else{$select="";}
		 $campo_dia.='<option value="'.$d.'" '.$select.'>'.$d.'</option>';
	 }
	 $campo_dia.='</select>';
	 
	 //---------------------------------------------------//
	  $campo_proveedor='<div id="areaProveedor"><select name="proveedor" id="proveedor" onchange="xajax_BUSCAR_FACTURAS(this.value);">';
	 
	 $cons_P="SELECT id_proveedor, rut, razon_social FROM proveedores ORDER BY rut";
	 
	 $sqli_P=$conexion_mysqli->query($cons_P);
	 $num_proveedores=$sqli_P->num_rows;
	 if($num_proveedores>0)
	 {
		 $campo_proveedor.='<option value="0">Seleccion Proveedor</option>';
		 while($P=$sqli_P->fetch_assoc())
		 {
			 $P_id=$P["id_proveedor"];
			 $P_rut=$P["rut"];
			 $P_razon_social=$P["razon_social"];
			 $campo_proveedor.='<option value="'.$P_id.'">'.$P_rut.' '.$P_razon_social.'</option>';
		 }
	 }
	 else
	 {  $campo_proveedor.='<option value="0">Sin Proveedores Cargados</option>';}
	 $sqli_P->free();
	 $campo_proveedor.='</select>';
	 //---------------------------------------------------------------------------------------//
	 
	 $campo_proveedor.='(busca por rut) <input type="text" name="buscaProveedor" id="buscaProveedor"/>
	 <a href="#" class="button_R" onclick="xajax_BUSCA_PROVEEDOR(document.getElementById(\'buscaProveedor\').value)">Buscar</a></div>';
	 
	 //--------------------------------------------------------------------------------------//
	 $campo_rut_personal='<select name="rut_personalLista" id="rut_personalLista" onchange="xajax_UTILIZAR_RUT(this.value)">
	 <option value="seleccione">seleccione</option>';
	 ///-------------------------//
	 ///ayuda para rut
	 $cons_PR="SELECT rut, nombre, apellido_P, apellido_M FROM personal ORDER BY apellido_P, apellido_M";
	 $sqli_PR=$conexion_mysqli->query($cons_PR)or die($conexion_mysqli->error);
	 $num_personal=$sqli_PR->num_rows;
	 if($num_personal>0)
	 {
		 while($PR=$sqli_PR->fetch_assoc())
		 {
			$PR_rut=$PR["rut"];
			$PR_nombre=$PR["nombre"];
			$PR_apellido_P=$PR["apellido_P"];
			$PR_apellido_M=$PR["apellido_M"];
			
			 $campo_rut_personal.='<option value="'.$PR_rut.'">'.$PR_apellido_P.' '.$PR_apellido_M.' '.$PR_nombre.' ----</option>';
		 }
	}
	 else
	 {  $campo_rut_personal.='<option value="0">Sin personal</option>';}
	 $sqli_PR->free();
	 $campo_rut_personal.='</select>';
	 
	 $tabla_forma_pago=' 
	 <table width="100%">
	 <thead>
	 	<tr>
			<th colspan="5">Forma de pago</th>
		</tr>
	 </thead>
	 <tbody>
	 <tr >
    <td ><strong>Foma de Pago</strong></td>
    <td ><input name="forma_pago" type="radio" id="radio" value="efectivo" checked="checked" />Efectivo</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr >
    <td height="26">&nbsp;</td>
    <td><input type="radio" name="forma_pago" id="radio2" value="cheque" />
      Cheque</td>
    <td><div align="left">Fecha Vencimiento';
     
	$tabla_forma_pago.=$campo_dia.CAMPO_SELECCION("mes","meses",$mes_actual,false).CAMPO_SELECCION("year","year",$year_actual,false);

    $tabla_forma_pago.='</div></td>
    <td>Numero
      <input name="cheque_numero" type="text" id="cheque_numero" size="15" /></td>
    <td>Banco<br />
      '.CAMPO_SELECCION("cheque_banco", "bancos","",false).'</td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td ><input type="radio" name="forma_pago" id="radio3" value="deposito" />
      Deposito </td>
    <td ><label for="id_cta_cte"></label>
      <select name="id_cta_cte" id="id_cta_cte">';
        
        $cons="SELECT id, banco, num_cuenta FROM cuenta_corriente";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_cuentas=$sqli->num_rows;
		if($num_cuentas>0)
		{
			while($C=$sqli->fetch_assoc())
			{
				$C_id=$C["id"];
				$C_banco=$C["banco"];
				$C_num_cuenta=$C["num_cuenta"];
				
				$tabla_forma_pago.='<option value="'.$C_id.'">'.$C_banco.'-'.$C_num_cuenta.'</option>';
			}
		}
		else
		{ echo'<option value="0">Sin Cta. Cte.</option>';}
		$sqli->free();
		
	
     $tabla_forma_pago.='</select></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					  </tr >
	<tr >
    <td >&nbsp;</td>
    <td ><input type="radio" name="forma_pago" id="radio4" value="transferencia" />Transferencia</td>
    <td ><label for="T_id_cta_cte"></label>
      <select name="T_id_cta_cte" id="T_id_cta_cte">';
        
        $cons="SELECT id, banco, num_cuenta FROM cuenta_corriente";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_cuentas=$sqli->num_rows;
		if($num_cuentas>0)
		{
			while($C=$sqli->fetch_assoc())
			{
				$C_id=$C["id"];
				$C_banco=$C["banco"];
				$C_num_cuenta=$C["num_cuenta"];
				
				$tabla_forma_pago.='<option value="'.$C_id.'">'.$C_banco.'-'.$C_num_cuenta.'</option>';
			}
		}
		else
		{ echo'<option value="0">Sin Cta. Cte.</option>';}
		$sqli->free();
		
	
      $tabla_forma_pago.='</select></td>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					  </tr >
					  </tbody>
					  </table>';
	 
	 
	 //---------------------------------------------------
	switch($tipo_documento)
	{
		case"boleta":
			$html_formulario='<table width="100%" border="1" align="left">
			  <thead>
  <tr>
    <th colspan="5">Boleta</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td colspan="2">Proveedor</td>
    <td colspan="3"><div id="areaProveedor">'.$campo_proveedor.'</div></td>
  </tr>
   <tr>
    <td colspan="2">concepto</td>
    <td colspan="3">'.CAMPO_SELECCION("concepto", "conceptos_financieros_egresos","",false).'</td>
  </tr>
  <tr>
    <td colspan="2">Numero boleta</td>
    <td colspan="3"><label for="numero_boleta"></label>
      <input type="text" name="numero_boleta" id="numero_boleta" /></td>
  </tr>
  <tr>
    <td colspan="2">Responsable del Gasto (rut)</td>
    <td colspan="3"><label for="responsable_de_gasto"></label>
      <input type="text" name="responsable_gasto" id="responsable_gasto" /> <- '.$campo_rut_personal.'</td>
  </tr>
  <tr>
    <td colspan="2">Glosa o detalle</td>
    <td colspan="3"><label for="glosa"></label>
      <input type="text" name="glosa" id="glosa" size="50"/></td>
  </tr>
  <tr>
    <td colspan="2">Valor</td>
    <td colspan="3"><label for="valor"></label>
      <input type="text" name="valor" id="valor" /></td>
  </tr>
		  </tbody>
		</table>';
			break;
		case"boleta_honorario":
			$html_formulario='<table width="100%" border="1">
			<thead>
			  <tr>
				<th colspan="5">Boleta Honorario</th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
				<td colspan="2">Proveedor</td>
				<td colspan="3">'.$campo_proveedor.'</td>
			  </tr>
			   <tr>
				<td colspan="2">concepto</td>
				<td colspan="3">'.CAMPO_SELECCION("concepto", "conceptos_financieros_egresos","",false).'</td>
			  </tr>
			   <tr>
				<td colspan="2">Responsable del Gasto (rut)</td>
				<td colspan="3"><label for="responsable_de_gasto"></label>
				  <input type="text" name="responsable_gasto" id="responsable_gasto" /> <- '.$campo_rut_personal.'</td>
			  </tr>
			  <tr>
				<td colspan="2">Numero de Boleta</td>
				<td colspan="3"><label for="numero_boleta"></label>
				  <input type="text" name="numero_boleta" id="numero_boleta" /></td>
			  </tr>
			  <tr>
				<td colspan="2">Glosa o detalle</td>
				<td colspan="3"><label for="glosa"></label>
				  <input type="text" name="glosa" id="glosa" size="50"/></td>
			  </tr>
			  <tr>
				<td colspan="2">Valor</td>
				<td colspan="3"><label for="valor"></label>
				  <input type="text" name="valor" id="valor" /></td>
			  </tr>
			  </tbody>
			</table>';
			break;
		case"factura":
			$html_formulario='<table width="100%" border="1">
			<thead>
			  <tr>
				<th colspan="5">Facturas</th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
				<td>Proveedor</td>
				<td colspan="4">'.$campo_proveedor.'</td>
			  </tr>
			   <tr>
				<td>factura</td>
				<td colspan="4"><div id="div_facturas">Seleccione Proveedor</div></td>
			  </tr>
			   <tr>
				<td>concepto</td>
				<td colspan="4">'.CAMPO_SELECCION("concepto", "conceptos_financieros_egresos","",false).'</td>
			  </tr>
			   <tr>
				<td>Responsable del Gasto (rut)</td>
				<td colspan="4"><label for="responsable_de_gasto"></label>
				  <input type="text" name="responsable_gasto" id="responsable_gasto" /> <- '.$campo_rut_personal.'</td>
			  </tr>
			  <tr>
				<td>Glosa o detalle</td>
				<td colspan="4"><label for="glosa"></label>
				  <input type="text" name="glosa" id="glosa" size="50"  value="Pago de Factura"/></td>
			  </tr>
			  <tr>
				<td >Valor</td>
				<td colspan="4"><label for="valor"></label>
				  <input type="text" name="valor" id="valor" /></td>
			  </tr>
			  </tbody>
			</table>';
			break;
		case"comprobante_egreso":
			$html_formulario='<table width="100%" border="1">
			<thead>
			  <tr>
				<th colspan="5">Comprobante Egreso</th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
				<td colspan="2">Proveedor/funcionario</td>
				<td>Proveedor<input name="tipo_proveedor" type="radio" value="proveedor" checked onClick="xajax_COMPROBANTE_EGRESO_ELIGE_TIPO(this.value)" /></td>
				<td>Funcionario<input name="tipo_proveedor" type="radio" value="personal" onClick="xajax_COMPROBANTE_EGRESO_ELIGE_TIPO(this.value)"/></td>
			  </tr>
			  
			  <tr>
				  
				<td colspan="2"><div id="area_tipo_proveedorL1">Proveedor</div></td>
				<td colspan="3"><div id="area_tipo_proveedorL2">'.$campo_proveedor.'</div></td>
				 
			  </tr>
			  
			   <tr><td>&nbsp;</td></tr>
			   <tr>
				<td colspan="2">concepto</td>
				<td colspan="3">'.CAMPO_SELECCION("concepto", "conceptos_financieros_egresos","",false).'</td>
			  </tr>
			 
			   <tr>
				<td colspan="2">Responsable del Gasto (rut)</td>
				<td colspan="3"><label for="responsable_de_gasto"></label>
				  <input type="text" name="responsable_gasto" id="responsable_gasto"  readonly="readonly"/> <- '.$campo_rut_personal.'</td>
			  </tr>
			 
			  <tr>
				<td colspan="2">Glosa o detalle</td>
				<td colspan="3"><label for="glosa"></label>
				  <input type="text" name="glosa" id="glosa" size="50"/></td>
			  </tr>
			  <tr>
				<td colspan="2">Valor</td>
				<td colspan="3"><label for="valor"></label>
				  <input type="text" name="valor" id="valor" /></td>
			  </tr>
			  </tbody>
			</table>';
			break;
		default:
			$html_formulario='';				
	}
	
	//agrego forma de pago a todos los tipos de documento
	$html_formulario.=''.$tabla_forma_pago;
	
	//-----------------------------------------------//
		$html_formulario.='<br><br><br><div align="center"><a href="#" class="button_R" onclick="xajax_VERIFICAR(xajax.getFormValues(\'frm\'))">Confirmar y Grabar</a></div>';
	//-------------------------------------------------//
	
	$objResponse->Assign($div,"innerHTML",$html_formulario);
	$conexion_mysqli->close();
	return $objResponse;
}

//********************************************************//
function BUSCAR_FACTURAS($id_proveedor)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	$div="div_facturas";
	
	$campo_factura='<select name="factura" id="factura" onchange="xajax_CARGAR_SALDO_FACTURA(this.value)">';
	$cons_F="SELECT id, cod_factura, saldo FROM facturas WHERE id_proveedor='$id_proveedor' AND condicion='pendiente' ORDER by id";
	$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
	$num_facturas=$sqli_F->num_rows;
	if($num_facturas>0)
	{
		$campo_factura.='<option value="0">seleccione</option>';
		while($F=$sqli_F->fetch_assoc())
		{
			$F_id=$F["id"];
			$F_cod_factura=$F["cod_factura"];
			$F_saldo=$F["saldo"];
			$campo_factura.='<option value="'.$F_id.'">'.$F_cod_factura.'</option>';
		}
	}
	else
	{$campo_factura.='<option value="0">Sin Facturas Pendientes</option>';}
	$sqli_F->free();
	
	$campo_factura.='</select>';
	
	$objResponse->Assign($div,"innerHTML",$campo_factura);
	$conexion_mysqli->close();
	return $objResponse;
}
//--------------------------------------------------------------//
function CARGAR_SALDO_FACTURA($id_factura)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	$campo="valor";
	
	$id_factura=mysqli_real_escape_string($conexion_mysqli, $id_factura);
	
	$cons_F="SELECT id, cod_factura, saldo FROM facturas WHERE condicion='pendiente' AND  id='$id_factura' LIMIT 1";
	$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
	$num_facturas=$sqli_F->num_rows;
	if($num_facturas>0)
	{
		$F=$sqli_F->fetch_assoc();
		$F_id=$F["id"];
		$F_cod_factura=$F["cod_factura"];
		$F_saldo=$F["saldo"];
	}
	else
	{}
	$sqli_F->free();
	
	
	
	$objResponse->Assign($campo,"value",$F_saldo);
	$conexion_mysqli->close();
	return $objResponse;
}
function VERIFICAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/funciones_varias.php");
	
	$tipo_documento=$FORMULARIO["tipo_documento"];
	//$objResponse->Alert("Tipo Documento: $tipo_documento");
	//obtengo los datos de las formas de pago comunes
	
	$forma_pago=$FORMULARIO["forma_pago"];
	//cheque
	$dia=strip_tags($FORMULARIO["dia"]);
	$mes=strip_tags($FORMULARIO["mes"]);
	$year=strip_tags($FORMULARIO["year"]);
	$cheque_numero=strip_tags($FORMULARIO["cheque_numero"]);
	$cheque_banco=strip_tags($FORMULARIO["cheque_banco"]);
	//--------------------------------------------//
	//deposito
	$id_cta_cte=strip_tags($FORMULARIO["id_cta_cte"]);
	//----------------------------------------------//
	
	$formaPagoOK=true;
	switch($forma_pago)
	{
		case"efectivo":
			break;
		case"cheque":
				if(!checkdate($mes,$dia,$year)){$objResponse->Alert("Ingrese Fecha de Vencimiento Correcta"); $formaPagoOK=false;}
				if(empty($cheque_numero)){ $objResponse->Alert("Ingrese Numero de Cheque"); $formaPagoOK=false;}
				elseif(EXISTE_CHEQUE($cheque_numero, $cheque_banco)){ $objResponse->Alert("Ya existe un cheque con estos datos Registrados\n ingrese otros"); $formaPagoOK=false;}
				else{$objResponse->Alert("Cheque Ok");}
			break;
		case"deposito":
			break;
		case"transferencia":
			break;	
	}
	
	if(!$formaPagoOK){ $objResponse->Alert("Problema en la seleccion de la forma de Pago\n Por favor verificar");}
	
	 
	 switch($tipo_documento)
	 {
		 case"boleta":
		 	$continuar_1=true;
		 		$proveedor=strip_tags($FORMULARIO["proveedor"]);
				$concepto=$FORMULARIO["concepto"];
				$numero_boleta=strip_tags($FORMULARIO["numero_boleta"]);
				$glosa=strip_tags($FORMULARIO["glosa"]);
				$valor=strip_tags($FORMULARIO["valor"]);
				
				//---------------------------------------------------------------//
				$rut_responsable_gasto=strip_tags($FORMULARIO["responsable_gasto"]);
				$rut_responsable_gasto=str_replace(".","",$rut_responsable_gasto);
				$rut_responsable_gasto=str_replace(" ","",$rut_responsable_gasto);
				///----------------------------------//

				
				if(!RUT_OK($rut_responsable_gasto)){$objResponse->Alert("Rut responsable del gasto Incorrecto"); $continuar_1=false;}
				else{ $objResponse->Assign("responsable_gasto","value",$rut_responsable_gasto);}
				
				if($proveedor==0){ $objResponse->Alert("Seleccione o cargue proveedor"); $continuar_1=false;}
				if(empty($numero_boleta)){ $objResponse->Alert("Ingrese Numero de Boleta"); $continuar_1=false;}
				if(empty($glosa)){ $objResponse->Alert("Ingrese Glosa o Detalle"); $continuar_1=false;}
				if(!is_numeric($valor)){ $objResponse->Alert("Ingrese Valor"); $continuar_1=false;}
				
		 	break;
		 case"factura":
		 	$continuar_1=true;
			$proveedor=strip_tags($FORMULARIO["proveedor"]);
			$concepto=$FORMULARIO["concepto"];
			$glosa=strip_tags($FORMULARIO["glosa"]);
			$valor=strip_tags($FORMULARIO["valor"]);
			$forma_pago=$FORMULARIO["forma_pago"];
			$factura=strip_tags($FORMULARIO["factura"]);
			//---------------------------------------------------------------//
				$rut_responsable_gasto=strip_tags($FORMULARIO["responsable_gasto"]);
				$rut_responsable_gasto=str_replace(".","",$rut_responsable_gasto);
				$rut_responsable_gasto=str_replace(" ","",$rut_responsable_gasto);
			//----------------------------------------------------------------//
			if(!RUT_OK($rut_responsable_gasto)){$objResponse->Alert("Rut responsable del gasto Incorrecto"); $continuar_1=false;}
			else{ $objResponse->Assign("responsable_gasto","value",$rut_responsable_gasto);}
			//---------------------------------------------------------------------------------------------//
			if($proveedor==0){ $objResponse->Alert("Seleccione o cargue proveedor"); $continuar_1=false;}
			if($factura==0){ $objResponse->Alert("Seleccione o cargue facturas a este proveedor"); $continuar_1=false;}
			if(empty($glosa)){ $objResponse->Alert("Ingrese Glosa o Detalle"); $continuar_1=false;}
			///----------------------------------//
		 	break;
		case"comprobante_egreso":
			
			$continuar_1=true;
			
			$concepto=$FORMULARIO["concepto"];
			$glosa=strip_tags($FORMULARIO["glosa"]);
			$valor=strip_tags($FORMULARIO["valor"]);
			$forma_pago=$FORMULARIO["forma_pago"];
			//$numero_comprobante_E=$FORMULARIO["numero_comprobante_E"];
			$tipo_proveedor=$FORMULARIO["tipo_proveedor"];
			
			switch($tipo_proveedor)
			{
				case"personal":
					//-----------------------------------------------------------------//
					$rut_personal=strip_tags($FORMULARIO["rut_personal"]);
					$rut_personal=str_replace(".","",$rut_personal);
					$rut_personal=str_replace(" ","",$rut_personal);
					//-------------------------------------------------------------------//
					if(!RUT_OK($rut_personal)){$objResponse->Alert("Rut de Personal Incorrecto"); $continuar_1=false;}
					else{ $objResponse->Assign("rut_personal","value",$rut_personal);}
					//---------------------------------------------------------------------------------------------//
					break;
				case"proveedor":
					$proveedor=strip_tags($FORMULARIO["proveedor"]);
					if($proveedor==0){ $objResponse->Alert("Seleccione o cargue proveedor"); $continuar_1=false;}
					break;
			}
					
			//-----------------------------------------------------------------//
			$rut_responsable_gasto=strip_tags($FORMULARIO["responsable_gasto"]);
			$rut_responsable_gasto=str_replace(".","",$rut_responsable_gasto);
			$rut_responsable_gasto=str_replace(" ","",$rut_responsable_gasto);
			//-------------------------------------------------------------------//
			
			if(!RUT_OK($rut_responsable_gasto)){$objResponse->Alert("Rut responsable del gasto Incorrecto"); $continuar_1=false;}
			else{ $objResponse->Assign("responsable_gasto","value",$rut_responsable_gasto);}
			//---------------------------------------------------------------------------------------------//
			//if(empty($numero_comprobante_E)){$objResponse->Alert("Ingrese Numero de Comprobante de Egreso"); $continuar_1=false;}
			if(empty($glosa)){ $objResponse->Alert("Ingrese Glosa o Detalle"); $continuar_1=false;}
			if(!is_numeric($valor)){ $objResponse->Alert("Ingrese Valor"); $continuar_1=false;}
			break;
		case"boleta_honorario":	
			$continuar_1=true;
			$proveedor=strip_tags($FORMULARIO["proveedor"]);
			$concepto=$FORMULARIO["concepto"];
			$glosa=strip_tags($FORMULARIO["glosa"]);
			$valor=strip_tags($FORMULARIO["valor"]);
			$forma_pago=$FORMULARIO["forma_pago"];
			$numero_boleta=$FORMULARIO["numero_boleta"];
			$rut_responsable_gasto=strip_tags($FORMULARIO["responsable_gasto"]);
			//-----------------------------------------------------------------//
			$rut_responsable_gasto=strip_tags($FORMULARIO["responsable_gasto"]);
			$rut_responsable_gasto=str_replace(".","",$rut_responsable_gasto);
			$rut_responsable_gasto=str_replace(" ","",$rut_responsable_gasto);
			//-------------------------------------------------------------------//
			if(!RUT_OK($rut_responsable_gasto)){$objResponse->Alert("Rut responsable del gasto Incorrecto"); $continuar_1=false;}
			else{ $objResponse->Assign("responsable_gasto","value",$rut_responsable_gasto);}
			//---------------------------------------------------------------------------------------------//
			if($proveedor==0){ $objResponse->Alert("Seleccione o cargue proveedor"); $continuar_1=false;}
			if(empty($glosa)){ $objResponse->Alert("Ingrese Glosa o Detalle"); $continuar_1=false;}
			if(!is_numeric($valor)){ $objResponse->Alert("Ingrese Valor"); $continuar_1=false;}
			if(!is_numeric($numero_boleta)){ $objResponse->Alert("Ingrese numero de boleta"); $continuar_1=false;}
			
			break;
	 }
	 if($continuar_1 and $formaPagoOK){$objResponse->script('CONFIRMAR();');}	
	 
	return $objResponse;
}
function UTILIZAR_RUT($aux_rut, $destino="responsable_gasto")
{
	$objResponse = new xajaxResponse();
	if($aux_rut!=="seleccione")
	{ $objResponse->Assign($destino,"value",$aux_rut);}
	return $objResponse;
}

function COMPROBANTE_EGRESO_ELIGE_TIPO($tipo_proveedor)
{
	require("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	//$objResponse->Alert("tipo Proveedor".$tipo_proveedor);
	 //---------------------------------------------------//
	  $campo_proveedor='<select name="proveedor" id="proveedor" onchange="xajax_BUSCAR_FACTURAS(this.value);">';
	 
	 $cons_P="SELECT id_proveedor, rut, razon_social FROM proveedores ORDER BY razon_social";
	 
	 $sqli_P=$conexion_mysqli->query($cons_P);
	 $num_proveedores=$sqli_P->num_rows;
	 if($num_proveedores>0)
	 {
		 $campo_proveedor.='<option value="0">Seleccion Proveedor</option>';
		 while($P=$sqli_P->fetch_assoc())
		 {
			 $P_id=$P["id_proveedor"];
			 $P_rut=$P["rut"];
			 $P_razon_social=$P["razon_social"];
			 $campo_proveedor.='<option value="'.$P_id.'">'.$P_razon_social.' ['.$P_rut.']</option>';
		 }
	 }
	 else
	 {  $campo_proveedor.='<option value="0">Sin Proveedores Cargados</option>';}
	 $sqli_P->free();
	 $campo_proveedor.='</select>';
	 
	 //---------------------------------------------------
	 //--------------------------------------------------------------------------------------
	 $campo_rut_personal2='<select name="rut_personal2" id="rut_personal2" onchange="xajax_UTILIZAR_RUT(this.value, \'rut_personal\')">
	 <option value="seleccione">seleccione</option>';
	 ///-------------------------//
	 ///ayuda para rut
	 $cons_PR="SELECT rut, nombre, apellido_P, apellido_M, con_acceso FROM personal ORDER BY con_acceso, apellido_P, apellido_M ";
	 $sqli_PR=$conexion_mysqli->query($cons_PR)or die($conexion_mysqli->error);
	 $num_personal=$sqli_PR->num_rows;
	 if($num_personal>0)
	 {
		 while($PR=$sqli_PR->fetch_assoc())
		 {
			$PR_rut=$PR["rut"];
			$PR_nombre=$PR["nombre"];
			$PR_apellido_P=$PR["apellido_P"];
			$PR_apellido_M=$PR["apellido_M"];
			$PR_ACCESO=$PR["con_acceso"];
			
			 $campo_rut_personal2.='<option value="'.$PR_rut.'">'.$PR_apellido_P.' '.$PR_apellido_M.' '.$PR_nombre.' ['.$PR_ACCESO.']</option>';
		 }
	}
	 else
	 {  $campo_rut_personal2.='<option value="0">Sin personal</option>';}
	 $sqli_PR->free();
	 $campo_rut_personal2.='</select>';
	 
	 
	 //---------------------------------------------------
	switch($tipo_proveedor)
	{
		case"proveedor":
			$tipo_areaL1='Proveedor';
			$tipo_areaL2=$campo_proveedor;
			break;
		case"personal":
			$tipo_areaL1='Personal';
				$tipo_areaL2='<input type="text" name="rut_personal" id="rut_personal"  readonly="readonly"/> <- '.$campo_rut_personal2;
			break;
	}
	$objResponse->Assign("area_tipo_proveedorL1","innerHTML",$tipo_areaL1);
	$objResponse->Assign("area_tipo_proveedorL2","innerHTML",$tipo_areaL2);
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>