<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso_3c_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"VALOR_RESTANTE");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
////////////////////////////////////////////
function VERIFICAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	
	//linea credito
	$linea_credito_cantidad=$FORMULARIO["linea_credito_cantidad"];
	//contado
	  $contado_cantidad=$FORMULARIO["contado_cantidad"];
	  $contado_descuento=$FORMULARIO["contado_descuento"];
	
	  $cheque_cantidad=$FORMULARIO["cheque_cantidad"];
	  $cheque_banco=$FORMULARIO["cheque_banco"];
	  $cheque_fecha_vence=$FORMULARIO["cheque_fecha_vence"];
	  $cheque_numero=$FORMULARIO["cheque_numero"];
	  @$cheque_matricula_arancel=$FORMULARIO["cheque_matricula_arancel"];
	  if(empty($cheque_matricula_arancel))
	  {$cheque_matricula_arancel="OFF";}
	  
	  //------------------------------------------//
	  //cheque
	  $verificar_cheque=false;
	  if($cheque_cantidad!="0")
	  {$verificar_cheque=true;}
	  //contado
	  $verificar_contado=false;
	  if($contado_cantidad!="0"){ $verificar_contado=true;}
	   //clinea credito
	  $verificar_linea_credito=false;
	  if($linea_credito_cantidad!="0"){ $verificar_linea_credito=true;}
	  //----------------------------------------//
	  $cheque_ok=true;
	  if($verificar_cheque)
	  {
		  if(!is_numeric($cheque_cantidad)){$objResponse->Alert("Ingrese una Cantidad Valida en el Pago con Cheque"); $cheque_ok=false;}
		  if($cheque_matricula_arancel=="OFF")
		  {
		  	if(empty($cheque_fecha_vence)){ $objResponse->Alert("Ingrese Fecha de Vencimiento de Cheque"); $cheque_ok=false;}
		  	if(empty($cheque_numero)){ $objResponse->Alert("Ingrese Numero de Cheque"); $cheque_ok=false;}
		  }
	  }
	  //----------------------------------------//
	  $contado_ok=true;
	  if($verificar_contado)
	  {
		  if(!is_numeric($contado_cantidad)){$objResponse->Alert("Ingrese una Cantidad Valida en el Pago al Contado"); $contado_ok=false;}
		  if(!is_numeric($contado_descuento)){$objResponse->Alert("Ingrese una Cantidad Valida en el descuento de pago al Contado"); $contado_ok=false;}
	  }
	  $linea_credito_ok=true;
	  if($verificar_linea_credito)
	  {
		   if(!is_numeric($linea_credito_cantidad)){$objResponse->Alert("Ingrese una Cantidad Valida en la Linea de Credito"); $linea_credito_ok=false;}
	  }
	  
	  if($contado_ok and $cheque_ok and $linea_credito_ok)
	  {$objResponse->Script("document.getElementById('frm').submit();");}
	  
	return $objResponse;
}
function ACTUALIZA_CANTIDAD($FORMULARIO, $origen, $TOTAL_INICIAL=0)
{
	$objResponse = new xajaxResponse();
	
	$sede_user_activo=$_SESSION["USUARIO"]["sede"];
	if($TOTAL_INICIAL>0){$hay_excedente=false;}
	else{ $hay_excedente=true;}
	///////////todos los datos del FRM//////////////
	//linea credito
	$aux_total=$FORMULARIO["linea_credito_cantidad"];
	//$objResponse->alert("Total $aux_total...");
	if(!$hay_excedente)
	{
		//$objResponse->alert("--->SIN Excedentes...$TOTAL_INICIAL");
		//excedente
	  $_SESSION["FINANZAS"]["excedente_proximo_contrato"]=0;
	  $linea_credito_cantidad=$aux_total;
	  $linea_credito_cantidad_cuotas=$FORMULARIO["linea_credito_cantidad_cuotas"];
	  $linea_credito_mes_ini=$FORMULARIO["linea_credito_mes_ini"];
	  $linea_credito_dia_vencimiento=$FORMULARIO["linea_credito_dia_vencimiento"];
	  $linea_credito_year=$FORMULARIO["linea_credito_year"];
	  if(empty($linea_credito_year))
	  {$linea_credito_year=date("Y");}
	  $meses_avance=$FORMULARIO["meses_avance"];//agregado
	  
	  //contado
	  $contado_cantidad=$FORMULARIO["contado_cantidad"];
	  $contado_descuento=$FORMULARIO["contado_descuento"];
	  //cheque
	  $cheque_cantidad=$FORMULARIO["cheque_cantidad"];
	  $cheque_banco=$FORMULARIO["cheque_banco"];
	  $cheque_fecha_vence=$FORMULARIO["cheque_fecha_vence"];
	  $cheque_numero=$FORMULARIO["cheque_numero"];
	  @$cheque_matricula_arancel=$FORMULARIO["cheque_matricula_arancel"];
	  if(empty($cheque_matricula_arancel))
	  {$cheque_matricula_arancel="OFF";}
	$total_a_pagar_arancel=$_SESSION["FINANZAS"]["total_a_pagar_arancel"];
	/////////////-----------------------////////////
	//Guardo en session
	if(((is_numeric($contado_cantidad))and(is_numeric($linea_credito_cantidad))and(is_numeric($cheque_cantidad)))and($contado_cantidad + $linea_credito_cantidad + $cheque_cantidad <= $total_a_pagar_arancel))
	{
		$total_a_pagar_arancel=$_SESSION["FINANZAS"]["total_a_pagar_arancel"];
		$arancel_total_semestre=$_SESSION["FINANZAS"]["arancel"];
		//asignacion automatica de % descuento si paga todo al contado
		if(($contado_cantidad==$arancel_total_semestre)and($contado_descuento==0)and($sede_user_activo!="Linares"))
		{
			//$contado_descuento=10;
			//$objResponse->Assign("contado_descuento","value",$contado_descuento);//saco descuento automatico
		}
	
		
		//linea_credito
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"]=$linea_credito_cantidad;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"]=$linea_credito_cantidad_cuotas;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["mes_ini_cuota"]=$linea_credito_mes_ini;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["dia_vence_cuota"]=$linea_credito_dia_vencimiento;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["year"]=$linea_credito_year;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["meses_avance"]=$meses_avance;
		
		//contado
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"]=$contado_cantidad;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["descuento"]=$contado_descuento;
		//$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["total"]
		//cheque
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"]=$cheque_cantidad;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["banco"]=$cheque_banco;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["fecha_vencimiento"]=$cheque_fecha_vence;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["numero"]=$cheque_numero;
	
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["matricula_arancel"]=$cheque_matricula_arancel;
		
		//CALCULANDO RESUMEN
		$total_acordado_now=($linea_credito_cantidad + $contado_cantidad + $cheque_cantidad);
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
				<td><strong>N.</strong></td>
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
			case"CONTADO":
				$AUX=($contado_cantidad*$contado_descuento)/100;
				$total=$contado_cantidad-$AUX;
				$objResponse->Assign("resultado_contado","innerHTML","$total");
				break;
			case"CHEQUE":
				break;		
		}
		
		if($total_sin_acuerdo==0)
		{
			$html_boton='<input name="btn" type="button" value="continuar&#9658;&#9658;" onclick="Verificar(\'normal\');" />';
			$objResponse->Assign("botonera","innerHTML",$html_boton);
		}
		else
		{
			$html_boton='';
			$objResponse->Assign("botonera","innerHTML",$html_boton);
		}
		
		
		
		$html="LINEA CREDITO -> $linea_credito_cantidad<br>CONTADO -> $contado_cantidad<br>CHEQUE -> $cheque_cantidad<br>____________________<br>Sin Pactar ==> $total_sin_acuerdo";
		$objResponse->Assign("pago_0","innerHTML",$html);
	}
	else
	{
		$objResponse->Assign("linea_credito_cantidad","value",$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"]);
		$objResponse->Assign("contado_cantidad","value",$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"]);
		$objResponse->Assign("cheque_cantidad","value",$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"]);
		$objResponse->alert("Dato incorrecto o Cantidad mayor a Total...");
	}
	}
	else
	{
		//$objResponse->alert("--->Excedentes...$TOTAL_INICIAL");
		$valor_excedente=abs($TOTAL_INICIAL);
		$_SESSION["FINANZAS"]["excedente_proximo_contrato"]=$valor_excedente;
		$html="EXCEDENTES -> $valor_excedente <br>LINEA CREDITO -> 0<br>CONTADO -> 0<br>CHEQUE -> 0<br>____________________<br>Sin Pactar ==> 0";
		$objResponse->Assign("pago_0","innerHTML",$html);
		//$objResponse->alert("DEXXXX...");
		//linea_credito
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"]=0;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"]=0;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["mes_ini_cuota"]="";
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["dia_vence_cuota"]="";
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["year"]=0;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["meses_avance"]=0;
		
		//contado
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"]=0;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["descuento"]=0;
		//$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["total"]
		//cheque
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"]=0;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["banco"]="";
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["fecha_vencimiento"]="";
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["numero"]="";
	
		$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["matricula_arancel"]="ON";
	}
	return $objResponse;
}
function ACTUALIZA_TOTAL($total)
{
	$div='apDiv3';
	$objResponse = new xajaxResponse();
	if($total>0)
	{
		$objResponse->Assign("linea_credito_cantidad","value",$total);
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"]=$total;
		$_SESSION["FINANZAS"]["excedente_proximo_contrato"]=0;
	}
	else
	{
		$valor_excedente=abs($total);
		$_SESSION["FINANZAS"]["excedente_proximo_contrato"]=$valor_excedente;
		$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"]=0;
		$html='<form action="paso_3c_X.php" method="post" name="frm" id="frm">
		<table border="0" width="95%">
				<thead>
				<tr>
					<th colspan="2" bgcolor="#f5f5f5">Excedentes<input name="validador" type="hidden" id="validador" value="'.md5("PASO3".date("Y-m-d")).'" /></th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td bgcolor="#e5e5e5">Excedentes Proximo Contrato</td>
				<td bgcolor="#e5e5e5">'.$valor_excedente.'</td>
				</tr>
				<tr>
				<td bgcolor="#e5e5e5"><input name="btn_ant" type="button" value="<< Anterior"  onclick="Volver();"/></td>
				<td bgcolor="#e5e5e5"><input name="btn" type="button" value="continuar >>" onclick="Verificar(\'excedente\');"/></td>
				</tr>
				</tbody>
				</table>
				</form>';
		$objResponse->Alert("Alumno Con Excedente... $valor_excedente");
		$objResponse->Assign($div,"innerHTML",$html);
	}
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>