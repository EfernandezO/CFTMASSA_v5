<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("asignar_beca_2_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"RECALCULAR");
$xajax->register(XAJAX_FUNCTION,"ARANCEL_X_SEMESTRE");
$xajax->register(XAJAX_FUNCTION,"ASIGNAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"QUITAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_TABLA_BENEFICIOS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"ARMAR_CUOTAS");
////////////////////////////////////////////
function ACTUALIZAR_TABLA_BENEFICIOS($arancel){
	$objResponse = new xajaxResponse();
	$div="div_beneficiosEstudiantilesAsignados";
	$html="";
	
	
	$html.='<table width="100%">
      <thead>
        <tr>
          <th colspan="3">Beneficio Estudiantil Asignados</th>
        </tr>
      </thead>
      <tbody>';
	  $totalBeneficios=0;
	 foreach($_SESSION["FINANZASX"]["beneficiosEstudiantiles"] as $auxIdBeneficio =>$arrayValores){
		 $auxNombre=$arrayValores["nombre"];
		 $auxAporteValor=$arrayValores["aporteValor"];
		 $auxAportePorcentaje=$arrayValores["aportePorcentaje"];
		 $auxTipo=$arrayValores["tipo"];
		 $auxForma=$arrayValores["forma"];
		 
		
			 if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
			 else{$totalizadoBeneficio=$auxAporteValor;}
		
		 $totalBeneficios+=$totalizadoBeneficio;
		 $html.='<tr>
		 <td>'.$auxNombre.'</td>';
		 
		 if($auxForma=="variable"){$html.='<td align="right"><input onblur="xajax_ACTUALIZAR_BENEFICIO('.$auxIdBeneficio.', this.value, '.$arancel.')" name="beneficio" type="text" value="'.$totalizadoBeneficio.'"/></td>';}
		 else{$html.='<td align="right">$'.number_format($totalizadoBeneficio,0,",",".").'</td>';}
				 
		$html.='<td><a href="#" onclick="xajax_QUITAR_BENEFICIO('.$auxIdBeneficio.', '.$arancel.');"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="quitar" /></a></td>
				 </tr>';
	 }
	 
    $html.='<tr>
				<td><strong>TOTAL</strong></td>
				<td align="right"><strong>$'.number_format($totalBeneficios,0,",",".").'</strong></td>
				<td>&nbsp;</td>
			</tr></tbody></table>';

  $objResponse->Assign($div,"innerHTML",$html);
  
  $objResponse->Assign("campo_totalBeneficiosEstudiantiles","value",$totalBeneficios);
  $objResponse->script("xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'))");
  
  return $objResponse;
}

function ASIGNAR_BENEFICIO($id_beneficio, $arancel)
{
	$objResponse = new xajaxResponse();
	require("../../../funciones/conexion_v2.php");
	if(!isset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio])){
		
	 $cons_BE="SELECT * FROM beneficiosEstudiantiles WHERE id='$id_beneficio' LIMIT 1";
	  $sqli_BE=$conexion_mysqli->query($cons_BE);
	  $DBE=$sqli_BE->fetch_assoc();
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["nombre"]=$DBE["beca_nombre"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["tipo"]=$DBE["beca_tipo_aporte"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["forma"]=$DBE["formaAporte"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["aporteValor"]=$DBE["beca_aporte_valor"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["aportePorcentaje"]=$DBE["beca_aporte_porcentaje"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["familiaBeneficio"]=$DBE["familiaBeneficio"];
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["duracion"]=$DBE["duracion"];
		
		
	$sqli_BE->free();	
	}
	$conexion_mysqli->close();
	
	$objResponse->script('xajax_ACTUALIZAR_TABLA_BENEFICIOS('.$arancel.');');
	
	return $objResponse;
}
function QUITAR_BENEFICIO($id_beneficio, $arancel)
{
	$objResponse = new xajaxResponse();
	if(isset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio])){
		unset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]);
	}
	$objResponse->script('xajax_ACTUALIZAR_TABLA_BENEFICIOS('.$arancel.');');
	return $objResponse;
}

