<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_caja_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe_caja_egreso.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if(DEBUG){ var_dump($_GET);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
	////////////////PARAMETROS//////////////////////////////////
	$mostrar_aviso_saldo_anterior=false;	
	$mostrar_fecha_generacion=false;	
	$buscar_saldo_anterior=false;
	$actualiza_saldo=false;
	$buscar_cheques_depositados=true;
	$borde=1;
	$img_ok='';
	///////////////////////////////////////////////////////////////
		$sedex=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
		$fecha_iniciox=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["fecha_ini"]));
		//-------------------------------------------/
		//---------------------------------------//
		include("../../../funciones/VX.php");
		$evento="exporta a excel informe pago (EGRESOS) del dia $fecha_iniciox";
		REGISTRA_EVENTO($evento);
		//-----------------------------------------//
		$suma_valor_E=0;
		$suma_cheque_E=0;
		$TOTAL=0;
		//--------------------------------------------//
		$consP="SELECT * FROM pagos WHERE fechapago='$fecha_iniciox' AND sede='$sedex' AND movimiento='E' ORDER by fechapago, idpago";
		$sqlP=$conexion_mysqli->query($consP)or die();
		$cuenta_pag=$sqlP->num_rows;
		$html_tabla='<table border="'.$borde.'" width="100%">
				<thead>
				<tr bgcolor="#EBE5D9">
					<th colspan="10">Egresos Realizados el '.fecha_format($fecha_iniciox).' - Sede: '.$sedex.'</th>
				</tr>
				<tr>
				<th scope="col">-</th>
				<th scope="col">Cod.</th>
				<th scope="col">Fecha</th>
				<th scope="col">Valor</th>
				<th scope="col">Tipo Doc.</th>
				<th scope="col">N° Doc.</th>
				<th scope="col">Glosa</th>
				<th scope="col">Tipo Mov.</th>
				<th scope="col">Forma Pag.</th>
				<th scope="col">ID User</th>
				</tr>
				</thead>
				<tbody bgcolor="#F7F4EE">';
		if($cuenta_pag>0)
		{
			$RESUMEN=array();
			$suma_valor_I=0;
			$suma_valor_E=0;
			$i=0;
			$ultimo_id_multi_cheque=0;
			while($D=$sqlP->fetch_assoc())
			{
				$icono='';
				$id_pago=$D["idpago"];
				$id_boleta=$D["id_boleta"];
				$id_alumno=$D["id_alumno"];
				$fecha_pag=$D["fechapago"];
				$fecha_generacion=$D["fecha_generacion"];
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
				if(isset($RESUMEN[$por_concepto][$movimiento])){$RESUMEN[$por_concepto][$movimiento]+=$valor;}
				else{$RESUMEN[$por_concepto][$movimiento]=$valor;}
				///////////////////
				
				$TOTAL+=$valor;
			switch($por_concepto)
				{
					case"otro_ingreso":
						$filtrar=false;
						break;
					case"arancel":
						$filtrar=true;
						break;
					case"matricula":
						$filtrar=true;
						break;	
					case"otro_egreso":	
						$filtrar=false;
						break;
					case"otro_ingreso_2":	
						$filtrar=true;
						break;
					default:
						$filtrar=false;	
				}
				//echo"---------> $id_boleta <br>";
			//	echo"|$por_concepto| <br>";
					list($tipo_documento, $numero_documento)=INFO_COMPROBANTE_FINANCIERO($id_pago);
				///////////sumo los valores de los cheque recibidos///////////////
				if($forma_pago=="cheque")
				{
					$forma_pago.="(".fecha_format($fechaV_cheque,"-").")";
					switch($movimiento)
					{
						case"I":
							$suma_cheque_I+=$valor;
							if($fechaV_cheque==$fecha_iniciox)
							{ $icono=$img_ok;}
							break;
						case"E":
							$suma_cheque_E+=$valor;
							$icono=$img_ok;
							break;
					}
					
				
				}/////////////////////////////////////////////////////////////
				elseif($forma_pago=="multi_cheque")
				{
					$cons_MCH="SELECT  * FROM registro_multi_cheque WHERE id='$id_multi_cheque' LIMIT 1";
					$sql_MCH=$conexion_mysqli->query($cons_MCH)or die("multi_cheque".$conexion_mysqli->error());
					$D_MCH=$sql_MCH->fetch_assoc();
						$MCH_cantidad_cheques=$D_MCH["cantidad_cheques"];
						$MCH_total_al_dia=$D_MCH["total_al_dia"];
						$MCH_fecha=$D_MCH["fecha"];
					$sql_MCH->free();	
					//solo cargo el primero de los multi cheques
					if($id_multi_cheque==$ultimo_id_multi_cheque)
					{$suma_cheque_I+=$valor;}
					else
					{
						if($MCH_fecha==$fecha_iniciox)
						{ 
							$icono=$img_ok;
							$suma_cheque_I+=$valor;
							$forma_pago.="(".number_format($MCH_total_al_dia,0,",",".").")";
						}

					}
					$ultimo_id_multi_cheque=$id_multi_cheque;	
				}
				else
				{
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
				}
				$html_tabla.='<tr>
					
					<td align="center">'.$icono.'</td>
					<td align="center">'.$id_pago.'</td>
					<td align="center"><a href="#" title="FG:'.$fecha_generacion.'">'.fecha_format($fecha_pag,"-").'</a></td>
					<td align="right">$'.number_format($valor,0,",",".").'</td>
					<td align="center">'.$tipo_doc.'</td>
					<td align="center">'.$aux_numero_documento.'</td>
					<td>'.$glosa.'</td>
					<td align="center">'.$movimiento.'</td>
					<td align="center">'.$forma_pago.'</td>
					<td align="center">'.$cod_user.'</td>
					</tr>';
					
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
				<td colspan="3">Nº Total Movimientos:</td>
				<td colspan="2" align="right">'.$cuenta_pag.'</td>
				<td colspan="5">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="10">&nbsp;</td>
				</tr>';
				
				/////////////////////////
				//$suma_valor_I+=$total_cheques_hoy;//sumo los cheque de hoy a los ingresos
				$suma_valor_E+=$suma_cheque_E;//sumo cheques al egreso
				/////////////////////////
		$html_tabla.='
				<tr>
				<td colspan="3"></td>
				<td colspan="2" ></td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2"></td>
				<td colspan="2"  align="right"></td>
				</tr>
				<tr>
				<td colspan="3">Total Egresos:</td>
				<td colspan="2" align="right">$'.number_format($suma_valor_E,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2"></td>
				<td colspan="2"  align="right"></td>
				</tr>';
				/////calculo ingresos -egresos/////////////////////
				
				///////////---------------------------///////////////
				//////////////////////////////////
				//////////////////////////////////
				$html_tabla.='<tr bgcolor="#EBE5D9">
				<td colspan="3"><strong>TOTAL :</strong></td>
				<td colspan="2" align="right"><strong>$'.number_format($TOTAL,0,",",".").'</strong></td>
				<td colspan="5">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="10">&nbsp;</td>
				</tr>
				</tfoot>
				</table>';
				
		$html=$html_tabla;
		echo $html;
	$sqlP->free();	
	@mysql_close($conexion);	
	$conexion_mysqli->close();
	$html_tabla_item='</br><table width="100%" border="'.$borde.'">
  <caption></caption>
  <thead>
  <tr>
  	<td colspan="3" bgcolor="#e5e5e5" align="center"><strong>Resumen por Concepto</strong></td>
  </tr>
  <tr>
    <td ><div align="right"><strong>N&deg;</strong></div></td>
    <td ><div align="right"><strong>Concepto</strong></div></td>
    <td><div align="right"><strong>Total</strong> <strong>($)</strong></div></td>
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
			$aux_ingreso=$MOV["I"];
			$aux_egreso=$MOV["E"];
			if(empty($aux_ingreso)){ $aux_ingreso=0;}
			if(empty($aux_engreso)){ $aux_engreso=0;}
			$aux_total=($aux_ingreso-$aux_egreso);
			
			$TOTALES["I"]+=$aux_ingreso;
			$TOTALES["E"]+=$aux_egreso;
			$TOTALES["T"]+=$aux_total;
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
		{$html_tabla_item.='<tr><td colspan="3" align="center"><em>Sin Registros</em></td></tr>';}
		$html_tabla_item.='</tbody></table>';
	//////////////////
	echo $html_tabla_item;
}
?>