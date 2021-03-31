<?php
session_start();
//////////////////////XAJAX/////////////////
@require_once ("../../../Edicion_carreras/libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("recalculo_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"RECALCULAR");
////////////////////////////////////////////

function ACTUALIZA_CANTIDAD($FORMULARIO, $origen)
{
	$objResponse = new xajaxResponse();
	
	$sede_user_activo=$_SESSION["USUARIO"]["sede"];
	///////////todos los datos del FRM//////////////
	//linea credito
	
	  $linea_credito_cantidad=$FORMULARIO["linea_credito_cantidad"];
	  $total_a_pagar_arancel=$linea_credito_cantidad;
	  
	  $linea_credito_cantidad_cuotas=$FORMULARIO["linea_credito_cantidad_cuotas"];
	  $linea_credito_mes_ini=$FORMULARIO["linea_credito_mes_ini"];
	  $linea_credito_dia_vencimiento=$FORMULARIO["linea_credito_dia_vencimiento"];
	  $linea_credito_year=$FORMULARIO["linea_credito_year"];
	  if(empty($linea_credito_year))
	  {$linea_credito_year=date("Y");}
	  $meses_avance=$FORMULARIO["meses_avance"];//agregado
	  
	$total_a_pagar_arancel=0;
	/////////////-----------------------////////////
	//Guardo en session
	if(is_numeric($linea_credito_cantidad))
	{
		
		//CALCULANDO RESUMEN
		$total_acordado_now=($linea_credito_cantidad);
		$total_sin_acuerdo=($total_a_pagar_arancel-$total_acordado_now);
		switch($origen)
		{
			case"LINEA_CREDITO":
				$div_r="resultado_linea_credito";
				$dia_vence=$linea_credito_dia_vencimiento;
				$mes=$linea_credito_mes_ini;
				$año=$linea_credito_year;
				$html_cuotas="<table border=0 width=100%>
				<tr align=center>
				<td><strong>N°</strong></td>
				<td><strong>Cantidad</strong></td>
				<td><strong>Vencimiento</strong></td>
				</tr>";
				$valor_cuota=round($linea_credito_cantidad/$linea_credito_cantidad_cuotas);
				
				for($c=1;$c<=$linea_credito_cantidad_cuotas;$c++)
				{
					if(($dia_vence>28)and($mes==2))
					{
						$vencimiento="28/02/$año";
					}
					else
					{
						if($mes<10)
						{$mes_label="0".$mes;}
						else{$mes_label=$mes;}
						if($dia_vence<10)
						{$dia_vence_label="0".$dia_vence;}
						else{$dia_vence_label=$dia_vence;}
						$vencimiento="$dia_vence_label/$mes_label/$año";	
					}	
					////avance y condiciones para fechas
					$mes+=$meses_avance;
					if($mes>12)
					{
						$mes-=12;//modificado
						$año++;
					}
					//////////////////////////////////
					$html_cuotas.='<tr align=center>
									<td><em>'.$c.'/'.$linea_credito_cantidad_cuotas.'</em></td>
									<td><em>$'.number_format($valor_cuota,0,",",".").'</em></td>
									<td><em>'.$vencimiento.'</em></td>
									</tr>';
				}
				$html_cuotas.="</table>";
				if($linea_credito_cantidad<=0)
				{
					$html_cuotas="";
				}
				$objResponse->Assign("resultado_linea_credito","innerHTML",$html_cuotas);
				break;
		}
		
		
			$html_boton='<a href="#" onclick="Verificar();" class="button_G"/>GRABAR CONTRATO</a>';
			$objResponse->Assign("botonera","innerHTML",$html_boton);
		
	}
	else
	{
		$objResponse->alert("Dato incorrecto o Cantidad mayor a Total...");
	}
	return $objResponse;
}
function RECALCULAR($FORM)
{
	$objResponse = new xajaxResponse();
	$div="DEBUG_1";
	$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$id_contrato=$FORM["id_contratoZ"];
	$max_num_cuotas=$FORM["max_numero_cuotas"];
	$linea_credito_meses_avance=$FORM["linea_credito_meses_avance"];
	$max_avance_mes=$FORM["max_avance_mes"];
	$max_dia_mes=$FORM["max_dia_mes"];
	//////////////////////////////////
	
	$arancel_anual=$FORM["arancel"];
	$excedente_anterior=$FORM["excedente_anterior"];
	$total_cancelado=$FORM["total_cancelado"];
	
	$total_deuda=$arancel_anual-($excedente_anterior+$total_cancelado);
	$aporte_beca=$FORM["aporte_beca"];//beca_nuevo milenio
	$aporte_beca_excelencia=$FORM["beca_excelencia"];
	$cantidad_desc=$FORM["cantidad_desc"];
	$saldo_a_favor_new=($total_cancelado+$excedente_anterior);
	
	$porcentaje_beca=$FORM["porcentaje_beca"];
	
	$semestres_restantes_BNM=$FORM["semestres_restantes_BNM"];
	
	/////////////////////calculo descuento
	if((is_numeric($aporte_beca))and(is_numeric($porcentaje_beca)and(is_numeric($cantidad_desc)and(is_numeric($aporte_beca_excelencia)))))
	{ $avanzar=true;}
	else
	{ $avanzar=false;}
	
	
	if($avanzar)
	{
		
		if(($semestres_restantes_BNM<=0)and($aporte_beca>0))
		{
			$msj_SRBNM="No quedan semestres para Asignar BNM, supero duracion de carrera";
			$aporte_beca=0;
			$objResponse->Assign("aporte_beca","value",$aporte_beca);
			$objResponse->alert("$msj_SRBNM :-(...");
			
			
		}
		
		$aux_total=(($total_deuda-$aporte_beca)-$aporte_beca_excelencia);//resto cantidad
		$aux_total-=$cantidad_desc;
		
		if($porcentaje_beca>0)
		{
			$descuentoXbeca=(($porcentaje_beca*$arancel_anual)/100);
		}
		else
		{
			$descuentoXbeca=0;
		}
		///////////////////////----/////////////////////////////
		//echo"----> $cantidad_beca<br>";
		$total=($aux_total-$descuentoXbeca);
		if($total>0)
		{
			$hay_excedente=false;
			$excedente_valor=0;
		}
		else
		{
			$hay_excedente=true;
			$excedente_valor=abs($total);
		}
		
		$html="TOTAL DEUDA= $total_deuda<br>";
		$html.="TOTAL = $total<br>";
		$html.="% Desc. (valorizado)= $descuentoXbeca";
	
		$objResponse->Assign($div,"innerHTML",$html);
		///asignando total//////////////
		$objResponse->Assign("total_saldar","value",$total);
		
		
		//segun total generar cuotas o excedente
		$CUADRO_EXCEDENTE='<form action="genera_excedente.php" method="post" name="frm1" id="frm1">
	<table width="100%" border="0">
	  <tr>
		<td colspan="2" bgcolor="#e5e5e5"><span class="Estilo2">&gt;Informacion<strong>
		  <input name="id_contratoX2" type="hidden" id="id_contratoX2" value="'.$id_contrato.'"/>
		  <input name="porcentaje_beca_old2" type="hidden" id="porcentaje_beca_old2" value="'.$porcentaje_beca.'" />
		  <input name="aporte_beca2" type="hidden" id="aporte_beca2" value="'.$aporte_beca.'"/>
		  <input name="arancel_anual2" type="hidden" id="arancel_anual2" value="'.$arancel_anual.'" />
		  <input name="saldo_a_favor2" type="hidden" id="saldo_a_favor2" value="'.$saldo_a_favor_new.'" />
		  <input name="validador2" type="hidden" id="validador2" value="'.md5("reasignacion_e".date("Y-m-d")).'" />
		  <input type="hidden" name="comentario_beca_Y" id="comentario_beca_Y" />
		  <input type="hidden" name="cantidad_desc2" id="cantidad_desc2" value="'.$cantidad_desc.'" />
		  <input name="aporte_beca_excelencia2" type="hidden" id="aporte_beca_excelencia2" value="'.$aporte_beca_excelencia.'" />
		</strong></span></td>
		</tr>
	  <tr>
		<td bgcolor="#f5f5f5">Excedente A Favor Alumno</td>
		<td bgcolor="#f5f5f5"><input name="excedente_valor" type="text" id="excedente_valor" value="'.$excedente_valor.'" /></td>
	  </tr>
	  <tr>
		<td colspan="2" bgcolor="#f5f5f5"><div align="right">
		  <input type="button" name="button" id="button" value="Continuar"  onclick="VERIFICAR2();"/>
		</div></td>
		</tr>
	</table>
	</form>';
	
		$CUADRO_CUOTAS='<form action="genera_cuotas.php" method="post" name="frm" id="frm">
	  <div id="TabbedPanels1" class="TabbedPanels">
		<ul class="TabbedPanelsTabGroup">
		  <li class="TabbedPanelsTab" tabindex="0">Linea Credito</li>
		  </ul>
		<div class="TabbedPanelsContentGroup">
		  <div class="TabbedPanelsContent">
			<table width="486" border="0">
			  <tr>
				<td colspan="4" bgcolor="#e5e5e5"><input name="validador" type="hidden" id="validador" value="'. md5("reasignacion_c".date("Y-m-d")).'" />
				  <strong>>Linea Credito
				  <input name="id_contratoX" type="hidden" id="id_contratoX" value="'.$id_contrato.'" />
				  <input name="porcentaje_beca_old" type="hidden" id="porcentaje_beca_old" value="'.$porcentaje_beca.'" />
				  <input name="aporte_beca" type="hidden" id="cantidad_beca" value="'.$aporte_beca.'" />
				  <input name="arancel_anual" type="hidden" id="arancel_anual" value="'.$arancel_anual.'" />
				  <input name="saldo_a_favor" type="hidden" id="saldo_a_favor" value="'.$saldo_a_favor_new.'" />
				  <input type="hidden" name="comentario_beca_Y" id="comentario_beca_X" value=""/>
				  <input type="hidden" name="cantidad_desc" id="cantidad_desc" value="'.$cantidad_desc.'" />
				  <input name="aporte_beca_excelencia" type="hidden" id="aporte_beca_excelencia" value="'.$aporte_beca_excelencia.'" />
				  </strong></td>
			  </tr>
			  <tr>
				<td width="136">Cantidad</td>
				<td colspan="3"><input type="text" name="linea_credito_cantidad" id="linea_credito_cantidad"  value="'. $total.'" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;" readonly="readonly"/></td>
			  </tr>
			  <tr>
				<td>Numero de Cuotas</td>
				<td colspan="3"><select name="linea_credito_cantidad_cuotas" id="linea_credito_cantidad_cuotas"  onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">';
		   
				for($c=1;$c<=$max_num_cuotas;$c++)
				{
					$CUADRO_CUOTAS.='<option value="'.$c.'">'.$c.'</option>';	
				}
				
				$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Mes Inicio</td>
				<td width="117"><select name="linea_credito_mes_ini" id="linea_credito_mes_ini" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">';
				foreach($array_meses as $n => $valor)
				{
					if($n+1==date("m"))
					{
						$CUADRO_CUOTAS.='<option value="'.($n + 1).'" selected="selected">'.$valor.'</option>';
					}
					else
					{
						$CUADRO_CUOTAS.='<option value="'.($n + 1).'">'.$valor.'</option>';
					}	
				}
						  $CUADRO_CUOTAS.='</select></td>
				<td width="118">Meses Avance</td>
				<td width="97"><select name="meses_avance" id="meses_avance" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">';
				  for($ma=1;$ma<=$max_avance_mes;$ma++)
				  {
					if($linea_credito_meses_avance==$ma)
					{
						$CUADRO_CUOTAS.='<option value="'.$ma.'" selected="selected">'.$ma.'</option>';
					}
					else
					{
						$CUADRO_CUOTAS.='<option value="'.$ma.'">'.$ma.'</option>';
					}
				  }
				$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Dia Vencimiento</td>
				<td colspan="3"><select name="linea_credito_dia_vencimiento" id="linea_credito_dia_vencimiento" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">';
				  $array_dias_disponibles=array(5,10,15,20,25,30);
				  
		  foreach($array_dias_disponibles as $n => $valor)
		  {
			$CUADRO_CUOTAS.='<option value="'.$valor.'">'.$valor.'</option>';
		  }
				$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Año</td>
				<td colspan="3">
				<select name="linea_credito_year" id="linea_credito_year" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">';
					$año_actual=date("Y");
					$año_ini=$año_actual-10;
					$año_fin=$año_actual+1;
					for($a=$año_ini;$a<=$año_fin;$a++)
					{
							if($a==$año_actual)
							{
								$CUADRO_CUOTAS.='<option value="'.$a.'" selected="selected">'.$a.'</option>';
							}
							else
							{
								$CUADRO_CUOTAS.='<option value="'.$a.'" >'.$a.'</option>';
							}	
					}
		$CUADRO_CUOTAS.='
				</select>            </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
			  </tr>
			</table>
			<div id="resultado_linea_credito">
			  <div align="center"><a href="#" onclick="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues(\'frm\'), \'LINEA_CREDITO\');return false;">Actualizar</a></div>
			</div>
		  </div>
		  <div class="TabbedPanelsContent"></div>
		</div>
	  </div>
	  <div id="botonera">
	  </div>
	  
	  <div id="fin">  
		<div align="center">
		</div>
	  </div>
	</form>';
		
		if($hay_excedente)
		{ 
			if($aporte_beca>0){ $aux_comentario="Beca Nuevo Milenio ($".number_format($aporte_beca,0,",",".").")";}
			if($aporte_beca_excelencia>0)///comentario por beca excelencia
			{ $aux_comentario.=" Beca Excelencia Academica ($".number_format($aporte_beca_excelencia,0,",",".").")";}
			$aux_comentario.="  *Excedente Proximo Contrato $".number_format($excedente_valor,0,",",".")."*";
			$objResponse->Assign("apDiv3","innerHTML",$CUADRO_EXCEDENTE);
		}
		else
		{ 
			if($aporte_beca>0){ $aux_comentario="Beca Nuevo Milenio ($".number_format($aporte_beca,0,",",".").")";}
			if($aporte_beca_excelencia>0)///comentario por beca excelencia
			{ $aux_comentario.=" Beca Excelencia Academica ($".number_format($aporte_beca_excelencia,0,",",".").")";}
			$objResponse->Assign("apDiv3","innerHTML",$CUADRO_CUOTAS);
		}
		$objResponse->Assign("comentario_beca_main","value",$aux_comentario);//escribe en el cuadro de comentario
	}//fin avanzar
	else
	{
		$objResponse->alert("Dato incorrecto o :-(...");
	}
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>