function ACTUALIZAR_BENEFICIO($id_beneficio, $nuevoValor, $arancel){
	$objResponse = new xajaxResponse();
	
	if(!is_numeric($nuevoValor)){$nuevoValor=0;}
	if($nuevoValor<0){$nuevoValor=0;}
	
	if(isset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio])){
		$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$id_beneficio]["aporteValor"]=$nuevoValor;
	}
	$objResponse->script('xajax_ACTUALIZAR_TABLA_BENEFICIOS('.$arancel.');');
	return $objResponse;
}
function VERIFICAR($id_alumno, $arancel){
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	
	$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$yearIngresoCarrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	
	//quito beneficios en "0"
	$errores=false;
	$msj="";
	$arrayDuraciones=array();
	if((isset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"]))and(count($_SESSION["FINANZASX"]["beneficiosEstudiantiles"])>0)){
	 foreach($_SESSION["FINANZASX"]["beneficiosEstudiantiles"] as $auxIdBeneficio =>$arrayValores){
		
		 $auxNombre=$arrayValores["nombre"];
		 $auxAporteValor=$arrayValores["aporteValor"];
		 $auxAportePorcentaje=$arrayValores["aportePorcentaje"];
		 $auxTipo=$arrayValores["tipo"];
		 $auxForma=$arrayValores["forma"];
		 $auxFamiliaBeneficio=$arrayValores["familiaBeneficio"];
		 $auxDuracion=$arrayValores["duracion"];
		 
		 if($auxDuracion>0){
			 if(isset( $arrayDuracion[$auxFamiliaBeneficio])){
			  $arrayDuracion[$auxFamiliaBeneficio]+=SEMESTRES_CON_BECA_V2($id_alumno, $auxIdBeneficio, $id_carrera_alumno, $yearIngresoCarrera_alumno);}
			  else{$arrayDuracion[$auxFamiliaBeneficio]=SEMESTRES_CON_BECA_V2($id_alumno, $auxIdBeneficio, $id_carrera_alumno, $yearIngresoCarrera_alumno);}
			 // $objResponse->Alert($auxNombre.'['.$arrayDuracion[$auxFamiliaBeneficio].']');
			  if($arrayDuracion[$auxFamiliaBeneficio]>$auxDuracion){$errores=true; $msj.='Beneficio :'.$auxNombre.', excede la duracion establecida: '.$arrayDuracion[$auxFamiliaBeneficio].'/'.$auxDuracion;}
		 }
		 if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
		 else{$totalizadoBeneficio=$auxAporteValor;}
		 if($totalizadoBeneficio==0){unset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]);}
	 }
	}
	
	if($errores){$objResponse->Alert('Errores, Revisar\n '.$msj.'\n');}
	else{ $objResponse->script("document.getElementById('frm').submit()");}
	return $objResponse;
}
////////////////
function ACTUALIZA_CANTIDAD($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	
	$arancel_contrato=$FORMULARIO["arancel"];
	$ya_cancelado=$FORMULARIO["total_cancelado"];
	$saldo_a_favor=$FORMULARIO["excedente_anterior"];
	$totalBeneficiosEstudiantiles=$FORMULARIO["totalBeneficiosEstudiantiles"];
	
	$cantidad_a_pactar=((($arancel_contrato-$ya_cancelado)-$saldo_a_favor));
	
	$total=($cantidad_a_pactar-$totalBeneficiosEstudiantiles);
	
	//$objResponse->Alert(" arancel: $arancel_contrato\n total beneficios Estudiantiles: $totalBeneficiosEstudiantiles\n saldo a favor: $saldo_a_favor\n ya cancelado: $ya_cancelado\n TOTAL: $total");
	
	$objResponse->Assign("campo_totalDeuda","value",$cantidad_a_pactar);
	$objResponse->Assign("campo_total_saldar","value",$total);
	
	$objResponse->script("xajax_RECALCULAR(xajax.getFormValues('frm'))");
	
	return $objResponse;
}
function RECALCULAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$div="DEBUG_1";
	$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	
	$id_contrato=$FORMULARIO["id_contrato"];
	$max_num_cuotas=12;
	$linea_credito_meses_avance=1;
	$max_avance_mes=3;
	$max_dia_mes=30;
	$vigencia_contrato_manual=$FORMULARIO["vigencia_contrato_manual"];
	//////////////////////////////////
	
	$arancel_contrato=$FORMULARIO["arancel"];
	$ya_cancelado=$FORMULARIO["total_cancelado"];
	$saldo_a_favor=$FORMULARIO["excedente_anterior"];
	$totalBeneficiosEstudiantiles=$FORMULARIO["totalBeneficiosEstudiantiles"];
	$total_saldar=$FORMULARIO["total_saldar"];

	
		
	if( (float) $total_saldar<=0){$hay_excedentes=true;}
	else{$hay_excedentes=false;}

		//segun total generar cuotas o excedente
		$CUADRO_EXCEDENTE='
	<table width="100%" border="0">
	<thead>
	  <tr>
		<th colspan="2"><span class="Estilo2">&gt;Informacion<strong>
		  <input name="hay_cuotas" type="hidden" id="hay_cuotas" value="0"/>
		  <input name="validador" type="hidden" id="validador" value="'. md5("reasignacion_c".date("Y-m-d")).'" />
		 
		</strong></span></th>
		</tr>
		</thead>
		<tbody>
	  <tr>
		<td >Excedente A Favor Alumno</td>
		<td ><input name="excedente_valor" type="text" id="excedente_valor" value="'.($total_saldar*-1).'" /></td>
	  </tr>
	  <tr>
		<td colspan="2" ><div align="right">
		 
		</div></td>
		</tr>
		</tbody>
	</table>
	';
	
		$CUADRO_CUOTAS='
			<table width="100%" border="0">
			<thead>
			  <tr>
				<th colspan="4"><input name="validador" type="hidden" id="validador" value="'. md5("reasignacion_c".date("Y-m-d")).'" />
				  <strong>>Linea Credito
				   <input name="hay_cuotas" type="hidden" id="hay_cuotas" value="1"/>
				  </strong></th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
				<td width="136">Cantidad</td>
				<td colspan="3"><input type="text" name="linea_credito_cantidad" id="linea_credito_cantidad"  value="'. $total_saldar.'" onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;" readonly="readonly"/></td>
			  </tr>
			  <tr>
				<td>Numero de Cuotas</td>
				<td colspan="3"><select name="linea_credito_cantidad_cuotas" id="linea_credito_cantidad_cuotas"  onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">';
		   
				for($c=1;$c<=$max_num_cuotas;$c++)
				{$CUADRO_CUOTAS.='<option value="'.$c.'">'.$c.'</option>';}
				
				$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Mes Inicio</td>
				<td width="117"><select name="linea_credito_mes_ini" id="linea_credito_mes_ini" onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">';
				foreach($array_meses as $n => $valor)
				{
					if($n+1==date("m"))
					{$CUADRO_CUOTAS.='<option value="'.($n + 1).'" selected="selected">'.$valor.'</option>';}
					else
					{$CUADRO_CUOTAS.='<option value="'.($n + 1).'">'.$valor.'</option>';}	
				}
						  $CUADRO_CUOTAS.='</select></td>
				<td width="118">Meses Avance</td>
				<td width="97"><select name="meses_avance" id="meses_avance" onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">';
				  for($ma=1;$ma<=$max_avance_mes;$ma++)
				  {
					if($linea_credito_meses_avance==$ma)
					{$CUADRO_CUOTAS.='<option value="'.$ma.'" selected="selected">'.$ma.'</option>';}
					else
					{$CUADRO_CUOTAS.='<option value="'.$ma.'">'.$ma.'</option>';}
				  }
				$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Dia Vencimiento</td>
				<td colspan="3"><select name="linea_credito_dia_vencimiento" id="linea_credito_dia_vencimiento" onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">';
				
		 $array_dias_disponibles=array(5,10,15,20,25,30);
				  
		  foreach($array_dias_disponibles as $n => $valor)
		  {$CUADRO_CUOTAS.='<option value="'.$valor.'">'.$valor.'</option>';}
		  
			$CUADRO_CUOTAS.='</select></td>
			  </tr>
			  <tr>
				<td>Year</td>
				<td colspan="3">
				<select name="linea_credito_year" id="linea_credito_year" onchange="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">';
					$a�o_actual=date("Y");
					$a�o_ini=$a�o_actual-10;
					$a�o_fin=$a�o_actual+1;
					for($a=$a�o_ini;$a<=$a�o_fin;$a++)
					{
						if($a==$a�o_actual)
						{$CUADRO_CUOTAS.='<option value="'.$a.'" selected="selected">'.$a.'</option>';}
						else
						{$CUADRO_CUOTAS.='<option value="'.$a.'" >'.$a.'</option>';}	
					}
		$CUADRO_CUOTAS.='</select> </td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
			  </tr>
			  </tbody>
			</table>
			<div id="resultado_linea_credito">
			  <div align="center"><a href="#" onclick="xajax_ARMAR_CUOTAS(xajax.getFormValues(\'frm\'));return false;">Actualizar</a></div>
			</div>';
		
		$boton='<a href="#" class="button_G" onclick="xajax_VERIFICAR(document.getElementById(\'id_alumno\').value, document.getElementById(\'arancel\').value)">GRABAR</a>';
		
		if($hay_excedentes)
		{$objResponse->Assign("apDiv3","innerHTML",$CUADRO_EXCEDENTE);}
		else
		{ $objResponse->Assign("apDiv3","innerHTML",$CUADRO_CUOTAS); $objResponse->script("xajax_ARMAR_CUOTAS(xajax.getFormValues('frm'))");}
		
		$objResponse->Assign("div_botonera","innerHTML",$boton);
		
		
	
	return $objResponse;
}

