<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	//////////////
	$glosa_boleta="";
	$acceso=false;
	$validador=$_POST["validador"];
	$comparador=md5("MPAGO".date("Y-m-d"));
	if($comparador==$validador)
	{$acceso=true;}

	$verificador=$_SESSION["CUOTAS"]["verificador"];
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $hay_alumno_seleccionado=true; if(DEBUG){ echo"hay Alumno Seleccionada en Sesion<br>";}}
	else{  $hay_alumno_seleccionado=false; if(DEBUG){ echo"NO hay Alumno Seleccionada en Sesion<br>";}}
	
	
     if(($_POST)and($acceso)and($verificador)and $hay_alumno_seleccionado)
	 {
	 	if(DEBUG)
		{
			var_dump($_POST);
			echo"<br>";
		}
		else
		{ $_SESSION["CUOTAS"]["verificador"]=false;}
		
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funcion.php");
		require("../../../../funciones/funciones_sistema.php");
		
		//obtencion de datos
		$id_cuota=$_POST["id_cuota"];
		$cantidad_pagar=$_POST["cantidad_pagar"];
		$opcion_pago=$_POST["opcion_pago"];
		$comentario=$_POST["comentario"];
		$cantidad_cheques=$_POST["cantidad_cheques"];
		$cheque_numero=$_POST["cheque_numero"];
		$cheque_banco=$_POST["cheque_banco"];
			$CH_year=$_POST["year"];
			$CH_mes=$_POST["mes"];
			$CH_dia=$_POST["dia"];
		
		$cheque_valor=$_POST["cheque_valor"];
		$oculto_valor=$_POST["oculto_valor"];
		$ocultodeu_act=$_POST["ocultodeu_act"];
		$sedeZ=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$id_alumno=$_POST["id_alumno"];
		$fecha_pagoX=$_POST["fecha_pagoX"];
		
		 $aplica_interes_x_atraso=$_POST["aplicar_interes_x_atraso"];
		if($aplica_interes_x_atraso==1){ $aplica_interes_x_atraso=true;}else{$aplica_interes_x_atraso=false;}
		
		$aplica_gastos_cobranza=$_POST["aplicar_gastos_cobranza"];
		if($aplica_gastos_cobranza==1){ $aplica_gastos_cobranza=true;}else{ $aplica_gastos_cobranza=false;}
		//////////////////
		$user_activo=$_SESSION["USUARIO"]["id"];
		$fecha=date("Y-m-d");
		$tipo_doc_pago="cuota";
		$movimiento="I";
		///
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
		if(($cantidad_pagar<=$ocultodeu_act)and(is_numeric($cantidad_pagar)))
		{
		
			////////////
			$array_id_cuota=explode(",",$id_cuota);
			$num_cuotas=count($array_id_cuota);
			////////generando glosa a boleta
			if($cantidad_pagar==$ocultodeu_act)
			{
				$glosa_boleta='Pago de '.$num_cuotas.' Mensualidad(es)[br]';
				$glosa_boleta.='COD.:'.$id_cuota.'[br]';
				$array_id_cuotaXX=explode(",",$id_cuota);
				foreach($array_id_cuotaXX as $nx=> $valornx)
				{
					$glosa_boleta.=DATOS_CUOTA_FULL($valornx, "pagada");
				}
			}
			elseif($cantidad_pagar<$ocultodeu_act)
			{
				$cuotas_pagadasX=($num_cuotas-1);
				$cuotas_abonadasX=1;
				
				if($cuotas_pagadasX>0)
				{
					$glosa_boleta='Pago de '.$cuotas_pagadasX.' Mensualidad(es)[br]';
				}
				$glosa_boleta.='Abono de '.$cuotas_abonadasX.' Mensualidad [br]';
				$glosa_boleta.='COD.:'.$id_cuota.'[br]';
				$array_id_cuotaXX=explode(",",$id_cuota);
				$numero_cuotas_afectadas=count($array_id_cuotaXX);
				if(DEBUG){echo"NA: $numero_cuotas_afectadas<br>";}
				
				$contador_afectadas=0;
				foreach($array_id_cuotaXX as $nx=> $valornx)
				{
					$contador_afectadas++;
					if($contador_afectadas==$numero_cuotas_afectadas)
					{ $condicion="abonada";}
					else
					{ $condicion="pagada";}
					$glosa_boleta.=DATOS_CUOTA_FULL($valornx, $condicion);
				}
				
				if(DEBUG){echo"<br>GLOSA BOLETA: $glosa_boleta <br>";}
			}	
			/////////
			
			$aux_num_documento="NULL";
			$id_cta_cte="NULL";
			$id_cheque="NULL";
			
			switch($opcion_pago)
			{
				case"cheque":
					$acumula_total_al_dia=0;
					$primer_cheque=true;
					if($cantidad_cheques>1)
					{
						$opcion_pago="multi_cheque";
					}
					for($indiceCH=0;$indiceCH<$cantidad_cheques;$indiceCH++)
					{
						if(DEBUG){echo"indiceCH-> $indiceCH<br>";}
						$cheque["id_alumno"]=$id_alumno;
						
						$cheque["numero"]=$cheque_numero[$indiceCH];
						$cheque["banco"]=$cheque_banco[$indiceCH];
						$cheque["valor"]=$cheque_valor[$indiceCH];
						$cheque["sede"]=$sedeZ;
						
						$ch_year=$CH_year[$indiceCH];
						$ch_mes=$CH_mes[$indiceCH];
						if($ch_mes<10)
						{ $ch_mes="0".$ch_mes;}
						$ch_dia=$CH_dia[$indiceCH];
						if($ch_dia<10)
						{$ch_dia="0".$ch_dia;}
						$aux_vencimiento_cheque="$ch_year-$ch_mes-$ch_dia";
						$cheque["fecha_vence"]=$aux_vencimiento_cheque;
						///valor que cheques al dia
						
							if($aux_vencimiento_cheque==$fecha_pagoX)
							{$acumula_total_al_dia+=$cheque["valor"];}
							if($primer_cheque)
							{
								$primer_cheque=false;
								$primer_vencimiento_cheque="'".$aux_vencimiento_cheque."'";
							}
						/////////////
						$cheque["glosa"]="Pago de $num_cuotas Cuota(s)";
						if($opcion_pago=="multi_cheque")
						{ $cheque["glosa"].=" Usando $opcion_pago";}
						
						$id_cheque[$indiceCH]=REGISTRA_CHEQUE($cheque);
						
						
						$glosa_boleta.=" -CH:".$cheque["numero"]." al ".fecha_format($cheque["fecha_vence"])." bco: ".$cheque["banco"]."[br]";
					}
					if($cantidad_cheques==1){$id_cheque=$id_cheque[0];}
					
					break;
				case"deposito":
					if(DEBUG){echo"Pago -> Deposito<br>";}
					$id_cheque="0";
					$deposito_numero=$_POST["deposito_numero"];
					$id_cta_cte="'".$_POST["id_cta_cte"]."'";
					$fecha_vencimiento_cheque="0000-00-00";
					$glosa_boleta.=" (Deposito N.$deposito_numero)";
					$aux_num_documento="'$deposito_numero'";
					$primer_vencimiento_cheque="NULL";
					break;		
				case"efectivo":
					$id_cheque="0";
					$glosa_boleta.="(efectivo)";
					$primer_vencimiento_cheque="NULL";
					break;
			}
		
			$id_boleta=GENERA_BOLETA($id_alumno, $cantidad_pagar, $sedeZ, $glosa_boleta, $fecha_pagoX);
			////////////
			//si pago con multiples cheques genero registro multicheque que los vinculara a todos
			if($opcion_pago=="multi_cheque")
			{
				$id_multi_cheque=GENERA_MULTI_CHEQUE($id_alumno, $sedeZ, $cantidad_pagar, $acumula_total_al_dia, $id_cheque, $fecha_pagoX, $cantidad_cheques);
				$id_cheque="0";
			}
			else
			{ $id_multi_cheque="0";}
			///////////////////////
			$array_id_cuota=explode(",",$id_cuota);
			$num_cuotas=count($array_id_cuota);
			/////selecciono cuotas a pagar las recorro y voy generando los pagos y rebajando cantidad
			
			$cons_Cuo="SELECT * FROM letras WHERE id IN($id_cuota) ORDER by id";
			$sql_cuo=$conexion_mysqli->query($cons_Cuo)or die($conexion_mysqli->error);
			$aux_cantidad_pagar=$cantidad_pagar;

			$TOTAL_interes_x_atraso=0;
			$TOTAL_gastos_cobranza=0;
			$TOTAL_cuotas=0;
			while($DCUO=$sql_cuo->fetch_assoc())
			{
				$id_contrato_cuota=$DCUO["id_contrato"];
				$id_cuota=$DCUO["id"];
				$deudaXcuota=$DCUO["deudaXletra"];
				$valor_cuota=$DCUO["valor"];
				$tipo_doc=$DCUO["tipo"];
				$semestre=$DCUO["semestre"];
				$year=$DCUO["ano"];
				
				if($aplica_interes_x_atraso){$aux_interes_x_atraso=INTERES_X_ATRASO_V2($id_cuota);}
				else{ $aux_interes_x_atraso=0;}
				
				//$interes_x_atraso+=$interes_x_atraso;
				
				if($aplica_gastos_cobranza){ $aux_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);}
				else{ $aux_gastos_cobranza=0;}
				
				//$gastos_cobranza+=$aux_gastos_cobranza;
				
				if(empty($tipo_doc))
				{$tipo_doc="cuota";}	
				switch($tipo_doc)
				{
					case"cuota":
						//$glosa_boleta="Pago Mensualidad";
						$por_concepto="arancel";
						$aux_comentario="(arancel)";
						break;
					case"matricula":
						//$glosa_boleta="Pago Matricula";
						if(ES_MATRICULA_NUEVA($id_alumno, $id_contrato_cuota))
						{ $por_concepto="matricula_nueva";}
						else{$por_concepto="matricula";}
						$aux_comentario="(matricula)";
						break;	
					case"examen":
						$glosa_boleta="Pago cuota derecho a examen de titulo COD.:$id_cuota";
						$glosa_boleta.='Valor: $'.number_format($deudaXcuo,0,",",".").' - ';
						$glosa_boleta.=$semestre.' Semestre-'.$year.'[br]';
						$por_concepto="examen titulo";
						$comentario.="(examen Titulo)";
						break;			
				}
				if($aux_cantidad_pagar<=0)
				{ if(DEBUG){ echo"Cantidad a pagar: $aux_cantidad_pagar Menor o igual a 0, detener bucle<br>";} break;}
				
				//intereses
				if($aux_interes_x_atraso>0)
				{
					if(DEBUG){ echo"<br>Aplicar interes de $aux_interes_x_atraso<br>";}
					if($aux_cantidad_pagar>0)
					{
						if($aux_cantidad_pagar>=$aux_interes_x_atraso)
						{
							if(DEBUG){ echo"->Valor: $aux_cantidad_pagar alcanza a cubrir totalidad interes: $aux_interes_x_atraso<br>";}
							$aux_interes_x_atraso_a_pagar=$aux_interes_x_atraso;
							if(DEBUG){ echo"--->Aplicar cantidad a pagar $cantidad_pagar (>0) $aux_interes_x_atraso<br>";}
							$aux_cantidad_pagar=($aux_cantidad_pagar-$aux_interes_x_atraso_a_pagar);
							$TOTAL_interes_x_atraso=($TOTAL_interes_x_atraso+$aux_interes_x_atraso);
							
						}
						else
						{
							if(DEBUG){ echo"->Valor: $aux_cantidad_pagar no alcanza a cubrir totalidad interes: $aux_interes_x_atraso<br>";}
							$aux_interes_x_atraso_a_pagar=$aux_cantidad_pagar;
							$aux_cantidad_pagar=($aux_cantidad_pagar-$aux_interes_x_atraso_a_pagar);
							$TOTAL_interes_x_atraso=($TOTAL_interes_x_atraso+$aux_interes_x_atraso_a_pagar);
							
						}
						
							if(DEBUG){ echo"<br><strong>Registrando Interes X atraso...</strong><br>";}
							$cons_IA="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_multi_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$aux_interes_x_atraso_a_pagar', '$tipo_doc_pago','interes_x_atraso', '$sedeZ', '$movimiento', '$opcion_pago', $primer_vencimiento_cheque, '$id_cheque', '$id_multi_cheque', 'interes_x_atraso', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
						
						if(DEBUG){ echo"--->$cons_IA<br><br>";}
						else{ $conexion_mysqli->query($cons_IA)or die("Interes X atraso:".$conexion_mysqli->error);}
					}
					else
					{if(DEBUG){ echo"--->NO Aplicar cantidad a pagar $cantidad_pagar (<0) $aux_interes_x_atraso<br>";}}
				}
				else
				{ if(DEBUG){ echo"Intereses x atraso:0<br>";}}
				//cobranza
				if($aux_gastos_cobranza>0)
				{
					if(DEBUG){ echo"<br><strong>Aplicar Gastos cobranza de $aux_gastos_cobranza</strong><br>";}
					if($aux_cantidad_pagar>0)
					{
						if(DEBUG){ echo"--->Aplicar cantidad a pagar $cantidad_pagar (>0) $aux_gastos_cobranza<br>";}
						if($aux_cantidad_pagar>=$aux_gastos_cobranza)
						{
							if(DEBUG){ echo"Valor de: $aux_cantidad_pagar alcanza a cubrir Gasto cobranza de: $aux_gastos_cobranza<br>";}
							$aux_gastos_cobranza_a_pagar=$aux_gastos_cobranza;
							$aux_cantidad_pagar=($aux_cantidad_pagar-$aux_gastos_cobranza_a_pagar);
							$TOTAL_gastos_cobranza=($TOTAL_gastos_cobranza+$aux_gastos_cobranza_a_pagar);
							
						}
						else
						{
							if(DEBUG){ echo"Valor de: $aux_cantidad_pagar alcanza a cubrir Gasto cobranza de: $aux_gastos_cobranza<br>";}
							$aux_gastos_cobranza_a_pagar=$aux_cantidad_pagar;
							$aux_cantidad_pagar=($aux_cantidad_pagar-$aux_gastos_cobranza_a_pagar);
							$TOTAL_gastos_cobranza=($TOTAL_gastos_cobranza+$aux_gastos_cobranza_a_pagar);
						}
						
						$cons_GC="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_multi_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', $id_cta_cte, $aux_num_documento,'$codigo_item', '$fecha_pagoX', '$aux_gastos_cobranza_a_pagar', '$tipo_doc_pago','gastos_cobranza', '$sedeZ', '$movimiento', '$opcion_pago', $primer_vencimiento_cheque, '$id_cheque', '$id_multi_cheque', 'gastos_cobranza', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
					
						if(DEBUG){ echo"--->$cons_GC<br><br>";}
						else{ $conexion_mysqli->query($cons_GC)or die("Gastos Cobranza: ".$conexion_mysqli->error);}
					}
					else
					{if(DEBUG){ echo"--->NO Aplicar cantidad a pagar $cantidad_pagar (<0) $aux_gastos_cobranza<br>";}}
				}
				else
				{if(DEBUG){ echo"Gastos Cobranza:0<br>";}}
				
				
				if($aux_cantidad_pagar>=$deudaXcuota)
				{
					$condicion="S";
				   $valor_pago=$deudaXcuota;
				   $diferencia=($deudaXcuota-$valor_pago);
				   $TOTAL_cuotas+=$deudaXcuota;
				   if(DEBUG){
					echo"--->$aux_cantidad_pagar -> $deudaXcuota (>=) <br> V: $valor_cuota <br>D: $deudaXcuota<br>VP: $valor_pago<br>D: $diferencia<br>";
							}
						$comentario_label="$comentario V2 $aux_comentario";	
				}
				else
				{
				 	$condicion="A";
					$valor_pago=$aux_cantidad_pagar;
					$diferencia=($deudaXcuota-$valor_pago);
					$TOTAL_cuotas+=$aux_cantidad_pagar;
					if(DEBUG){
					echo"$aux_cantidad_pagar -> $deudaXcuota (<) <br> V: $valor_cuota <br>D: $deudaXcuota<br>VP: $valor_pago<br>D: $diferencia<br>";			}
						$comentario_label="Abono por Cuota V2 $aux_comentario";
				}
				//------------------------------------------------------------//
				$aux_cantidad_pagar-=$deudaXcuota;
				
				
				if($valor_pago>0)
				{
					if(DEBUG){ echo"Valor Pago (>0) Registrar Pago a Cuota<br>";}
				///////genero pago
				  $consP="INSERT INTO pagos (id_cuota, id_boleta, id_alumno, id_cta_cte, aux_num_documento, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_multi_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip) VALUES ('$id_cuota', '$id_boleta', '$id_alumno', $id_cta_cte, $aux_num_documento, '$codigo_item', '$fecha_pagoX', '$valor_pago', '$tipo_doc_pago','$comentario_label', '$sedeZ', '$movimiento', '$opcion_pago', $primer_vencimiento_cheque, '$id_cheque', '$id_multi_cheque', '$por_concepto', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip')";
				///////actualizo Cuota
				$consCX="UPDATE letras SET deudaXletra='$diferencia', pagada='$condicion', fecha_ultimo_pago='$fecha_pagoX' WHERE id='$id_cuota' LIMIT 1";
				
				
				  if(DEBUG){echo"$consP<br><br>$consCX<br><br>";}
				  else
				  {
					 // echo"--> $consP<br>";
				  	$conexion_mysqli->query($consP)or die("-->Pago".$conexion_mysqli->error);
					$conexion_mysqli->query($consCX)or die("--->Update Cuota".$conexion_mysqli->error);
				  }
				}
				else
				{
					if(DEBUG){ echo"Valor Pago (<0)NO Registrar Pago a Cuota<br>";}
				}
				
			}//fin while
			
			if(DEBUG){ echo"RESUMEN<br>Cuota: $TOTAL_cuotas<br>INteres:$TOTAL_interes_x_atraso<br>Gastos: $TOTAL_gastos_cobranza<br>";}
			
			//cuotas
			if($TOTAL_cuotas>0)
			{
				if(DEBUG){ echo"Agrego Valor de Cuotas<br>";}
				$glosa_boleta.="Arancel: ".$TOTAL_cuotas."[br]";
			}
			else
			{}
			
			//interes
			if($TOTAL_interes_x_atraso>0)
			{
				if(DEBUG){ echo"Agrego interes a glosa Boleta<br>";}
				$glosa_boleta.="Intereses x atraso: ".$TOTAL_interes_x_atraso."[br]";
			}
			else
			{if(DEBUG){ echo"NO Agrego interes a glosa Boleta<br>";}}
			
			//gastos
			if($TOTAL_gastos_cobranza>0)
			{
				if(DEBUG){ echo"Agrego gastos cobranza a glosa Boleta<br>";}
				$glosa_boleta.="Gastos Cobranza: ".$TOTAL_gastos_cobranza."[br]";
			}
			else
			{ if(DEBUG){ echo"NO Agrego Gastos Cobranza a glosa Boleta<br>";}}
			
			$error=0;
			
			
			$cons_UP_boleta="UPDATE boleta SET glosa='$glosa_boleta' WHERE id='$id_boleta' LIMIT 1";
			if(DEBUG){ echo"GLOSA BOLETA: $cons_UP_boleta<br><br>";}
			else{ $conexion_mysqli->query($cons_UP_boleta);}
		}
		else
		{
			$error=1;
		}	
		$sql_cuo->free();
		//-------------------------------------//
		include("../../../../funciones/VX.php");
		$evento="Pago Multiple de Cuotas num_cuotas afectadas: $num_cuotas id_alumno:$id_alumno";
		REGISTRA_EVENTO($evento);
		//-----------------------------------------------//
		
		$conexion_mysqli->close();
		if(DEBUG)
		{echo"$error<br>";}
		else
		{header("location: multi_pago3.php?error=$error&id_boleta=$id_boleta&num_cuotas=$num_cuotas&valor=$cantidad_pagar&semestre=$semestre&year=$year");}
	}
