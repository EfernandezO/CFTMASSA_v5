<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("beneficiosServer.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ASIGNAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"QUITAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_TABLA_BENEFICIOS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

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
				 
		$html.='<td><a href="#" onclick="xajax_QUITAR_BENEFICIO('.$auxIdBeneficio.', '.$arancel.');"><img src="../../../../BAses/Images/b_drop.png" width="16" height="16" alt="quitar" /></a></td>
				 </tr>';
	 }
	 
    $html.='<tr>
				<td><strong>TOTAL</strong></td>
				<td align="right"><strong>$'.number_format($totalBeneficios,0,",",".").'</strong></td>
				<td>&nbsp;</td>
			</tr></tbody></table>';

  $objResponse->Assign($div,"innerHTML",$html);
  return $objResponse;
}

function ASIGNAR_BENEFICIO($id_beneficio, $arancel)
{
	$objResponse = new xajaxResponse();
	require("../../../../../funciones/conexion_v2.php");
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
	require("../../../../../funciones/funciones_sistema.php");
	
	
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
			  $arrayDuracion[$auxFamiliaBeneficio]+=SEMESTRES_CON_BECA_V2($id_alumno, $auxIdBeneficio);}
			  else{$arrayDuracion[$auxFamiliaBeneficio]=SEMESTRES_CON_BECA_V2($id_alumno, $auxIdBeneficio);}
			 // $objResponse->Alert($auxNombre.'['.$arrayDuracion[$auxFamiliaBeneficio].']');
			  if($arrayDuracion[$auxFamiliaBeneficio]>$auxDuracion){$errores=true; $msj.='Beneficio :'.$auxNombre.', excede la duracion establecida: '.$arrayDuracion[$auxFamiliaBeneficio].'/'.$auxDuracion;}
		 }
		 if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
		 else{$totalizadoBeneficio=$auxAporteValor;}
		 if($totalizadoBeneficio==0){unset($_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]);}
	 }
	}
	//$errores=false;
	
	if($errores){$objResponse->Alert('Errores, Revisar\n '.$msj.'\n');}
	else{ $objResponse->script("document.getElementById('frm').submit()");}
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>