function ARMAR_CUOTAS($FORMULARIO){
	$div="resultado_linea_credito";
	$html="<table width='100%'>
			<tr>
				<td>N</td>
				<td>Vencimiento</td>
				<td>Valor</td>
			</tr>";
	$objResponse = new xajaxResponse();
		//$objResponse->Alert("ARMAR CUOTAS");
		
		//var_dump($FORMULARIO);
		//////-------------////ARMANDO CUOTAS////////////////--------------------/////////////////////
		$dia_vence=$FORMULARIO["linea_credito_dia_vencimiento"];
		$mes=$FORMULARIO["linea_credito_mes_ini"];
		$a�o=$FORMULARIO["linea_credito_year"];
		$linea_credito_cantidad_pactar=$FORMULARIO["linea_credito_cantidad"];
		$linea_credito_cantidad_cuotas=$FORMULARIO["linea_credito_cantidad_cuotas"];
		$meses_avance=$FORMULARIO["meses_avance"];
		
		$valor_cuota=round($linea_credito_cantidad_pactar/$linea_credito_cantidad_cuotas);
		
		for($c=1;$c<=$linea_credito_cantidad_cuotas;$c++)
		{
			if(($dia_vence>28)and($mes==2))
			{$vencimiento="28/02/$a�o";}
			else
			{
				if($mes<10)
				{$mes_label="0".$mes;}
				else{$mes_label=$mes;}
				if($dia_vence<10)
				{$dia_vence_label="0".$dia_vence;}
				else{$dia_vence_label=$dia_vence;}
				$vencimiento="$dia_vence_label-$mes_label-$a�o";	
			}	
			$html.='<tr>
						<td>'.$c.'/'.$linea_credito_cantidad_cuotas.'</td>
						<td>'.$vencimiento.'</td>
						<td>$'.number_format($valor_cuota,0,",",".").'</td>
					</tr>';
			////avance y condiciones para fechas
			$mes+=$meses_avance;
			if($mes>12)
			{
				$mes-=12;//modificado
				$a�o++;
			}
		}
			//////////////////////////////////
	$html.='</table>';		
	$objResponse->Assign($div,"innerHTML",$html);	
		
	return $objResponse;
}
function ARANCEL_X_SEMESTRE($vigencia)
{
	$objResponse = new xajaxResponse();
	
	$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$year_actual=date("Y");
	require("../../../funciones/conexion_v2.php");
	
	$cons="SELECT arancel_1, arancel_2 FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera_alumno' AND sede='$sede_alumno' AND year='$year_actual' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$DC=$sqli->fetch_assoc();
		$arancel[1]=$DC["arancel_1"];
		$arancel[2]=$DC["arancel_2"];
	$sqli->free();	
		
	$conexion_mysqli->close();
	//----------------------------------------------------------------------------//
	switch($vigencia)
	{
		case"anual":
			$arancel_X=$arancel[1]+$arancel[2];
			break;
		default:
			$array_vigencia=explode("_", $vigencia);
			$semestre_X=$array_vigencia[0];
			$arancel_X=$arancel[$semestre_X];
	}
	//---------------------------------------------------------------------------///
	//$objResponse->Alert('ARANCEL_X_SEMESTRE\n arancel: '.$arancel_X);
	$objResponse->Assign("arancel", "value", $arancel_X);
	$objResponse->script("xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'))");
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>