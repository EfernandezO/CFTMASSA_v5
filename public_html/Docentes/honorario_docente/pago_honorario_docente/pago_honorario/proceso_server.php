<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_METODO_PAGO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");


function CARGA_METODO_PAGO($metodo_pago)
{
$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
		$objResponse = new xajaxResponse();
		$div='apDiv2';
		$html_cheque='<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Forma de Pago</th>
    </tr>
    </thead>
    <tbody>
	<tr>
		<td>Froms de Pago</td>
		<td>Cheque</td>
	</tr>
    <tr>
      <td>Numero Cheque</td>
      <td><label for="numero_cheque"></label>
        <input name="numero_cheque" type="text" id="numero_cheque" size="15" /></td>
    </tr>
    <tr>
      <td width="50%">Banco</td>
      <td width="50%">
        <select name="cheque_banco" id="cheque_banco">';
         
		 foreach($array_bancos as $n)
		 {$html_cheque.='<option value="'.$n.'">'.$n.'</option>';}
		
        $html_cheque.='</select>
      </td>
    </tr>
	 <tr>
      <td>Boleta Honorario</td>
      <td>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    </tbody>
  </table>';
  
  $html_transferencia='<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Forma de Pago</th>
    </tr>
    </thead>
    <tbody>
	<tr>
		<td>Forma de Pago</td>
		<td>Transferencia Electronica</td>
	</tr>
    <tr>
      <td>Numero Cuenta</td>
      <td>
        <input name="numero_cuenta" type="text" id="numero_cuenta" size="15" /></td>
    </tr>
    <tr>
      <td width="50%">Banco</td>
      <td width="50%">
        <select name="banco_cuenta" id="banco_cuenta">';
         
		 foreach($array_bancos as $n)
		 {$html_transferencia.='<option value="'.$n.'">'.$n.'</option>';}
		
        $html_transferencia.='</select>
      </td>
    </tr>
	 <tr>
      <td>Boleta Honorario</td>
      <td>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    </tbody>
  </table>';
		
	$html_efectivo='<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Forma de Pago</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Forma Pago</td>
      <td>Efectivo</td>
    </tr>
	 <tr>
      <td>Boleta Honorario</td>
      <td>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    </tbody>
  </table>';
  
  switch($metodo_pago)	
  {
	  case"efectivo":
	  	$html=$html_efectivo;
	  	break;
	  case"cheque":
	  	$html=$html_cheque;
	  	break;
	   case"transferencia":
	  	$html=$html_transferencia;
	  	break;	
  }
  $boton_VERIFICAR='<a href="#" class="button_R" onclick="CONFIRMAR();"> Seguro(a) Desea Realizar este Pago de Honorario ¿?</a>';
  
  $html.="</br></br><p>".$boton_VERIFICAR.'</p>';
		$objResponse->Assign($div,"innerHTML",$html);
		
		return $objResponse;
}
function VERIFICAR($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$metodo_pago=$FORMULARIO["forma_pago"];
	$archivo=$FORMULARIO["archivo"];
	$id_honorario=$FORMULARIO["id_honorario"];
	$pagoActual=$FORMULARIO["total"];
	$cargar_archivo=false;////
	
	 require("../../../../../funciones/conexion_v2.php");
	 $cons="SELECT total FROM honorario_docente WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons);
	$D=$sqli->fetch_assoc();
		$H_total=$D["total"];
	$sqli->free();
		
	//busco pagos previo al honorario
	if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
	$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
	if(DEBUG){echo"-->$consPP<br>";}
	$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
	$PP=$sqliPP->fetch_row();
	$pagosPrevios=$PP[0];
	if(empty($pagosPrevios)){$pagosPrevios=0;}
	$sqliPP->free();
	if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
	$deudaXcuota=$H_total-$pagosPrevios;
	$deudaXcuota=number_format($deudaXcuota,0,"","");
	$conexion_mysqli->close();
	//-------------------------------------------------------------------
	
	
	if($pagoActual<=$deudaXcuota){$continuar1=true;}
	else{$continuar1=false; $objResponse->alert("Pago Excede a Deuda Actual Verificar...[$pagoActual /$deudaXcuota]");}
	
	if(empty($archivo)){ $archivo_cargado=false;}
	else{ $archivo_cargado=true;}
	
	if(($archivo_cargado)or(!$cargar_archivo))
	{
		switch($metodo_pago)
		{
			case"efectivo":
				$continuar=true;
				break;
			case"transferencia":
				$numero_cuenta=$FORMULARIO["numero_cuenta"];
				if((empty($numero_cuenta))or($numero_cuenta==" "))
				{
					$continuar=false;
					$objResponse->alert("Ingrese Numero de Cuenta");
				}
				else
				{$continuar=true;}
				break;	
			case"cheque":
				$numero_cheque=$FORMULARIO["numero_cheque"];
				$banco_cheque=$FORMULARIO["cheque_banco"];
				if((empty($numero_cheque))or($numero_cheque==" "))
				{
					$continuar=false;
					$objResponse->alert("Ingrese Numero de Cheque");
				}
				else
				{
					require("../../../../../funciones/conexion_v2.php");
					$cons_BCH="SELECT COUNT(id) FROM registro_cheques WHERE emisor='massachusetts' AND numero='$numero_cheque' AND banco='$banco_cheque'"; 
					$sqli_bch=$conexion_mysqli->query($cons_BCH);
					$Dbch=$sqli_bch->fetch_row();
						$coincidencias=$Dbch[0];
						if(empty($coincidencias)){$coincidencias=0;}
					$sqli_bch->free();	
					$conexion_mysqli->close();	
					
					if($coincidencias>0)
					{ $continuar=false; $objResponse->alert("Cheque Ya registrado Anteriormente");}
					else
					{$continuar=true;}
				}
				break;
			default:
				$continuar=false;	
		}
	}
	else
	{
			$continuar=false;
			$objResponse->alert("Boleta de Honorario No Cargada");
	}
	//-------------------------------------------------------------------------------------------//
	if($continuar and $continuar1){$objResponse->script("document.getElementById('frm').submit();");}
	//--------------------------------------------------------------------------------------------//
	return $objResponse;
}
$xajax->processRequest();
?>