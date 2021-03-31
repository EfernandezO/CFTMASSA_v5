<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	$acceso=false;
	$validador=$_POST["validador"];
	$comparador=md5("PAGO".date("Y-m-d"));
	if($comparador==$validador)
	{$acceso=true;}
	$verificador=$_SESSION["CUOTAS"]["verificador"];
	
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $hay_alumno_seleccionado=true; if(DEBUG){ echo"hay Alumno Seleccionada en Sesion<br>";}}
	else{  $hay_alumno_seleccionado=false; if(DEBUG){ echo"NO hay Alumno Seleccionada en Sesion<br>";}}
	
//---------------------------------------------------//
$error="debug";
	
 if(($_POST)and($acceso)and($verificador)and($hay_alumno_seleccionado))
	{
		if(!DEBUG)
		{ $_SESSION["CUOTAS"]["verificador"]=false;}
	   require("../../../funciones/conexion_v2.php");
	   require("../../../funciones/funcion.php");
	   require("../../../funciones/funciones_sistema.php");
	   
	   if(DEBUG){ var_dump($_POST);}
	   
		$tipo_doc_pago="cuota";
		$id_cuota=$_POST["id_cuota"];
		$valor=$_POST["oculto_valor"];
		$deudaXcuo=$_POST["ocultodeu_act"];
		$sedeZ=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		if(DEBUG){ echo"<b>Sede: $sedeZ</b><br>";}
		$comentario=str_inde($_POST["comentario"]);
		$id_alumno=$_POST["oculto_id_alumno"];
		$fecha_pagoX=$_POST["fecha_pagoX"];///agregada para indicar fecha pago
		//--------------------------------------------//
		//cobranza extra
		$TOTAL_A_PAGAR=$deudaXcuo;
		
		$aplica_interes_x_atraso=$_POST["aplica_intereses_x_atraso"];
		if($aplica_interes_x_atraso==1){ $aplica_interes_x_atraso=true;}else{$aplica_interes_x_atraso=false;}
		
		$aplica_gastos_cobranza=$_POST["aplicar_gastos_cobranza"];
		if($aplica_gastos_cobranza==1){ $aplica_gastos_cobranza=true;}else{ $aplica_gastos_cobranza=false;}
		
		$intereses_x_atraso=$_POST["intereses_x_atraso"];
		$gastos_cobranza=$_POST["gastos_cobranza"];
		
		if($aplica_interes_x_atraso)
		{ if(DEBUG){ echo"Aplicar interes X atraso de: $intereses_x_atraso<br>";} $TOTAL_A_PAGAR+=$intereses_x_atraso;}
		else{if(DEBUG){ echo"NO Aplicar interes X atraso<br>";}}
		if($aplica_gastos_cobranza)
		{ if(DEBUG){echo"Aplicar Gastos de Cobranza de: $gastos_cobranza<br>";} $TOTAL_A_PAGAR+=$gastos_cobranza;}
		else{if(DEBUG){ echo"NO Aplicar Gastos Cobranza<br>";}}
		//---------------------------------------------------------//
		$deuda_actual=$valor - $valor;
		$fecha=date("Y-m-d");
		////datos agregados
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		/////
		$user_activo=$_SESSION["USUARIO"]["id"];
		$opcion_pago=$_POST["opcion_pago"];
		$movimiento="I";
		///////////////////////////////////////
		//para campos agregados
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		///designando codigo item segun pal cuentas
		switch($sedeZ)
		{
			case"Talca":
				$codigo_item="4101286";
				break;
			case"Linares":
				$codigo_item="4101294";
				break;
		}
		//////////////////////mas datos de Cuotas/////////////////////////////
		$cons_abc="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND semestre='$semestre' AND ano='$year'";
		if(DEBUG){echo"cantidad_cuotas.: $cons_abc<br>";}
			$sql_abc=$conexion_mysqli->query($cons_abc)or die($conexion_mysqli->error);
			$D_abc=$sql_abc->fetch_row();
			$numero_total_cuotas=$D_abc[0];
		$sql_abc->free();
		//--------------------------------------------------------------------------------//
		
		$cons_abc2="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND semestre='$semestre' AND ano='$year' AND tipo='matricula'";
		if(DEBUG){echo"cantidad_cuotas.: $cons_abc2<br>";}
		
		$sql_abc2=$conexion_mysqli->query($cons_abc2)or die("Cantidad Cuotas Matricula".$conexion_mysqli->error);	
		$D_abc2=$sql_abc2->fetch_row();
		$cantidad_cuotas_matricula=$D_abc2[0];
		if(empty($cantidad_cuotas_matricula)){ $cantidad_cuotas_matricula=0;}
		$sql_abc2->free();
		//////////////////////////////////////////////////////////////////////
		///////////////OBTENER TIPO y otros CUOTA/////////////////////
		$cons_tpc="SELECT id_contrato, numcuota, fechavenc, tipo FROM letras WHERE id='$id_cuota' LIMIT 1";
		$sql_tpc=$conexion_mysqli->query($cons_tpc)or die("Tipo".$conexion_mysqli->error);
		$DX_tpc=$sql_tpc->fetch_assoc();
			$tipo_doc=$DX_tpc["tipo"];
			$posicion_cuota=$DX_tpc["numcuota"];
			$vencimiento_cuota=fecha_format($DX_tpc["fechavenc"]);
			$id_contrato_cuota=$DX_tpc["id_contrato"];
		$sql_tpc->free();
		//////////////////--------------------////////////////
		//datos de cuota
		//le sumo solo si es cuota
		if($tipo_doc=="cuota")
		 { $posicion_cuota+=$cantidad_cuotas_matricula; }
		$posicion_cuota_f=$posicion_cuota."/".$numero_total_cuotas;
		if(DEBUG){ echo"P final.: $posicion_cuota_f<br>";}
		//------------------//
		if(empty($tipo_doc))
		{$tipo_doc="cuota";}	
		switch($tipo_doc)
		{
			case"cuota":
				$glosa_boleta="Pago Mensualidad COD.:$id_cuota";
				$glosa_boleta.='[br]Cuota: '.$posicion_cuota_f.' Vence: '.$vencimiento_cuota.'[br]';
				$glosa_boleta.='Valor: $'.number_format($deudaXcuo,0,",",".").' - ';
				$glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
				$por_concepto="arancel";
				$comentario.="(arancel)";
				
				break;
			case"matricula":
				$glosa_boleta="Pago Matricula COD.:$id_cuota";
				$glosa_boleta.='[br]Cuota: '.$posicion_cuota_f.' Vence: '.$vencimiento_cuota.'[br]';
				$glosa_boleta.='Valor: $'.number_format($deudaXcuo,0,",",".").' - ';
				$glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
				if(ES_MATRICULA_NUEVA($id_alumno, $id_contrato_cuota))
				{ $por_concepto="matricula_nueva";}
				else{$por_concepto="matricula";}
				$comentario.="(matricula)";
				break;
			case"examen":
				$glosa_boleta="Pago cuota derecho a examen de titulo COD.:$id_cuota";
				$glosa_boleta.='Valor: $'.number_format($deudaXcuo,0,",",".").' - ';
				$glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
				$por_concepto="examen titulo";
				$comentario.="(examen Titulo)";
				break;		
				
		}
		
		///------------------------------------------------//
		if($aplica_interes_x_atraso)
		{ $glosa_boleta.="Intereses por atraso: $".number_format($intereses_x_atraso,0,",",".")."[br]";}
		if($aplica_gastos_cobranza)
		{  $glosa_boleta.="Gastos de Cobranza: $".number_format($gastos_cobranza,0,",",".")."[br]";}
		///----------------------------------------------------//
		
		$aux_num_documento="NULL";
		switch($opcion_pago)
		{
			case"cheque":
				$cheque["id_alumno"]=$id_alumno;
				$cheque["numero"]=$_POST["cheque_numero"];
				$cheque["banco"]=$_POST["cheque_banco"];
				$cheque["valor"]=$TOTAL_A_PAGAR;
				$cheque["sede"]=$sedeZ;
				$cheque["fecha_vence"]=$_POST["cheque_fecha_vence"];
				$fecha_vence_cheque="'".$cheque["fecha_vence"]."'";
				$cheque["glosa"]="Pago Total Cuota ($por_concepto)";
				$id_cheque="'".REGISTRA_CHEQUE($cheque)."'";
				$glosa_boleta.="(cheque N:".$cheque["numero"]." vence:".fecha_format($cheque["fecha_vence"])." banco:".$cheque["banco"].")";
				$id_cta_cte="NULL";
				break;
			case"deposito":
				if(DEBUG){echo"Pago -> Deposito<br>";}
				$id_cheque="NULL";
				$deposito_numero=$_POST["deposito_numero"];
				$id_cta_cte="'".$_POST["id_cta_cte"]."'";
				$fecha_vencimiento_cheque="0000-00-00";
				$glosa_boleta.=" (Deposito N.$deposito_numero)";
				$aux_num_documento="'$deposito_numero'";
				$fecha_vence_cheque="NULL";
				break;		
			default:
				$id_cheque="NULL";	
				$fecha_vence_cheque="NULL";
				$glosa_boleta.="(efectivo)";
				$id_cta_cte="NULL";
		}
		
		$id_boleta=GENERA_BOLETA($id_alumno, $TOTAL_A_PAGAR, $sedeZ, $glosa_boleta, $fecha_pagoX);
		
	    $consP="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$deudaXcuo', '$tipo_doc_pago','$comentario', '$sedeZ', '$movimiento', '$opcion_pago', $fecha_vence_cheque, $id_cheque, '$por_concepto', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
		
		 $consP_interes="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$intereses_x_atraso', '$tipo_doc_pago','interes_x_atraso', '$sedeZ', '$movimiento', '$opcion_pago', $fecha_vence_cheque, $id_cheque, 'interes_x_atraso', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
		 
		  $consP_cobranza="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento,'$codigo_item', '$fecha_pagoX', '$gastos_cobranza', '$tipo_doc_pago','gastos_cobranza', '$sedeZ', '$movimiento', '$opcion_pago', $fecha_vence_cheque, $id_cheque, 'gastos_cobranza', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
		
		
		
		$consL="UPDATE letras SET deudaXletra='$deuda_actual', pagada='S', fecha_ultimo_pago='$fecha_pagoX' WHERE id='$id_cuota' LIMIT 1";
		
		
		if(DEBUG)
		{echo"<br>PAGO: $consP<br><br>CUOTA: $consL<br><br>Interes: $consP_interes<br><br>cobranza: $consP_cobranza<br>";}
		else
		{
			if($conexion_mysqli->query($consP))
			{
				if($conexion_mysqli->query($consL))
				{
					if(($aplica_interes_x_atraso)and($intereses_x_atraso>0))
					{ if(DEBUG){echo"INTERES: $consP_interes<br>";}else{$conexion_mysqli->query($consP_interes)or die("Interes ".$conexion_mysqli->error);}}
					
					if(($aplica_gastos_cobranza)and($gastos_cobranza>0))
					{ if(DEBUG){ echo"COBRANZA: $consP_cobranza<br>";}else{$conexion_mysqli->query($consP_cobranza)or die("Gastos Cobranza ".$conexion_mysqli->error);}}
					
					 $error=0;
					 ///////////////registr evento/////////////////////
					 include("../../../funciones/VX.php");
					 $evento="Paga Total Cuota id_cuota:$id_cuota id_alumno:$id_alumno";
					 REGISTRA_EVENTO($evento);
					 ///////////////////////////////////////////////////
				}
				else
				{
					  echo "Letra ".$conexion_mysqli->error;
				}
			}
			else
			{
				 //si error
				  $error=1;
				 // echo "Pago".$conexion_mysqli->error;
				  //echo"X-----> ".$conexion_mysqli->error;
			}
		}	
		
		
		$conexion_mysqli->close();
		if(DEBUG){echo"ERROR: $error<br>";}
		else{header("Location: pago3.php?error=$error&id_cuota=$id_cuota&v=$TOTAL_A_PAGAR&ID=$id_boleta&semestre=$semestre&year_estudio=$year");}
}
else
{header("location: cuota1.php");}
?>