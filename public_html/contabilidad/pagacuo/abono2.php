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
	$error="debug";
	$tipo_doc_pago="cuota";
	$acceso=false;
	$validador=$_POST["validador"];
	$comparador= md5("ABONO".date("Y-m-d"));
	if($comparador==$validador)
	{$acceso=true;}
	
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $hay_alumno_seleccionado=true; if(DEBUG){ echo"hay Alumno Seleccionada en Sesion<br>";}}
	else{  $hay_alumno_seleccionado=false; if(DEBUG){ echo"NO hay Alumno Seleccionada en Sesion<br>";}}
	////////////////////////
	$verificador=$_SESSION["CUOTAS"]["verificador"];
	
     if(($_POST)and($acceso)and($verificador)and($hay_alumno_seleccionado))
	 {
	
		if(DEBUG){var_dump($_POST);}
		else{ $_SESSION["CUOTAS"]["verificador"]=false;}
		
		 require("../../../funciones/conexion_v2.php");
		 require("../../../funciones/funcion.php");
		 require("../../../funciones/funciones_sistema.php");
		 $id_cuota=$_POST["id_cuota"];
		 $abono=str_inde($_POST["abonoxcuota"]);
		 $TOTAL_A_PAGAR=$abono;
		 $comentario=str_inde($_POST["comentario"]);		
		 $valor_l=$_POST["oculto_valor"];
		 $deuda_actu=$_POST["ocultodeu_act"];
		 $id_alumno=$_POST["oculto_id_alumno"];
		 $sedeZ=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		 $fecha=date("Y-m-d");
		 $opcion_pago=$_POST["opcion_pago"];
		 
		 $interes_x_atraso=$_POST["interes_x_atraso"];
		 $gastos_cobranza=$_POST["gastos_cobranza"];
		 
		 $aplica_interes_x_atraso=$_POST["aplicar_interes_x_atraso"];
		if($aplica_interes_x_atraso==1){ $aplica_interes_x_atraso=true;}else{$aplica_interes_x_atraso=false;}
		
		$aplica_gastos_cobranza=$_POST["aplicar_gastos_cobranza"];
		if($aplica_gastos_cobranza==1){ $aplica_gastos_cobranza=true;}else{ $aplica_gastos_cobranza=false;}
		
		//-----------------------------------------------------//
		$registrar_interes_x_atraso=false;
		if($aplica_interes_x_atraso)
		{
			if(DEBUG){ echo"<br>Aplicar intereses X atraso<br>";}
			if($abono>=$interes_x_atraso)
			{
				if(DEBUG){ echo"Abono mayor a interes<br>";}
				$abono-=$interes_x_atraso;
				if(DEBUG){ echo"Interese x atraso: $interes_x_atraso<br>Abono - interes:$abono<br><br>";}
				$interes_x_atraso_a_registrar=$interes_x_atraso;
				$registrar_interes_x_atraso=true;
			}
			else
			{
				if(DEBUG){ echo"Abono Menor a interes<br>";}
				$interes_x_atraso_a_registrar=$abono;
				$registrar_interes_x_atraso=true;
				$abono=0;
			}
		}
		else
		{$interes_x_atraso_a_registrar=0; if(DEBUG){ echo"No aplicar interes X atraso<br>";}}
		if(DEBUG){ echo"Interes a Registrar: $interes_x_atraso_a_registrar<br>";}
		
		//-------------------------------------------------------//
		$registrar_gastos_cobranza=false;
		if($aplica_gastos_cobranza)
		{
			if(DEBUG){ echo"<br>Aplicar gastos Cobranza<br>";}
			if($abono>=$gastos_cobranza)
			{
				$abono-=$gastos_cobranza;
				if(DEBUG){ echo"Gastos cobranza: $gastos_cobranza<br>Abono - gastos cobranza:$abono<br>";}
				$gastos_cobranza_a_registrar=$gastos_cobranza;
				$registrar_gastos_cobranza=true;
			}
			else
			{
				$registrar_gastos_cobranza=true;
				$gastos_cobranza_a_registrar=$abono;
				$abono=0;
			}
		}
		else{$gastos_cobranza_a_registrar=0; if(DEBUG){ echo"No aplicar gastos Cobranza<br>";}}
		if(DEBUG){ echo"Gasto Cobranza a Registrar: $gastos_cobranza_a_registrar<br><br>";}
		//-----------------------------------------------------------//

		/////////////
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		$fecha_pagoX=$_POST["fecha_pagoX"];
		/////////////
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
		$sql_abc=$conexion_mysqli->query($cons_abc)or die("cons_abc".$conexion_mysqli->error);
		$D_abc=$sql_abc->fetch_row();
		$numero_total_cuotas=$D_abc[0];
		$sql_abc->free();
		//---------------------------------------------------------------//
		$cons_abc2="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND semestre='$semestre' AND ano='$year' AND tipo='matricula'";
		if(DEBUG){echo"cantidad_cuotas.: $cons_abc2<br>";}
		$sql_abc2=$conexion_mysqli->query($cons_abc2)or die("cantidad cuotas matricula ".$conexion_mysqli->error);
		$D_abc2=$sql_abc2->fetch_row();
			$cantidad_cuotas_matricula=$D_abc2[0];
		if(empty($cantidad_cuotas_matricula))
		{ $cantidad_cuotas_matricula=0;}
		$sql_abc2->free();
		//////////////////////////////////////////////////////////////////////
		///////////////OBTENER DATOS CUOTA/////////////////////
		$cons_tpc="SELECT id_contrato, numcuota, fechavenc, tipo FROM letras WHERE id='$id_cuota' LIMIT 1";
		$sql_tpc=$conexion_mysqli->query($cons_tpc)or die("tipo ".$conexion_mysqli->error);
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
		
		$user_activo=$_SESSION["USUARIO"]["id"];//////usuario que realiza la transsaccion
		
		
		//-----------------------------------------------------------------------------------------------//
		 if(is_numeric($abono))
		 {
		      //si datos correctos
			  //inicio si mayor
			  if($abono > $deuda_actu)
			  {
				  if(DEBUG){echo"<br><br>Abono Mayor a Deuda: Error<br>";}
			     $exedente=$abono - $valor_l;
				 //echo"$exedente<br>"; 
				 $error=2;
			  }
			 else
			  {
			  	if(DEBUG)
				{echo"<br><br>Abono menor o igual a Deuda:<br>";}
			       $deuda=$deuda_actu - $abono;
				   $fecha_p=date("Y-m-d");
				   $movimiento="I";
				   $glosa_boleta="";
				   if($abono>0)
				   {
					   if($abono<$deuda_actu)
					   { $tipo_operacion="abonar"; if(DEBUG){ echo"Tipo de operacion a Realizar:";}}
					   if($abono==$deuda_actu)
					   { $tipo_operacion="pagar";}
					   
					 if(DEBUG){ echo"Abono Mayor a cero, rebajar cuota<br>";}
					 $registrar_rebaje_de_cuota=true;
					 switch($tipo_doc)
					 {
					 	case"matricula":
							if(ES_MATRICULA_NUEVA($id_alumno, $id_contrato_cuota))
							{ $por_concepto="matricula_nueva";}
							else{$por_concepto="matricula";}
							 switch($tipo_operacion)
							 {
								 case"abonar":
								 	$glosa_boleta="Abono por Matricula COD.:$id_cuota";
								 	break;
								 case"pagar":
								 	 $glosa_boleta="Abono Final por Matricula COD.:$id_cuota";
								 	break;	
							 }
							
							 $glosa_boleta.='[br]Cuota: '.$posicion_cuota_f.' Vence: '.$vencimiento_cuota.'[br]';
							 $glosa_boleta.='Valor: $'.number_format($abono,0,",",".").' - ';
							 $glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
							 $comentario.="(matricula)";
							break;
						case"cuota":	
							$por_concepto="arancel";
							 switch($tipo_operacion)
							 {
								 case"abonar":
								 	 $glosa_boleta="Abono por Mensualidad COD.:$id_cuota";
								 	break;
								 case"pagar":
								 	 $glosa_boleta="Abono Final por Mensualidad COD.:$id_cuota";
								 	break;	
							 }
							 $glosa_boleta.='[br]Cuota: '.$posicion_cuota_f.' Vence: '.$vencimiento_cuota.'[br]';
							 $glosa_boleta.='Valor: $'.number_format($abono,0,",",".").' - ';
							 $glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
							 $comentario.="(arancel)";
							break;
						case"examen":
							switch($tipo_operacion)
							 {
								 case"abonar":
								 	$glosa_boleta="Abono por derecho de examen COD.:$id_cuota";
								 	break;
								 case"pagar":
								 	 $glosa_boleta="Abono Final por derecho examen COD.:$id_cuota";
								 	break;	
							 }
							
							$glosa_boleta.='Valor: $'.number_format($abono,0,",",".").' - ';
							$glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
							$por_concepto="examen titulo";
							$comentario.="(examen Titulo)";
							break;		
					 }
				   }
				   else
				   {
					   if(DEBUG){ echo"Abono igual a cero NO rebaja Cuota<br>";}
					    $registrar_rebaje_de_cuota=false;
				   }
					///------------------------------------------------//
					if($aplica_interes_x_atraso)
					{ $glosa_boleta.="Intereses por atraso: $".number_format($interes_x_atraso_a_registrar,0,",",".")."[br]";}
					if($aplica_gastos_cobranza)
					{  $glosa_boleta.="Gastos de Cobranza: $".number_format($gastos_cobranza_a_registrar,0,",",".")."[br]";}
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
								$cheque_fecha_vencimiento="'".$cheque["fecha_vence"]."'";
								$cheque["glosa"]="Abono por Cuota ($por_concepto)";
								
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
								$cheque_fecha_vencimiento="NULL";
								break;	
							default:
								$id_cta_cte="NULL";
								$id_cheque="NULL";	
								$glosa_boleta.="(efectivo)";
								$cheque_fecha_vencimiento="NULL";
						}
					
					$id_boleta=GENERA_BOLETA($id_alumno, $TOTAL_A_PAGAR, $sedeZ, $glosa_boleta, $fecha_pagoX);
				 
				 //------------------------------------------------------------------------------------------//
				 //rebaja cuota y registra pago
				 if($registrar_rebaje_de_cuota)
				 {
					  switch($tipo_operacion)
					 {
						 case"abonar":
							$condicion_cuota="A";
							break;
						 case"pagar":
							 $condicion_cuota="S";
							break;	
					 }
					 if(DEBUG){ echo"<strong>Registrando Rebaje de Cuota...</strong><br>";}
					 
			      	$consP2="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$abono', '$tipo_doc_pago','$comentario', '$sedeZ', '$movimiento', '$opcion_pago', $cheque_fecha_vencimiento, $id_cheque, '$por_concepto', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
					
				 $consL2="UPDATE letras SET deudaXletra='$deuda', pagada='$condicion_cuota', fecha_ultimo_pago='$fecha_pagoX' WHERE id='$id_cuota' LIMIT 1";
					  if(DEBUG)
					  { echo"<br> $consP2<br><br> $consL2<br>";}
					  else
					  {
						 $conexion_mysqli->query($consP2)or die("a1".$conexion_mysqli->error);
						 $conexion_mysqli->query($consL2)or die("a2".$conexion_mysqli->error);
					  }
				 }
				 
				 //registra pago x interes
				 if(($registrar_interes_x_atraso)and($interes_x_atraso_a_registrar>0))
				 {
					  if(DEBUG){ echo"<br><strong>Registrando Interes X atraso...</strong><br>";}
					  
			      	$cons_IA="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$interes_x_atraso_a_registrar', '$tipo_doc_pago','interes_x_atraso', '$sedeZ', '$movimiento', '$opcion_pago', $cheque_fecha_vencimiento, $id_cheque, 'interes_x_atraso', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
					
					if(DEBUG){ echo"--->$cons_IA<br>";}
					else{ $conexion_mysqli->query($cons_IA);}
				 }
				 
				 //Registrar gastos cobranza
				 if(($registrar_gastos_cobranza)and($gastos_cobranza_a_registrar>0))
				 {
					 if(DEBUG){ echo"<br><strong>Registrando Gastos cobranza...</strong><br>";}
					  
			      	$cons_GC="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_empresa, id_factura, id_multi_cheque, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', NULL, NULL, NULL, $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$gastos_cobranza_a_registrar', '$tipo_doc_pago','gastos_cobranza', '$sedeZ', '$movimiento', '$opcion_pago', $cheque_fecha_vencimiento, $id_cheque, 'gastos_cobranza', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
					
					if(DEBUG){ echo"--->$cons_GC<br>";}
					else{ $conexion_mysqli->query($cons_GC);}
				 }
				 $error=0;
			  }
			  //fin si menor
//---------------------------------------------------------------------------------------------------------------//			
		 }
		 else
		 {$error=1;}	
		   
		 if($error==0)
		 {
		 	///////////////registr evento/////////////////////
			 include("../../../funciones/VX.php");
			 $evento="Abono a Cuota id_cuota: $id_cuota id_alumno: $id_alumno";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////////////////////////////////
		 }
		$conexion_mysqli->close();
		if(DEBUG)
		{echo"<br><strong>Fin Proceso</strong><br>";}
		else
		{
			header("Location: abono3.php?error=$error&id_boleta=$id_boleta&semestre=$semestre&year_estudio=$year&id_cuota=$id_cuota&valor=$TOTAL_A_PAGAR");
		}	
	 }
	 else
	 { 
	 	if(DEBUG){ echo"Sin Acceso<br>";}else{header("location: cuota1.php");}
	 }

//----------------------------------------------------------------------------------------------------------------------//
?>