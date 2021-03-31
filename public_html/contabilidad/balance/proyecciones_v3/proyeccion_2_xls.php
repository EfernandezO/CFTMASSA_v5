<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG", false);
	set_time_limit(120);
//-----------------------------------------//	
$continuar=false;
$R2=array();
if($_GET)
{
	if(DEBUG){ var_dump($_GET); echo"<br>";}
	$ARRAY_TIPO_MOROSIDAD=unserialize(urldecode($_GET["morosidad"]));
	
	////////Variables de formulario/////////
	$year=$_GET["year"];
	$year_ingreso=$_GET["year_ingreso"];
	//$semestre=$_POST["semestre"];
	$sede=$_GET["sede"];
	
	$id_carrera=$_GET["id_carrera"];
	$nivel=$_GET["nivel"];
	$jornada=$_GET["jornada"];
	
	$por_concepto=$_GET["por_concepto"];
	$situacion_academica=$_GET["situacion_academica"];
	$continuar=true;
}
//--------------------------------------------------------------------------------------///
if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	//------------------------------------------//
	require("../../../../funciones/VX.php");
	$evento="Revisa Informe Proyecciones a XLS";
	REGISTRA_EVENTO($evento);
	//--------------------------------------------//
	
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe_x_fecha.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	
	if($por_concepto!="todos")
	{ $condicion_por_concepto="AND pagos.por_concepto='$por_concepto'";}
	else
	{ $condicion_por_concepto="";}
	
	if($sede!="0"){ $condicion_sede="AND alumno.sede='$sede'";}
	else{ $condicion_sede="";}
	
	if($nivel!="0"){ $condicion_nivel="AND alumno.nivel='$nivel'";}
	else{ $condicion_nivel="";}
	
	if($jornada!="0"){ $condicion_jornada="AND alumno.jornada='$jornada'";}
	else{ $condicion_jornada="";}
	
	if($situacion_academica!="0"){ $condicion_situacion_academica="AND alumno.situacion='$situacion_academica'";}
	else{ $condicion_situacion_academica="";}
	
	if($year_ingreso!="0"){$condicion_year_ingreso="AND alumno.ingreso='$year_ingreso'";}
	else{ $condicion_year_ingreso="";}
	
	if($year!="0"){ $condicion_year_cuotas="AND letras.ano='$year'";}
	else{ $condicion_year_cuotas="";}
	
	echo"<strong>Proyecciones Año $year - Sede: $sede</br>Año ingreso alumno: $year_ingreso Nivel: $nivel - Jornada: $jornada</strong><br><strong>Tipo de Morosidad:</strong> ";
	foreach($ARRAY_TIPO_MOROSIDAD as $j=>$k)
	{echo"$k ";}
	echo"<br><strong>Situacion Academica:</strong> $situacion_academica<br>Fecha generacion: ".date("d/m/Y")."<br><br>";
	//////-----------------------------/////
	
	//---------------------------------------------------------///
	if($year==0){ $year_proyeccion=$year_actual;}
	else{ $year_proyeccion=$year;}
	
	$ARRAY_MESES_LABEL[1]="Ene";
	$ARRAY_MESES_LABEL[2]="Feb";
	$ARRAY_MESES_LABEL[3]="Mar";
	$ARRAY_MESES_LABEL[4]="Abr";
	$ARRAY_MESES_LABEL[5]="May";
	$ARRAY_MESES_LABEL[6]="Jun";
	$ARRAY_MESES_LABEL[7]="Jul";
	$ARRAY_MESES_LABEL[8]="Ago";
	$ARRAY_MESES_LABEL[9]="Sep";
	$ARRAY_MESES_LABEL[10]="Oct";
	$ARRAY_MESES_LABEL[11]="Nov";
	$ARRAY_MESES_LABEL[12]="Dic";
	
	
	$numero_meses_proyectado=14;
	$mes=0;
	for($m=1;$m<=$numero_meses_proyectado;$m++)
	{
		if($m%13==0){$mes=0; $year_proyeccion++;}
		$mes++;
		
		
		if($mes<10)
		{$m_label="0".$mes;}
		else
		{$m_label=$mes;}
		
		
		switch($mes)
		{
			case"1":
				$dia_fin="31";
				break;
			case"2":
				if($year_proyeccion%4==0)
				{$dia_fin="29";}
				else
				{ $dia_fin="28";}
				break;
			case"3":
				$dia_fin="31";
				break;
			case"4":
				$dia_fin="30";
				break;		
			case"5":
				$dia_fin="31";
				break;
			case"6":
				$dia_fin="30";
				break;
			case"7":
				$dia_fin="31";
				break;
			case"8":
				$dia_fin="31";
				break;
			case"9":
				$dia_fin="30";
				break;
			case"10":
				$dia_fin="31";
				break;
			case"11":
				$dia_fin="30";
				break;
			case"12":
				$dia_fin="31";
				break;									
		}
		
		$array_vencimientos[$m]["inicio"]="$year_proyeccion-$m_label-01";
		$array_vencimientos[$m]["fin"]="$year_proyeccion-$m_label-$dia_fin";
		$array_vencimientos[$m]["year_proyectado"]=$year_proyeccion;
	}
	
	if(DEBUG){var_dump($array_vencimientos);}
	///----------------lleno array de carreras-----------------//
	
	
	
 	$cons_carreras="SELECT DISTINCT(id_carrera) FROM contratos2 WHERE ano='$year' AND sede='$sede' ORDER by id_carrera";
	
	if(DEBUG){ echo"<br><br>CARRERAS--->$cons_carreras<br><br>";}
	$sqlx1=$conexion_mysqli->query($cons_carreras)or die("Carreras ".$conexion_mysqli->error);

	$ARRAY_CARRERA_F=array();
	while($AC=$sqlx1->fetch_row())
	{
		$aux_id_carrera=$AC[0];
		$aux_carrera=NOMBRE_CARRERA($aux_id_carrera);
		$ARRAY_CARRERA_F[$aux_id_carrera]=$aux_carrera;
	}
	$sqlx1->free();
	//-------------------------------------------------------///
	
	$ARRAY_CANTIDAD_ALUMNOS=array();
	foreach($ARRAY_CARRERA_F as $aux_id_carrera => $aux_nombre_carrera)
	{
		if(DEBUG){ echo"----->CARRERA: $aux_id_carrera $aux_nombre_carrera<br>";}
		////busco Alumno Morosos en la carrera actual
			$cons_alumno_cuota="SELECT DISTINCT(idalumn) FROM letras INNER JOIN  alumno ON letras.idalumn = alumno.id WHERE tipo='cuota' $condicion_sede AND alumno.id_carrera='$aux_id_carrera' $condicion_nivel $condicion_jornada $condicion_situacion_academica $condicion_year_ingreso $condicion_year_cuotas  ORDER by idalumn";
				if(DEBUG){ echo"<strong>ALUMNO CON CUOTA:</strong> $cons_alumno_cuota<br>";}
			$sql_alumno_cuota=$conexion_mysqli->query($cons_alumno_cuota)or die("Moroso".$conexion_mysqli->error);
			$num_alumno_cuota=$sql_alumno_cuota->num_rows;
			if(DEBUG){ echo"<strong>NUM ALUMNO CUOTA:</strong> $num_alumno_cuota<br>";}
			$primera_vuelta_alumno=true;
			$condicion_id_alumno="";
			$numero_alumno_cumple_condicion=0;
			$hay_alumnos_para_revisar=false;
			if($num_alumno_cuota>0)
			{
				while($ACX=$sql_alumno_cuota->fetch_row())
				{
					$aux_id_alumno=$ACX[0];
					$aux_dias_morosidad=DIAS_MOROSIDAD($aux_id_alumno);
					$aux_tipo_morosidad=TIPO_MOROSIDAD($aux_dias_morosidad);
					if(DEBUG){ echo"ID ALUMNO: $aux_id_alumno ---> dias morosidad $aux_dias_morosidad<br>TIPO MOROSIDAD: $aux_tipo_morosidad<br>";}
					
					if(in_array($aux_tipo_morosidad, $ARRAY_TIPO_MOROSIDAD))
					{
						$hay_alumnos_para_revisar=true;
						$numero_alumno_cumple_condicion++;
						if(DEBUG){ echo"<strong>DENTRO de RANGO DE MOROSIDAD</strong><br>";}
						if($primera_vuelta_alumno)
						{
							$primera_vuelta_alumno=false;
							$condicion_id_alumno.="$aux_id_alumno";
						}
						else
						{ $condicion_id_alumno.=", $aux_id_alumno";}
						
					}
					else
					{
						if(DEBUG){ echo"<strong>FUERA de RANGO DE MOROSIDAD</strong><br>";}
					}
				}
			}
			else
			{ if(DEBUG){ echo"SIN ALUMNOS Con CUOTA en esta Carrera<br>";}}
			
			$ARRAY_CANTIDAD_ALUMNOS[$aux_id_carrera]=$numero_alumno_cumple_condicion;
			
			$sql_alumno_cuota->free();
			
			if(DEBUG){ echo"_______>$condicion_id_alumno<br>Numero Alumno con morosidad [$numero_alumno_cumple_condicion]<br>";}
			////////////////////////////////////////////////////////////////////////////
		for($x=1;$x<=$numero_meses_proyectado;$x++)
		{
			if(DEBUG){ echo" =====><strong>MES: $x</strong><br>";}
			$fecha_inicio=$array_vencimientos[$x]["inicio"];
			$fecha_fin=$array_vencimientos[$x]["fin"];
			$aux_year_proyectado=$array_vencimientos[$x]["year_proyectado"];
			//////////////////////////////////////
			///VALORES ASIGNADOS DE BNM
			
			$cons_becas="SELECT SUM(aporte_beca_nuevo_milenio), SUM(aporte_beca_excelencia) FROM contratos2 WHERE ano='$year' AND id_carrera='$aux_id_carrera' AND sede='$sede' AND fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin' AND condicion IN ('OK', 'OLD')";
			$sqli=$conexion_mysqli->query($cons_becas)or die($conexion_mysqli->error);
			$BA=$sqli->fetch_row();
				$aux_TOTAL_aporte_BNM=$BA[0];
				if(empty($aux_TOTAL_aporte_BNM)){ $aux_TOTAL_aporte_BNM=0;}
				$aux_TOTAL_EXCELENCIA=$BA[1];
				if(empty($aux_TOTAL_EXCELENCIA)){ $aux_TOTAL_EXCELENCIA=0;}
			$sqli->free();	
			if(DEBUG){ echo"Contratos con Beca : $cons_becas<br> Aporte BNM: $aux_TOTAL_aporte_BNM<br> Aporte Excelecia:$aux_TOTAL_EXCELENCIA<br>";}
			
			$RESULTADO[$aux_id_carrera][$x]["BNM"]=$aux_TOTAL_aporte_BNM;	
			$RESULTADO[$aux_id_carrera][$x]["BET"]=$aux_TOTAL_EXCELENCIA;	
			$R2[$aux_id_carrera]["BNM"][$x]=$aux_TOTAL_aporte_BNM;	
			$R2[$aux_id_carrera]["BET"][$x]=$aux_TOTAL_EXCELENCIA;	
			
			//////////////////////////VALORES POR MES Y CARRERA DE LINEA CREDITO
			$ingresos_del_mes=0;
			$valor_esperado=0;
			$deuda_cuota_actual=0;
			$suma_pagos_cuotas_periodo=0;
			$suma_pagos_fuera_periodo=0;
			$aux_pago_cuota=0;
			
			if($hay_alumnos_para_revisar)
			{
				if(DEBUG){ echo"Hay Alumnos Para Revisar<br>";}
				
				$cons_cuotas="SELECT id, valor, deudaXletra FROM letras  WHERE idalumn IN($condicion_id_alumno) AND ano='$year' AND tipo='cuota' AND letras.fechavenc BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			
				if(DEBUG){echo"ESPERADO (busco cuotas)---> $cons_cuotas<br>";}
					$sql_cuotas=$conexion_mysqli->query($cons_cuotas)or die("cuotas".$conexion_mysqli->error);
					$num_cuotas=$sql_cuotas->num_rows;
					if(DEBUG){echo"<br>Num cuotas: $num_cuotas<br><br>";}
					if($num_cuotas>0)
					{
						while($C=$sql_cuotas->fetch_assoc())
						{
							$aux_valor_cuota=$C["valor"];
							$aux_id_cuota=$C["id"];
							$aux_deuda_cuota=$C["deudaXletra"];
							
							$valor_esperado+=$aux_valor_cuota;
							$deuda_cuota_actual+=$aux_deuda_cuota;
							
							$time_inicio=strtotime($fecha_inicio);
							$time_fin=strtotime($fecha_fin);
							
							//busco pagos de estas cuotas en este periodo
							$cons_pagos_cuotas="SELECT valor, fechapago FROM pagos WHERE id_cuota='$aux_id_cuota' AND por_concepto='arancel'";
							$sqli_pagos_cuota=$conexion_mysqli->query($cons_pagos_cuotas)or die("Pagos cuota". $conexion_mysqli->error);
							$num_pagos=$sqli_pagos_cuota->num_rows;
							if(DEBUG){ echo"---->$cons_pagos_cuotas<br>N.$num_pagos<br>Periodo consulta time[$time_inicio - $time_fin]<br>";}
							
							if($num_pagos>0)
							{
								while($Px=$sqli_pagos_cuota->fetch_assoc())
								{
									$aux_pago_cuota=$Px["valor"];
									$aux_fecha_pago=$Px["fechapago"];
									$time_fecha_pago=strtotime($aux_fecha_pago);
									if(DEBUG){ echo"--->$aux_pago_cuota fecha pago time: $time_fecha_pago -> ";}
									
									if(($time_fecha_pago>=$time_inicio)and($time_fecha_pago<=$time_fin))
									{$suma_pagos_cuotas_periodo+=$aux_pago_cuota; if(DEBUG){echo"[Pago en Periodo]<br>";}}
									else{$suma_pagos_fuera_periodo+=$aux_pago_cuota; if(DEBUG){echo"[Pago fuera Periodo]<br>";}}
									
								}
							}
							$sqli_pagos_cuota->free();
						}
					}
					$sql_cuotas->free();
					if(DEBUG){echo"VALOR ESPERADO: $valor_esperado<br><br>";}
					//---------------------------------------------------------------//
					$cons_pagos_cuotas_otros_meses="SELECT SUM(pagos.valor) FROM pagos INNER JOIN alumno ON pagos.id_alumno=alumno.id WHERE pagos.por_concepto='arancel' AND alumno.id IN($condicion_id_alumno) AND pagos.fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
					$sqli_pagos_otros_meses=$conexion_mysqli->query($cons_pagos_cuotas_otros_meses)or die("PAGOS TOTALES ".$conexion_mysqli->error);
					$POM=$sqli_pagos_otros_meses->fetch_row();
						$ingresos_del_mes=$POM[0];
						if(empty($ingresos_del_mes)){$ingresos_del_mes=0;}
					$sqli_pagos_otros_meses->free();	
					if(DEBUG){ echo"---><strong>Pagos de cuotas de otros meses y year pagadas en el periodo:</strong> $cons_pagos_cuotas_otros_meses<br>pagos otros meses: $ingresos_del_mes<br>";}
					//---------------------------------------------------------------//
			}
			else
			{ if(DEBUG){ echo"No hay Alumnos Para Revisar (esperado)<br>";}}
			
			if(DEBUG){echo"<strong>Valor -> $valor_esperado</strong><br>";}
			
			///ingresos del realizados por estos alumnos en este mes de cualquier cuota
			$RESULTADO[$aux_id_carrera][$x]["INGRESOS_MES"]=$ingresos_del_mes;	
			$R2[$aux_id_carrera]["INGRESOS_MES"][$x]=$ingresos_del_mes;
			///valor de cuotas
			$RESULTADO[$aux_id_carrera][$x]["ESPERADO"]=$valor_esperado;	
			$R2[$aux_id_carrera]["ESPERADO"][$x]=$valor_esperado;
			///deudaXletra de cuotas
			$RESULTADO[$aux_id_carrera][$x]["DEUDA_ACTUAL"]=$deuda_cuota_actual;	
			$R2[$aux_id_carrera]["DEUDA_ACTUAL"][$x]=$deuda_cuota_actual;
			///pagos realizados a cuota fuera del periodo
			$RESULTADO[$aux_id_carrera][$x]["PAGO_CUOTA"]=$suma_pagos_fuera_periodo;	
			$R2[$aux_id_carrera]["PAGO_CUOTA"][$x]=$suma_pagos_fuera_periodo;
			///pagos realizados a cuota dentro del periodo
			$RESULTADO[$aux_id_carrera][$x]["REAL"]=$suma_pagos_cuotas_periodo;	
			$R2[$aux_id_carrera]["REAL"][$x]=$suma_pagos_cuotas_periodo;
			/////////////////////-------------------------------------/////////////////////
			
		}//fin for meses
		
	}//fin foreach
	//var_export($R2);
	$contador=1;
	$graficar=false;
	$mostrar_tabla=true;
	include("../../../../funciones/G_chart.php");
	$max_dato=0;
	$total_esperadoT_final=0;
	$total_realT_final=0;
	$total_ingresos_mes=0;
	$linea_1T="";
	$linea_2T="";
	$concat_esperadoT="";
	$concat_realT="";
	$TOTALIZADO=array();	
	
	foreach($R2 as $carreraX => $valor)
	{
		$aux_nombre_carrera=$ARRAY_CARRERA_F[$carreraX];
		$aux_cantidad_alumno=$ARRAY_CANTIDAD_ALUMNOS[$carreraX];
		
		$aux_esperado=$valor["ESPERADO"];
		$aux_deuda_actual=$valor["DEUDA_ACTUAL"];
		$aux_real=$valor["REAL"];
		
		$aux_ingresos_mes=$valor["INGRESOS_MES"];
		$aux_pago_cuota=$valor["PAGO_CUOTA"];
		
		$aux_BNM=$valor["BNM"];
		$aux_BET=$valor["BET"];
		
		$concat_esperado="";
		$concat_real="";
		$AUX=true;
		$max_dato=0;
		$total_x_carrera_E=0;
		$total_x_carrera_R=0;
		
		$total_BNM=0;
		$total_BET=0;
		$total_pago_cuota=0;
		$total_deuda_actual=0;
		$total_ingresos_mes=0;
		
		$tabla='<table border="1" width="80%">
				<tr>
					<td colspan="'.($numero_meses_proyectado+2).'"><strong>'.utf8_decode($aux_nombre_carrera).'</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>';
					$aux_mes=0;
					for($i=1;$i<=$numero_meses_proyectado;$i++)
					{
						if($i%13==0){$aux_mes=0;}
						$aux_mes++;
						$tabla.='<td><em>'.$ARRAY_MESES_LABEL[$aux_mes].'</em></td>';
					}
					
				$tabla.='<td><em>Total</em></td></tr>';
				
				$fila1="<tr><td><em>Ingreso Esperado</em></td>";
				$fila2='<tr><td bgcolor="#AAFFAA"><em><a title="Pagos realizados en este mes a cuotas del mismo periodo">Ingresos X cuotas en Periodo</a></em></td>';
				$fila3="<tr><td><em>Ingreso BNM</em></td>";
				$fila4="<tr><td><em>Ingreso BET</em></td>";
				$fila5='<tr><td bgcolor="#FFFFAA"><em><a title="Pagos realizados a cuotas de este periodo en otros meses">Ingresos X cuota fuera de Periodo</a></em></td>';
				$fila6='<tr><td><em><a title="Deuda Actual X cuotas del Mes">Deuda Actual</a></em></td>';
				$fila7='<tr><td bgcolor="#FFAAAA"><em><a title="Pagos realizados por estos alumnos a distintas cuotas en este mes">Pago Total del Mes</a></em></td>';
				
			
		for($m=1;$m<=$numero_meses_proyectado;$m++)
		{
			$esperado=$aux_esperado[$m];
			$deuda_cuota=$aux_deuda_actual[$m];
			$ingreso_total_mes=$aux_ingresos_mes[$m];
			$real=$aux_real[$m];
			
			$bnm=$aux_BNM[$m];
			$bet=$aux_BET[$m];
			$pago_cuota_mes=$aux_pago_cuota[$m];
			
			$fila1.='<td>'.$esperado.'</td>';
			$fila2.='<td bgcolor="#AAFFAA">'.$real.'</td>';
			$fila3.='<td>'.$bnm.'</td>';
			$fila4.='<td>'.$bet.'</td>';
			$fila5.='<td bgcolor="#FFFFAA">'.$pago_cuota_mes.'</td>';
			$fila6.='<td>'.$deuda_cuota.'</td>';
			$fila7.='<td bgcolor="#FFAAAA">'.$ingreso_total_mes.'</td>';
			
			
			$total_x_carrera_E+=$esperado;
			$total_deuda_actual+=$deuda_cuota;
			$total_x_carrera_R+=$real;
			$total_ingresos_mes+=$ingreso_total_mes;
			$total_BNM+=$bnm;
			$total_BET+=$bet;
			$total_pago_cuota+=$pago_cuota_mes;			
			
			if(isset($TOTALIZADO["ingreso_total"][$m]))
			{$TOTALIZADO["ingreso_total"][$m]+=$ingreso_total_mes;}
			else
			{$TOTALIZADO["ingreso_total"][$m]=$ingreso_total_mes;}
			
			if(isset($TOTALIZADO["esperado"][$m]))
			{$TOTALIZADO["esperado"][$m]+=$esperado;}
			else
			{$TOTALIZADO["esperado"][$m]=$esperado;}
			
			if(isset($TOTALIZADO["deuda_actual"][$m]))
			{$TOTALIZADO["deuda_actual"][$m]+=$deuda_cuota;}
			else
			{$TOTALIZADO["deuda_actual"][$m]=$deuda_cuota;}
			
			if(isset($TOTALIZADO["real"][$m]))
			{$TOTALIZADO["real"][$m]+=$real;}
			else
			{$TOTALIZADO["real"][$m]=$real;}
			
			if(isset($TOTALIZADO["bnm"][$m]))
			{$TOTALIZADO["bnm"][$m]+=$bnm;}
			else
			{$TOTALIZADO["bnm"][$m]=$bnm;}
			
			if(isset($TOTALIZADO["bet"][$m]))
			{$TOTALIZADO["bet"][$m]+=$bet;}
			else
			{$TOTALIZADO["bet"][$m]=$bet;}
			
			if(isset($TOTALIZADO["pago_cuota"][$m]))
			{$TOTALIZADO["pago_cuota"][$m]+=$pago_cuota_mes;}
			else
			{$TOTALIZADO["pago_cuota"][$m]=$pago_cuota_mes;}
			
			///buscando dato mayor
			if($esperado>$max_dato)
			{$max_dato=$esperado;}
			if($real>$max_dato)
			{$max_dato=$real;}
			///////////////////////
			
			if($AUX)
			{$concat_esperado.=$esperado;}
			else
			{$concat_esperado.=",".$esperado;}
			
			if($AUX)
			{
				$concat_real.=$real;
				$AUX=false;
			}
			else
			{$concat_real.=",".$real;}
			
			
		}
		
		$fila1.='<td>'.$total_x_carrera_E.'</td></tr>';
		$fila2.='<td bgcolor="#AAFFAA">'.$total_x_carrera_R."</td></tr>";
		$fila3.='<td>'.$total_BNM.'</td></tr>';
		$fila4.="<td>".$total_BET."</td></tr>";
		$fila5.='<td bgcolor="#FFFFAA">'.$total_pago_cuota."</td></tr>";
		$fila6.="<td>".$total_deuda_actual."</td></tr>";
		$fila7.='<td bgcolor="#FFAAAA">'.$total_ingresos_mes."</td></tr>";
		
		$tabla.=$fila1.$fila3.$fila4.$fila2.$fila5.$fila6.$fila7."</table>";
		
		/*echo'<div id="CollapsiblePanel'.$contador.'" class="CollapsiblePanel">
				  <div class="CollapsiblePanelTab Estilo1" tabindex="'.($contador-1).'">'.$carreraX.' '.$aux_nombre_carrera.'</div>
    <div class="CollapsiblePanelContent">';*/
				  
		//tabla resumen de datos		  
		if($mostrar_tabla)
		{echo $tabla.'<br>';}	
		$contador++;		
		///////////////////////////ARRAY para GRAFICO////////////////////////////////////
		$array_grafico["datos"][]=$concat_esperado;
		$array_grafico["rango_X"]="|E|F|M|A|M|J|J|A|S|O|N|D|";
		$array_grafico["datos"][]=$concat_real;
		$array_grafico["tipo"]="lc";//"bvs";"lc"
		$array_grafico["rango_Y"]="|E|F|M|A|M|J|J|A|S|O|N|D";
		$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico["dato_max"]=$max_dato;
		$array_grafico["etiqueta_izquierda"]="Ingresos";
		$array_grafico["etiqueta_inferior"]="Meses";
		$array_grafico["titulo"]=$aux_nombre_carrera;
		$array_grafico["simbologia"]="esperado|real";
		$array_grafico["colores_lineas_hex"]="F1A100,1F1F00";
		$array_grafico["color_titulo_hex"]="F10000";
		$array_grafico["size_titulo"]=16;
		///////////////----------------------------------------------------///////////////
		if($graficar)
		{GRAFICO_GOOGLE($array_grafico);}	
		//echo'</div></div>';
		unset($array_grafico);
	}///fin recorreo carrera
	////resumen final
	/*echo'<div id="CollapsiblePanel'.$contador.'" class="CollapsiblePanel">
				  <div class="CollapsiblePanelTab Estilo1" tabindex="'.($contador-1).'">Total</div>
    <div class="CollapsiblePanelContent">';*/
	
	$tabla_final='<table border="1" width="80%">
	<tr>
	<td>&nbsp;</td>';
	
	
	$aux_mes=0;
	for($i=1;$i<=$numero_meses_proyectado;$i++)
	{
		if($i%13==0){$aux_mes=0;}
		$aux_mes++;
		$tabla_final.='<td><em>'.$ARRAY_MESES_LABEL[$aux_mes].'</em></td>';
	}
	
	$tabla_final.='<td>Total</td></tr>';
	
	$esperadoT=$TOTALIZADO["esperado"];
	$realT=$TOTALIZADO["real"];
	$bnmT=$TOTALIZADO["bnm"];
	$betT=$TOTALIZADO["bet"];
	$pago_cuota_mesT=$TOTALIZADO["pago_cuota"];
	$deuda_actualT=$TOTALIZADO["deuda_actual"];
	$ingreso_totalT=$TOTALIZADO["ingreso_total"];
	
	$AUXT=true;
	$max_datoT=0;
	$total_bnmT_final=0;
	$total_betT_final=0;
	$total_deuda_actual_final=0;
	$linea_3T="";
	$linea_4T="";
	$linea_5T="";
	$linea_6T="";
	$linea_7T="";
	$linea_8T="";
	$total_IRF_final=0;
	$total_pago_cuota_final=0;
	$total_ingreso_total_final=0;
	
	for($mx=1;$mx<=$numero_meses_proyectado;$mx++)
	{
		
		/////
		//ingreso real por cuotas de todos los peridos cancelados x mes
		$fecha_inicio=$array_vencimientos[$mx]["inicio"];
		$fecha_fin=$array_vencimientos[$mx]["fin"];
		$cons_IRF="SELECT SUM(valor) FROM pagos WHERE sede='$sede' AND fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' AND por_concepto='$por_concepto'";
		if(DEBUG){ echo"---->$cons_IRF<br>";}
		$sqliZ=$conexion_mysqli->query($cons_IRF)or die($conexion_mysqli->error);
		$IRF=$sqliZ->fetch_row();
			$ingreso_real_del_mes=$IRF[0];
		$sqliZ->free();	
		if(empty($ingreso_real_del_mes)){$ingreso_real_del_mes=0;}
		
		$total_IRF_final+=$ingreso_real_del_mes;
		//------------------------------------------------------------------/
		
		
		
		$esperadoTx=$esperadoT[$mx];
		$realTx=$realT[$mx];
		$bnmTx=$bnmT[$mx];
		$betTx=$betT[$mx];
		$pago_cuota_mesTx=$pago_cuota_mesT[$mx];
		$deuda_actualTx=$deuda_actualT[$mx];
		$ingreso_totalTx=$ingreso_totalT[$mx];
		
		
		$total_esperadoT_final+=$esperadoTx;
		$total_realT_final+=$realTx;
		$total_bnmT_final+=$bnmTx;
		$total_betT_final+=$betTx;
		$total_pago_cuota_final+=$pago_cuota_mesTx;
		$total_deuda_actual_final+=$deuda_actualTx;
		$total_ingreso_total_final+=$ingreso_totalTx;
		
		$linea_1T.='<td>'.$esperadoTx.'</td>';
		$linea_2T.='<td>'.$realTx.'</td>';
		
		$linea_3T.='<td>'.$bnmTx.'</td>';
		$linea_4T.='<td>'.$betTx.'</td>';
		
		$linea_5T.='<td>'.$ingreso_real_del_mes.'</td>';
		$linea_6T.='<td>'.$pago_cuota_mesTx.'</td>';
		$linea_7T.='<td>'.$deuda_actualTx.'</td>';
		$linea_8T.='<td>'.$ingreso_totalTx.'</td>';
		
		/////buiscando el mayor
		if($esperadoTx>$max_datoT)
			{$max_datoT=$esperadoTx;}
		if($realTx>$max_datoT)
			{$max_datoT=$realTx;}
		///////////////////////
		//echo"-> $max_datoT<br>";
		if($AUXT)
			{$concat_esperadoT.=$esperadoTx;}
			else
			{$concat_esperadoT.=",".$esperadoTx;}
			
			if($AUXT)
			{
				$concat_realT.=$realTx;
				$AUXT=false;
			}
			else
			{$concat_realT.=",".$realTx;}
	}
		$tabla_final.='<tr><td><em><a href="#" title="Suma de VALOR de Cuotas que vencen en este MES">Ingreso Esperado</a></em></td>'.$linea_1T.'<td>'.$total_esperadoT_final.'</td></tr>';
		
		$tabla_final.='<tr><td><em><a href="#" title="Suma de aporte BNM en contratos generados en este MES">Ingreso BNM</a></em></td>'.$linea_3T.'<td>'.$total_bnmT_final.'</td></tr>';
		
		$tabla_final.='<tr><td><em><a href="#" title="Suma de aporte BET en contratos generados en este MES">Ingreso BET</a></em></td>'.$linea_4T.'<td>'.$total_betT_final.'</td></tr>';
		
		$tabla_final.='<tr bgcolor="#AAFFAA"><td><em><a href="#" title="Suma de pagos realizados en este mes a Cuotas del mismo mes MES">Ingreso X cuotas en Periodo</a></em></td>'.$linea_2T.'<td>'.$total_realT_final.'</td></tr>';
		
		$tabla_final.='<tr bgcolor="#FFFFAA"><td><em><a href="#" title="Suma de pagos realizados a cuotas de este mes en otros periodos">Ingresos X cuota Fuera de Periodo</a></em></td>'.$linea_6T.'<td>'.$total_pago_cuota_final.'</td></tr>';
		
		$tabla_final.='<tr><td><em><a href="#" title="">Deuda Actual</a></em></td>'.$linea_7T.'<td>'.$total_deuda_actual_final.'</td></tr>';
		$tabla_final.='<tr bgcolor="#FFAAAA"><td><em><a href="#" title="Total de ingresos realizados por estos alumnos a cuotas en este periodo">Ingreso Total de Alumnos</a></em></td>'.$linea_8T.'<td>'.$total_ingreso_total_final.'</td></tr>';
		
		$tabla_final.='<tr><td><em><a href="#" title="Suma de pagos de cuotas realizados este MES">Ingreso Real</a></em></td>'.$linea_5T.'<td>'.$total_IRF_final.'</td></tr>';
		
		if($mostrar_tabla)
		{echo $tabla_final;}	
		///////////////////////////ARRAY para GRAFICO////////////////////////////////////
		$array_grafico["datos"][]=$concat_esperadoT;
		$array_grafico["rango_X"]="|E|F|M|A|M|J|J|A|S|O|N|D|";
		$array_grafico["datos"][]=$concat_realT;
		$array_grafico["tipo"]="lc";//"bvs";"lc"
		$array_grafico["rango_Y"]="|E|F|M|A|M|J|J|A|S|O|N|D";
		$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico["dato_max"]=$max_datoT;
		$array_grafico["etiqueta_izquierda"]="Ingresos";
		$array_grafico["etiqueta_inferior"]="Meses";
		$array_grafico["titulo"]="Total";
		$array_grafico["simbologia"]="esperado|real";
		$array_grafico["colores_lineas_hex"]="FE0002,3F863A";
		$array_grafico["color_titulo_hex"]="F10000";
		$array_grafico["size_titulo"]=20;
		///////////////----------------------------------------------------///////////////
		if($graficar)
		{GRAFICO_GOOGLE($array_grafico);}	
		unset($array_grafico);
		///////////////////////

//////////fin resumen
}//fin post
else{ echo"Sin datos";}
?>
