<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_caja_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
@$xajax = new xajax("procesa_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"busca_pagos");
$xajax->register(XAJAX_FUNCTION,"BUSCA_EGRESOS");
////////////////////////////////////////////
function busca_pagos($fecha_inicio, $fecha_fin, $sede, $tipo_doc)
{
	if(DEBUG){ echo"Inicio Funcion<br>";}
	//sleep(5);
	$suma_cheque_E=0;
	$suma_cheque_I=0;
	$suma_valor_E=0;
	$suma_valor_I=0;
	$saldo_anteriorXX=0;
	$suma_depositos_I=0;
	$suma_tranferencia_I=0;
	
	$fecha_fin=$fecha_inicio;//no utilizo segunda fecha
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$mostrar_aviso_saldo_anterior=true;
			$mostrar_fecha_generacion=true;
			break;
		case"matricula":	
			$mostrar_aviso_saldo_anterior=true;
			$mostrar_fecha_generacion=false;
			break;
		case"inspeccion":
			$mostrar_fecha_generacion=true;
			$mostrar_aviso_saldo_anterior=false;
			break;	
		default:
			$mostrar_aviso_saldo_anterior=false;	
			$mostrar_fecha_generacion=false;	
			//echo"--->$mostrar_aviso_saldo_anterior";
	}
	
	$objResponse = new xajaxResponse();
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
	
		$tipo_documento="";
		$color_1="#F5A9A9";
		$img_ok='<img src="../../BAses/Images/ok.png" width="15" height="15" alt="ok" />';
		//-----------------------------------------------------------------//
		$total_cheques_hoy=0;
		$html_cheque='<table width="100%" align="center" border="0">
						<thead>
						<tr>
							<th colspan="7">Cheque con vencimiento el dia de Hoy </td>
						</tr>
						<tr>
							<td>N.</td>
							<td>Banco</td>
							<td>Numero</td>
							<td>Valor</td>
							<td>Emisor</td>
							<td>Emision</td>
							<td>Vencimiento</td>
						</tr>
						</thead>
						<tbody>';
		//cheque para hoy
		
		$cons_CH="SELECT * FROM registro_cheques WHERE sede='$sede' AND fecha_vencimiento='$fecha_inicio' AND movimiento='I' ORDER by id_alumno";
		if(DEBUG){ echo"cons_cheque: $cons_CH<br>";}
		$sqli_ch=$conexion_mysqli->query($cons_CH)or die($conexion_mysqli->error);
		$num_cheque_para_hoy=$sqli_ch->num_rows;
		if($num_cheque_para_hoy>0)
		{
			$aux=0;
			while($CH=$sqli_ch->fetch_assoc())
			{
				$aux++;
				$CH_valor=$CH["valor"];
				$CH_banco=$CH["banco"];
				$CH_numero=$CH["numero"];
				$CH_fecha_vencimiento=$CH["fecha_vencimiento"];
				$CH_fecha=$CH["fecha"];
				$CH_cod_user=$CH["cod_user"];
				$CH_id_alumno=$CH["id_alumno"];
				$total_cheques_hoy+=$CH_valor;
				
				
				$emite_cheque="A".$CH_id_alumno;
				$html_cheque.='<tr>
									<td>'.$aux.'</td>
									<td>'.$CH_banco.'</td>
									<td>'.$CH_numero.'</td>
									<td>$ '.number_format($CH_valor,0,",",".").'</td>
									<td><a href="#" title="Generado el:'.$CH_fecha.'">'.$emite_cheque.'</a></td>
									<td>'.$CH_fecha.'</td>
									<td>'.$CH_fecha_vencimiento.'</td>
								</tr>';
			}
		}
		else{ $html_cheque.='<tr><td colspan="4">Sin Cheque para Hoy</td></tr>';}
		$sqli_ch->free();
		$html_cheque.='</tbody></table>';
		
		//------------------------------------------------------------------//
		if($tipo_doc!="todos"){$tipo_documento="AND tipodoc='$tipo_doc'";}
		$consP="SELECT * FROM pagos WHERE fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' AND sede='$sede'AND NOT(por_concepto='saldo_caja') $tipo_documento AND movimiento='I' ORDER by movimiento, idpago";
		$sqlP=$conexion_mysqli->query($consP) or die($conexion_mysqli->error);
		$cuenta_pag=$sqlP->num_rows;
		$html_tabla='<table width="100%">
				<thead >
				<tr>
					<th colspan="10" bgcolor="'.$color_1.'">Ingresos del dia '.$fecha_inicio.' en '.$sede.'</th>
				</tr>
				<tr>
					<th bgcolor="'.$color_1.'">-</th>
					<th bgcolor="'.$color_1.'">Fecha</th>
					<th bgcolor="'.$color_1.'">Valor</th>
					<th bgcolor="'.$color_1.'">Tipo Doc.</th>
					<th bgcolor="'.$color_1.'">N. Doc.</th>
					<th bgcolor="'.$color_1.'">Glosa</th>
					<th bgcolor="'.$color_1.'">Tipo Mov.</th>
					<th bgcolor="'.$color_1.'">Forma Pag.</th>
					<th bgcolor="'.$color_1.'">ID User</th>
					<th bgcolor="'.$color_1.'">ID Receptor</th>
				</tr>
				</thead>
				<tbody>';
		if($cuenta_pag>0)
		{
			$RESUMEN=array();
			$suma_valor_I=0;
			$suma_valor_E=0;
			$suma_depositos_I=0;
			$i=0;
			$ultimo_id_multi_cheque=0;
			while($D=$sqlP->fetch_assoc())
			{
				$icono='';
				$id_pago=$D["idpago"];
				$id_boleta=$D["id_boleta"];
				$id_factura=$D["id_factura"];
				
				$tipo_receptor=$D["tipo_receptor"];
				$id_alumno=$D["id_alumno"];
				$id_empresa=$D["id_empresa"];
				$fecha_pag=$D["fechapago"];
				$fecha_generacion=$D["fecha_generacion"];
					$array_fecha_generacion=explode(" ",$fecha_generacion);
					$FG_D=fecha_format($array_fecha_generacion[0]);
					$FG_H=$array_fecha_generacion[1];
				
				$valor=$D["valor"];
				$tipo_doc=$D["tipodoc"];
				$glosa=$D["glosa"];
				$sede=$D["sede"];
				$movimiento=$D["movimiento"];
				$forma_pago=$D["forma_pago"];
				$fechaV_cheque=$D["fechaV_cheque"];
				$id_cheque=$D["id_cheque"];
				$id_multi_cheque=$D["id_multi_cheque"];
				$cod_user=$D["cod_user"];
				$por_concepto=$D["por_concepto"];
				$aux_num_documento=$D["aux_num_documento"];
				///////////////--------------------------------------------------////////////////
				//separo x item
				if(isset($RESUMEN[$por_concepto][$movimiento]))
				{$RESUMEN[$por_concepto][$movimiento]+=$valor;}
				else{$RESUMEN[$por_concepto][$movimiento]=$valor;}
				///////////////////
				
				list($tipo_documento, $numero_documento)=INFO_COMPROBANTE_FINANCIERO($id_pago);
				
				///////////sumo los valores de los cheque recibidos///////////////
				switch($forma_pago)
				{
					case"cheque":
					$forma_pago.="(".fecha_format($fechaV_cheque,"-").")";
					switch($movimiento)
					{
						case"I":
							$suma_cheque_I+=$valor;
							if($fechaV_cheque==$fecha_inicio)
							{ $icono=$img_ok;}
							break;
						case"E":
							$suma_cheque_E+=$valor;
							$icono=$img_ok;
						break;
					}
					break;
					case"multi_cheque":
						//------------------------------------------------------------------------------------//
						$cons_MCH="SELECT  * FROM registro_multi_cheque WHERE id='$id_multi_cheque' LIMIT 1";
						$sql_MCH=$conexion_mysqli->query($cons_MCH)or die($conexion_mysqli->error);
						$D_MCH=$sql_MCH->fetch_assoc();
							$MCH_cantidad_cheques=$D_MCH["cantidad_cheques"];
							$MCH_total_al_dia=$D_MCH["total_al_dia"];
							$MCH_fecha=$D_MCH["fecha"];
						$sql_MCH->free();
						//-------------------------------------------------------------------------------------//
						 
							$icono=$img_ok;
							$suma_cheque_I+=$valor;
							$forma_pago.="($MCH_cantidad_cheques cheques)";
						break;
					case"deposito":
						$suma_depositos_I+=$valor;
						$icono=$img_ok;
						break;
					case"tranferencia":
						$suma_transferencia_I+=$valor;
						$icono=$img_ok;
						break;	
					default:
						$icono=$img_ok;
						if($tipo_doc=="L")
						{$tipo_doc="Letra";}
						switch($movimiento)
						{
							case"I":
								$suma_valor_I+=$valor;
								break;
							case"E":
								$suma_valor_E+=$valor;
								break;
						}
						break;
				}
				
				$html_tabla.='<tr>
								<td align="center">'.$icono.'</td>
								<td align="center"><a href="#" title="'.$FG_D.' '.$FG_H.'">'.fecha_format($fecha_pag,"-").'</a></td>
								<td align="right">$'.number_format($valor,0,",",".").'</td>
								<td align="center">'.$tipo_documento.'</td>
								<td align="center">'.$numero_documento.'</td>
								<td>'.ucwords(strtolower($glosa)).'</td>
								<td align="center">'.$movimiento.'</td>
								<td align="center">'.$forma_pago.'</td>
								<td align="center">'.$cod_user.'</td>';
					switch($tipo_receptor)
					{
						case"alumno":
							$html_tabla.='<td align="center"><a href="pagos_alumno/pagos_alumno.php?id_alumno='.base64_encode($id_alumno).'&id_pago='.$id_pago.'" target="_blanck" title="Alumno">A'.$id_alumno.'</a></td>';
							break;
						case"empresa":
							$html_tabla.='<td align="center"><a href="pagos_empresa/pagos_empresa.php?id_empresa='.$id_empresa.'&id_pago='.$id_pago.'" target="_blanck" title="Empresa">E'.$id_empresa.'</a></td>';
							break;
					}
					$html_tabla.='</tr>';
					
			}
		}
		else
		{
			//no pagos en rango fecha
			$html_tabla.='<tr>
					<td colspan="10">Sin pagos Registrados en este Rango de Fechas, sede y Tipo de Documento</td>
					</tr>';
		}
		$html_tabla.='</tbody>
				<tfoot>
				<tr bgcolor="#EBE5D9">
				<td colspan="3">N. Total Movimientos:</td>
				<td colspan="2" align="right">'.$cuenta_pag.'</td>
				<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="9">&nbsp;</td>
				</tr>';
				
				/////////////////////////
				//$suma_valor_I+=$total_cheques_hoy;//sumo los cheque de hoy a los ingresos
				$suma_valor_E+=$suma_cheque_E;//sumo cheques al egreso
				/////////////////////////
		$html_tabla.='<tr>
				<td colspan="3">Total Efectivo</td>
				<td colspan="2" align="right">$'.number_format($suma_valor_I,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2">Total Cheques Recibidos:</td>
				<td colspan="2"  align="right">$'.number_format($suma_cheque_I,0,",",".").'</td>
				</tr>
				<tr>
				<td colspan="3">Total depositos</td>
				<td colspan="2" align="right">$'.number_format($suma_depositos_I,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2">Total Cheques Para HOY</td>
				<td colspan="2"  align="right">$'.number_format($total_cheques_hoy,0,",",".").'</td>
				</tr>
				<tr>
				<td colspan="3">Total transferencia</td>
				<td colspan="2" align="right">$'.number_format($suma_tranferencia_I,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2"  align="right">&nbsp;</td>
				</tr>';
				/////calculo ingresos -egresos/////////////////////
				$subtotal=number_format(($suma_valor_I -$suma_valor_E),0,",",".");
				///////////---------------------------///////////////
				//////////////////////////////////
				$TOTAL=(($suma_valor_I+$saldo_anteriorXX)-$suma_valor_E);
				$TOTAL_con_CHEQUE=number_format($TOTAL+$total_cheques_hoy,0,",",".");
				
				$TOTAL_NEW=($suma_valor_I + $suma_cheque_I);
				//////////////////////////////////
				$html_tabla.='<tr>
				<td colspan="6">&nbsp;</td>
				<td colspan="2"><strong>TOTAL DE HOY:</strong></td>
				<td colspan="2" align="right"><strong>$'.number_format($TOTAL_NEW,0,",",".").'</strong></td>
				</tr>
				<tr>
				<td colspan="9">&nbsp;</td>
				</tr>
				</tfoot>
				</table>';
				
		$html=$html_tabla;
		$html.='<div id="capa_actualiza"></div>';
		
		$html.='<a href="genera_excel_v2.php?sede='.base64_encode($sede).'&fecha_ini='.base64_encode($fecha_inicio).'" title="Exportar Ingresos a Excel" target="_blank"><img name="excel" src="../../BAses/Images/excel_icon.png" width="32" height="32" alt="Exportar a Excel" /></a> <tt>Fecha de Impresion: '.date("d-m-Y H:i:s").'</tt>';
		
		
		$objResponse->Assign("div_resultados","innerHTML",$html);
		$objResponse->Assign("cheque_detalle","innerHTML",$html_cheque);
		
		//$objResponse->Assign("div_total_2","innerHTML","<br><strong>TOTAL DEPOSITAR: $".$TOTAL_con_CHEQUE."</strong>");
	$sqlP->free();
	$conexion_mysqli->close();
	//////////////////
	$html_tabla_item='<table width="100%"">
  <caption></caption>
  <thead>
  <tr>
    <td ><div align="right"><strong>N&deg;</strong></div></td>
    <td ><div align="right"><strong>Concepto</strong></div></td>
    <td ><div align="right"><strong>Total ($) </strong></div></td>
  </tr>
  </thead>
  <tbody>';
	if(!empty($RESUMEN))
	{
		$contador=0;
		$TOTALES=array();
		foreach($RESUMEN as $concepto => $MOV)
		{
			$contador++;
			
			if(isset($MOV["I"])){$aux_ingreso=$MOV["I"];}
			else{$aux_ingreso=0;}

			if(empty($aux_ingreso)){ $aux_ingreso=0;}
			
			$aux_total=$aux_ingreso;
			
			if(isset($TOTALES["I"]))
			{$TOTALES["I"]+=$aux_ingreso;}
			else{$TOTALES["I"]=$aux_ingreso;}
			
			if(isset($TOTALES["T"]))
			{$TOTALES["T"]+=$aux_total;}
			else{$TOTALES["T"]=$aux_total;}
			
			$html_tabla_item.='<tr>
				<td><div align="right">'.$contador.'</div></td>
				<td><div align="right">'.$concepto.'</div></td>
				<td><div align="right">'.number_format($aux_ingreso,0,",",".").'</div></td>
				</tr>';
		}
		$html_tabla_item.='<tr>
				<td colspan="2"><strong>TOTALES</strong></td>
				<td><div align="right">'.number_format($TOTALES["I"],0,",",".").'</div></td>
				</tr>';
		}	
		else
		{
			$html_tabla_item.='<tr><td colspan="3" align="center"><em>Sin Registros</em></td></tr>';
		}
		$html_tabla_item.='</tbody></table>';
	//////////////////
	include("../../../funciones/VX.php");
	$evento="Revisa informe de Pagos V1 dia: $fecha_inicio";
	REGISTRA_EVENTO($evento);
	$objResponse->Assign("div_item","innerHTML",$html_tabla_item);
	$objResponse->call('xajax_BUSCA_EGRESOS',$fecha_inicio, $sede);
	
	return $objResponse;	
}
function BUSCA_EGRESOS($fecha, $sede)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$div="div_egresos";
	$color="#A9BCF5";
	$html_egresos='<br><table border="0" width="100%" align="center">
					<thead>
					<tr>
						<th bgcolor="'.$color.'" colspan="9">Egresos del dia '.$fecha.' en '.$sede.'</th>
					</tr>
					<tr>
						<td bgcolor="'.$color.'" align="center">N</td>
						<td bgcolor="'.$color.'" align="center">Fecha</td>
						<td bgcolor="'.$color.'" align="right">Valor</td>
						<td bgcolor="'.$color.'" align="center">Proveedor/Receptor</td>
						<td bgcolor="'.$color.'" align="center">Tipo Documento</td>
						<td bgcolor="'.$color.'" align="center">Numero</td>
						<td bgcolor="'.$color.'">Glosa</td>
						<td bgcolor="'.$color.'">Forma Pago</td>
						<td bgcolor="'.$color.'" align="center">Responsable</td>
					</tr>
					</thead>
					<tbody>';
	
	$objResponse = new xajaxResponse();
	
	$TOTAL_EGRESOS=0;
	//$objResponse->Alert("Buscando Egresos");
	$SUMA_EFECTIVO_E=0;
	$SUMA_CHEQUE_E=0;	
	$SUMA_DEPOSITO_E=0;
	$SUMA_TRANSFERENCIA_E=0;
	
	$cons_E="SELECT * FROM pagos WHERE sede='$sede' AND fechapago='$fecha' AND movimiento='E' ORDER by fechapago";	
	$sqli_E=$conexion_mysqli->query($cons_E)or die("Egresos ".$conexion_mysqli->error);
	$num_egresos=$sqli_E->num_rows;
	
	if($num_egresos>0)
	{
		$aux=0;
		while($E=$sqli_E->fetch_assoc())
		{
			$aux++;
			$E_id_pago=$E["idpago"];
			$E_valor=$E["valor"];
			$E_fechapago=$E["fechapago"];
			$E_forma_pago=$E["forma_pago"];
			$E_glosa=$E["glosa"];
			$E_fecha_generacion=$E["fecha_generacion"];
			$E_cod_user=$E["cod_user"];
			$E_rut_responsable=$E["rut_responsable_gasto"];
			$E_concepto=$E["por_concepto"];
			$E_id_proveedor=$E["id_proveedor"];
			$E_movimiento=$E["movimiento"];
			
			$E_tipo_receptos=$E["tipo_receptor"];
			$E_id_personal=$E["id_personal"];
			
			
			switch($E_tipo_receptos){
				case"personal":
					$proveedorReceptor=NOMBRE_PERSONAL($E_id_personal);
					break;
				case"proveedor":
					$proveedorReceptor=NOMBRE_PROVEEDOR($E_id_personal);
					break;	
			}
			
			switch($E_forma_pago)
			{
				case"efectivo":
					$SUMA_EFECTIVO_E+=$E_valor;
					break;
				case"cheque":
					$SUMA_CHEQUE_E+=$E_valor;
					break;
				case"deposito":
					$SUMA_DEPOSITO_E+=$E_valor;
					break;
				case"transferencia":
					$SUMA_TRANSFERENCIA_E+=$E_valor;
					break;	
			}
			
			$TOTAL_EGRESOS+=$E_valor;
			list($E_tipo_documento, $E_numero_documento)=INFO_COMPROBANTE_FINANCIERO($E_id_pago);
			
			//separo x item
			if(isset($RESUMEN[$E_concepto][$E_movimiento]))
			{$RESUMEN[$E_concepto][$E_movimiento]+=$E_valor;}
			else{$RESUMEN[$E_concepto][$E_movimiento]=$E_valor;}
			///////////////////
			
			$html_egresos.='<tr>
								<td align="center">'.$aux.'</td>
								<td align="center">'.$E_fechapago.'</td>
								<td align="right">$ '.number_format($E_valor,0,",",".").'</td>
								<td align="center">'.$proveedorReceptor.'</td>
								<td align="center">'.$E_tipo_documento.'</td>
								<td align="center">'.$E_numero_documento.'</td>
								<td>'.$E_glosa.'</td>
								<td>'.$E_forma_pago.'</td>
								<td align="center">'.$E_rut_responsable.'</td>
							</tr>';
		}
	}
	else
	{ $html_egresos.='<tr><td colspan="9">Sin Egresos</td></tr>';}
	
	
	$html_egresos.='<tr>
						<td colspan="2" bgcolor="#EBE5D9">N. Total Movimientos</td>
						<td bgcolor="#EBE5D9">'.$num_egresos.'</td>
						<td colspan="6" bgcolor="#EBE5D9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">TOTAL EFECTIVO</td>
						<td align="right">$ '.number_format($SUMA_EFECTIVO_E,0,",",".").'</td>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">TOTAL CHEQUE</td>
						<td align="right">$ '.number_format($SUMA_CHEQUE_E,0,",",".").'</td>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">TOTAL Deposito</td>
						<td align="right">$ '.number_format($SUMA_DEPOSITO_E,0,",",".").'</td>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">TOTAL tranferencias</td>
						<td align="right">$ '.number_format($SUMA_TRANSFERENCIA_E,0,",",".").'</td>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><strong>TOTAL</strong></td>
						<td align="right"><strong>$ '.number_format($TOTAL_EGRESOS,0,",",".").'</strong></td>
						<td colspan="6">&nbsp;</td>
					</tr>
					</tbody></table>';
	$sqli_E->free();
		
	
	
	$icono_exportar='<a href="genera_excel_egreso_v2.php?sede='.base64_encode($sede).'&fecha_ini='.base64_encode($fecha).'" title="Exportar Egresos a Excel" target="_blank"><img name="excel" src="../../BAses/Images/excel_icon.png" width="32" height="32" alt="Exportar Egresos a Excel" /></a>';
	
	$html_egresos.=$icono_exportar;
	//---------------------------------------------------------------------//
		$html_tabla_item='<table width="100%"">
  <caption></caption>
  <thead>
  <tr>
    <td ><div align="right"><strong>N&deg;</strong></div></td>
    <td ><div align="right"><strong>Concepto</strong></div></td>
    <td><div align="right"><strong>Total</strong> <strong>($)</strong></div></td>
  </tr>
  </thead>
  <tbody>';
  //var_dump($RESUMEN);
	if(!empty($RESUMEN))
	{
		$contador=0;
		$TOTALES=array();
		foreach($RESUMEN as $concepto => $MOV)
		{
			$contador++;
			if(isset($MOV["E"])){$aux_egreso=$MOV["E"];}
			else{ $aux_egreso=0;}

			$aux_total=$aux_egreso;
			
			if(isset($TOTALES["E"]))
			{$TOTALES["E"]+=$aux_egreso;}
			else{$TOTALES["E"]=$aux_egreso;}	
			
			$html_tabla_item.='<tr>
				<td><div align="right">'.$contador.'</div></td>
				<td><div align="right">'.$concepto.'</div></td>
				<td><div align="right">'.number_format($aux_egreso,0,",",".").'</div></td>
				</tr>';
		}
		$html_tabla_item.='<tr>
				<td colspan="2"><strong>TOTALES</strong></td>
				<td><div align="right">'.number_format($TOTALES["E"],0,",",".").'</div></td>
				</tr>';
		}	
		else
		{$html_tabla_item.='<tr><td colspan="5" align="center"><em>Sin Registros</em></td></tr>';}
		$html_tabla_item.='</tbody></table>';
	//----------------------------------------------------------------------//
	
	$objResponse->Assign($div,"innerHTML",$html_egresos);
	$objResponse->Assign("div_item_egreso","innerHTML",$html_tabla_item);
	//$objResponse->Assign("div_icono","innerHTML",$icono_exportar);
	
		
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();		
?>