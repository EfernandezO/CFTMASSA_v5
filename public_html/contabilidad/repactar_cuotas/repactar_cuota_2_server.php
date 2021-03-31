<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Repactar_cuotas_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//--------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso_3b_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"RECALCULAR");
$xajax->register(XAJAX_FUNCTION,"ARANCEL_X_SEMESTRE");
////////////////////////////////////////////

function ACTUALIZA_CANTIDAD($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$div="DEBUG_1";
	$linea_credito_cantidad=$FORMULARIO["linea_credito_cantidad"];
	$linea_credito_dia_vencimiento=$FORMULARIO["linea_credito_dia_vencimiento"];
	$linea_credito_mes_ini=$FORMULARIO["linea_credito_mes_ini"];
	$linea_credito_year=$FORMULARIO["linea_credito_year"];
	$linea_credito_cantidad_cuotas=$FORMULARIO["linea_credito_cantidad_cuotas"];
	
	$sede_user_activo=$_SESSION["USUARIO"]["sede"];
	$meses_avance=$FORMULARIO["meses_avance"];//agregado
	  
	/////////////-----------------------////////////
	//Guardo en session
	if(is_numeric($linea_credito_cantidad))
	{

				$div_r="resultado_linea_credito";
				$dia_vence=$linea_credito_dia_vencimiento;
				$mes=$linea_credito_mes_ini;
				$año=$linea_credito_year;
				$html_cuotas='<table border=0 width=100%>
				<thead>
				<tr>
					<th colspan="4">Cuotas Nuevas</th>
				</tr>
				<tr>
					<td><strong>N</strong></td>
					<td><strong>Cantidad</strong></td>
					<td><strong>Vencimiento</strong></td>
				</tr>
				</thead><tbody>';
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
				$html_cuotas.="</tbody></table>";
				if($linea_credito_cantidad<=0)
				{
					$html_cuotas="";
				}
				$objResponse->Assign("resultado_linea_credito","innerHTML",$html_cuotas);
				
		
			$html_boton='<strong>Detalles de Repactacion</strong><br>Numero Cuotas: '.$linea_credito_cantidad_cuotas.'<br>Valor cuotas: $'.number_format($valor_cuota,0,",",".").'<br>Dia de Vencimiento:'.$linea_credito_dia_vencimiento.'<br><br>Si Continua con la Repactacion se eliminaran las Cuotas de este contrato y se crearan nuevas cuotas como se detallaron...<br><br><br>';
			$html_boton.='<a href="#" class="button_R" onclick="Verificar();">Generar la Repartacion...?</a>';
			$objResponse->Assign($div,"innerHTML",$html_boton);
		
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
	
	$TOTAL_DEUDA_CUOTAS=$FORM["deuda_cuotas"];
	$TOTAL_INTERESES=$FORM["intereses"];
	$TOTAL_GASTOS_COBRANZA=$FORM["gastos_cobranza"];
	$TOTAL_SALDAR=$FORM["total_saldar"];
	$TOTAL_YA_CANCELADO=$FORM["total_ya_cancelado"];
	//////////////////////////////////
	
	
	/////////////////////calculo
	if((is_numeric($TOTAL_DEUDA_CUOTAS))and(is_numeric($TOTAL_INTERESES)and(is_numeric($TOTAL_GASTOS_COBRANZA)and(is_numeric($TOTAL_SALDAR)and(is_numeric($TOTAL_YA_CANCELADO))))))
	{ $avanzar=true;}
	else
	{ $avanzar=false;}
	
	
	if($avanzar)
	{
		$DEUDA_ACTUAL_ARANCEL=($TOTAL_DEUDA_CUOTAS-$TOTAL_YA_CANCELADO);
		$TOTAL_SALDAR=($DEUDA_ACTUAL_ARANCEL+$TOTAL_INTERESES+$TOTAL_GASTOS_COBRANZA);
		$html="TOTAL DEUDA -> $TOTAL_SALDAR<br>";
	
		$objResponse->Assign("div_subtotal","innerHTML",$DEUDA_ACTUAL_ARANCEL);
		$objResponse->Assign($div,"innerHTML",$html);
		$objResponse->Assign("total_saldar","value",$TOTAL_SALDAR);
		$objResponse->Assign("linea_credito_cantidad","value",$TOTAL_SALDAR);
		
		$objResponse->script("FORZAR_ACTUALIZAR();");
	}//fin avanzar
	else
	{
		$objResponse->alert("Datos incorrecto o :-(...");
	}
	return $objResponse;
}

////////////////
$xajax->processRequest();
?>