else
{ header("location: ../cuota1.php");}
////////////////////////////////////////////////////
function DATOS_CUOTA_FULL($id_cuota, $condicion="pagada")	
{
	require("../../../../funciones/conexion_v2.php");
	
	$cons="SELECT * FROM letras WHERE id='$id_cuota' LIMIT 1";
	if(DEBUG){ echo "F:>-> $cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die("DATOS_CUOTA_FULL".$conexion_mysqli->error);
	$D=$sql->fetch_assoc();
	
	//$id_contrato=$D["id_contrato"];
	$id_alumno=$D["idalumn"];
	$semestre=$D["semestre"];
	$year=$D["ano"];
	$tipo=$D["tipo"];
	$posicion_cuota=$D["numcuota"];
	$vencimiento=$D["fechavenc"];
	$fecha_vence=fecha_format($vencimiento);
	$sql->free();
	//////////////////////mas datos de Cuotas/////////////////////////////
		$cons_abc="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND semestre='$semestre' AND ano='$year'";
		if(DEBUG){echo"cantidad_cuotas.: $cons_abc<br>";}
		$sql_abc=$conexion_mysqli->query($cons_abc)or die("cons_abc".$conexion_mysqli->error);
		$D_abc=$sql_abc->fetch_row();
		$numero_total_cuotas=$D_abc[0];
		
		$sql_abc->free();
		$cons_abc2="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND semestre='$semestre' AND ano='$year' AND tipo='matricula'";
		if(DEBUG){echo"cantidad_cuotas.: $cons_abc2<br>";}
		$sql_abc2=$conexion_mysqli->query($cons_abc2)or die("cantidad cuotas matricula ".$conexion_mysqli->error);
		$D_abc2=$sql_abc2->fetch_row();
		$cantidad_cuotas_matricula=$D_abc2[0];
		if(empty($cantidad_cuotas_matricula))
		{ $cantidad_cuotas_matricula=0;}
		$sql_abc2->free();
		//////////////////////////////////////////////////////////////////////
		if($tipo=="cuota")
		 { $posicion_cuota+=$cantidad_cuotas_matricula; }
		$posicion_cuota_f=$posicion_cuota."/".$numero_total_cuotas;
		if(DEBUG){ echo"P final.: $posicion_cuota_f<br>";}
	switch($condicion)	
	{
		case"pagada":
			$ARMA_GLOSA='CUOTA: '.$posicion_cuota_f.' VENCE:'. $fecha_vence.'[br]';
			break;
		case"abonada":
			$ARMA_GLOSA='CUOTA: '.$posicion_cuota_f.' VENCE:'. $fecha_vence.'*[br]';
			break;	
	}	
	if(DEBUG){ echo $ARMA_GLOSA."<br>";}
	$conexion_mysqli->close();
	return($ARMA_GLOSA);
}
//--------------->GENERA multi cheque
function GENERA_MULTI_CHEQUE($id_alumno, $sede, $total, $total_hoy, $id_cheques, $fecha_pago, $cantidad_cheques)
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){echo"----GENERANDO multi_cheque-----<br>";}
	$maximo_numero_cheques=6;//maximo_numero_cheques
	
	$fecha_generacion=date("Y-m-d");
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$campos="id_alumno, sede, total, total_al_dia, cantidad_cheques, id_cheque_1, id_cheque_2, id_cheque_3, id_cheque_4, id_cheque_5, id_cheque_6, fecha, fecha_generacion, cod_user";
	
	$valores="'$id_alumno', '$sede', '$total', '$total_hoy', '$cantidad_cheques'";
	for($i=0;$i<$maximo_numero_cheques;$i++)
	{
		if(isset($id_cheques[$i]))
		{$aux_id_cheque=$id_cheques[$i];}
		else{ $aux_id_cheque=0;}
		
		$valores.=", '$aux_id_cheque'";
	}
	$valores.=", '$fecha_pago', '$fecha_generacion', '$id_usuario_activo'";
	
	$cons_IN_MCH="INSERT INTO registro_multi_cheque ($campos) VALUES($valores)";
	if(DEBUG){ echo"--->$cons_IN_MCH<br>";}
	if(DEBUG)
	{ $id_multi_cheque=4;}
	else
	{
		$conexion_mysqli->query($cons_IN_MCH)or die("multi_cheque".$conexion_mysqli->error);
		$id_multi_cheque=$conexion_mysqli->insert_id;
	}
	if(DEBUG){echo"----FIN GENERANDO multi_cheque-----<br>";}
	$conexion_mysqli->close();
	return($id_multi_cheque);
}
?>