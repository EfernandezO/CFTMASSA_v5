<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);

	set_time_limit(90);
	require("../../../../funciones/conexion_v2DKIM<h2></h2>.php");
	include("../../../../funciones/funcion.php");
		if(DEBUG){ var_export($_POST);}
		$year=$_GET["year"];
		$mes=$_GET["mes"];
		$sede=$_GET["sede"];
		$ver_todos_los_diasX=$_GET["ver_todos_los_dias"];
		
		if($ver_todos_los_diasX=="no")
		{ $ver_todos_los_dias=false;}
		else
		{ $ver_todos_los_dias=true;}
		$ultimo_dia_mes=MAX_DIA_MES($mes,$year);
		
		if(DEBUG)
			{ var_export($_GET);}
			else
			{
				header('Content-type: application/vnd.ms-excel');
				header("Content-Disposition: attachment; filename=libro_ventas_$mes_$year_$sede.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
			}
$color_celda_1='bgcolor="#e5e5e5"';

$tabla='<table border="1" >
<thead>
  <tr>
    <th '.$color_celda_1.' rowspan="2">Dia</th>
    <th '.$color_celda_1.' colspan="2">Rango</th>
    <th '.$color_celda_1.' rowspan="2">Matricula</th>
    <th '.$color_celda_1.' rowspan="2">Arancel</th>
    <th '.$color_celda_1.' rowspan="2">Otros Ingreso</th>
    <th '.$color_celda_1.' rowspan="2">Total</th>
  </tr>
  <tr>
    <th '.$color_celda_1.'>Desde</th>
    <th '.$color_celda_1.'>Hasta</th>
    </tr>
</thead>
<tbody>';
		
	$SUMA_TOTAL_MES=0;
	for($dia=1;$dia<=$ultimo_dia_mes;$dia++)	
	{
		
		if($dia<10)
		{ $dia_label="0".$dia;}
		else{ $dia_label=$dia;}
		
		$aux_fecha_consultar=$year."-".$mes."-".$dia_label;
		if(DEBUG){ echo"<br>--------------------------------------------------------------<br>FECHA: $aux_fecha_consultar<br>";}
		
		///consultar las distintas cajas ese dia
		$cons_cajas="SELECT DISTINCT (caja) FROM boleta WHERE sede='$sede' AND fecha='$aux_fecha_consultar' AND estado='OK' ORDER by caja";
		if(DEBUG){ echo"CAJAS: $cons_cajas<br>";}
		$sql_cajas=mysql_query($cons_cajas)or die(mysql_error());
		$num_cajas=mysql_num_rows($sql_cajas);
		if(DEBUG){ echo"N. Cajas: $num_cajas<br>";}
		if($num_cajas>0)
		{
			while($CX=mysql_fetch_row($sql_cajas))
			{
				$aux_caja=$CX[0];
				list($min_folio,$max_folio)=MM_FOLIO_DIA($aux_fecha_consultar, $sede, $aux_caja);
				
				if(DEBUG){ echo"<strong>CAJA: $aux_caja</strong><br>";}
				$cons_MAIN="SELECT pagos.* FROM pagos INNER JOIN boleta ON pagos.id_boleta=boleta.id WHERE pagos.fechapago='$aux_fecha_consultar' AND pagos.id_boleta>'0' AND pagos.sede='$sede' AND boleta.caja='$aux_caja' ORDER by pagos.fechapago";
			if(DEBUG){ echo"<br>-->$cons_MAIN<br>";}
			$sql_MAIN=mysql_query($cons_MAIN)or die("MAIN".mysql_error());
			$num_registros=mysql_num_rows($sql_MAIN);
				$SUMA_DIA=array("matricula"=>0, "arancel"=>0, "otro"=>0);
				$total_dia=0;
			if($num_registros>0)
			{
				$hay_movimiento=true;
				while($P=mysql_fetch_assoc($sql_MAIN))
				{
					$id_pago=$P["idpago"];
					$id_boleta=$P["id_boleta"];
					$item=$P["item"];
					$por_concepto=$P["por_concepto"];
					if($por_concepto=="matricula_nueva"){ $por_concepto="matricula";}
					$fecha_pago=$P["fechapago"];
					$valor=$P["valor"];
					
					if(DEBUG){ echo"<b>$fecha_pago</b> $id_pago $id_boleta $item $valor $por_concepto<br>";}
					
					
					if(($por_concepto=="matricula")or($por_concepto=="arancel"))
					{ $SUMA_DIA[$por_concepto]+=$valor;}
					else{ $SUMA_DIA["otro"]+=$valor;}
					
					$total_dia+=$valor;
					$SUMA_TOTAL_MES+=$valor;
					
				}
				if(DEBUG){ 
							var_export($SUMA_DIA);
							echo"FM:$max_folio Fm:$min_folio<br>";
						}
					
							
			}
			else
			{
				if(DEBUG){ echo"NO hay Movimientos este Dia<br>";}
				$aux_caja="---";
				$hay_movimiento=false;
				$max_folio="---";
				$min_folio="---";
			}
		//////////////////////////
		if($hay_movimiento)
		{
			$tabla.='<tr>
			<td>['.$aux_caja.']->'.fecha_format($aux_fecha_consultar).'</td>
			<td>'.$min_folio.'</td>
			<td>'.$max_folio.'</td>
			<td>'.$SUMA_DIA["matricula"].'</td>
			<td>'.$SUMA_DIA["arancel"].'</td>
			<td>'.$SUMA_DIA["otro"].'</td>
			<td>'.$total_dia.'</td>
			</tr>';
		}
		////////////////////////////	
		mysql_free_result($sql_MAIN);
			}
		}
		else{ 
			if(DEBUG){ echo"No hay Registros caja este Dia :(<br>";}
				$aux_caja="-X-";
				$hay_movimiento=false;
				$max_folio="---";
				$min_folio="---";
		}
		
		if(($ver_todos_los_dias)and(!$hay_movimiento))
		{
			$tabla.='<tr>
			<td>]'.$aux_caja.'[->'.fecha_format($aux_fecha_consultar).'</td>
			<td>'.$min_folio.'</td>
			<td>'.$max_folio.'</td>
			<td>'.$SUMA_DIA["matricula"].'</td>
			<td>'.$SUMA_DIA["arancel"].'</td>
			<td>'.$SUMA_DIA["otro"].'</td>
			<td>'.$total_dia.'</td>
			</tr>';
		}
		mysql_free_result($sql_cajas);
	}
	
$tabla.='</tbody>
 <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><strong>TOTAL</strong></td>
    <td><strong>'.$SUMA_TOTAL_MES.'</strong></td>
  </tr>
</table>';

	echo $tabla;
	echo DATOS_BOLETAS_NULAS($ultimo_dia_mes,$mes,$year,$sede);
	mysql_close($conexion);	

///////////////////////////////////
function MAX_DIA_MES($mes, $year)	
{
	switch($mes)
	{
		case"01":
			$max_dia=31;
			break;
		case"02":
			if($year%4==0)
			{ $max_dia=29;}
			else
			{ $max_dia=28;}
			break;
		case"03":
			$max_dia=31;
			break;		
		case"04":
			$max_dia=30;
			break;
		case"05":
			$max_dia=31;
			break;
		case"06":
			$max_dia=30;
			break;			
		case"07":
			$max_dia=31;
			break;
		case"08":
			$max_dia=31;
			break;
		case"09":
			$max_dia=30;
			break;
		case"10":
			$max_dia=31;
			break;
		case"11":
			$max_dia=30;
			break;
		case"12":
			$max_dia=31;
			break;						
	}
	return($max_dia);
}
///////////////////////
function MM_FOLIO_DIA($fecha, $sede, $caja)
{
	$cons_min="SELECT MIN(folio) FROM boleta WHERE sede='$sede' AND fecha='$fecha' AND folio>'0' AND caja='$caja'";
	$cons_max="SELECT MAX(folio) FROM boleta WHERE sede='$sede' AND fecha='$fecha' AND folio>'0' AND caja='$caja'";
	
	if(DEBUG){ echo"<br><br>MAX---> $cons_max<br>MIN--->$cons_min<br>";}
	
	$SQL_min=mysql_query($cons_min)or die("minimo".mysql_error());
	$SQL_MAX=mysql_query($cons_max)or die("maximo".mysql_error());;
	
	$MIN=mysql_fetch_row($SQL_min);
	$MAX=mysql_fetch_row($SQL_MAX);
	
	$folio_minimo=$MIN[0];
	$folio_max=$MAX[0];
	
	mysql_free_result($SQL_min);
	mysql_free_result($SQL_MAX);
	
	return(array($folio_minimo, $folio_max));
}
////////////////////////////////
function DATOS_BOLETAS_NULAS($max_dia_mes, $mes, $year, $sede)
{
	$estado_nula='ANULADA';
	$fecha_ini=$year."-".$mes."-01";
	$fecha_fin=$year."-".$mes."-".$max_dia_mes;
	
	$tabla='<table border="1" align="center" width="100%">
				<thead>
				</thead>
				<tbody>';
	$cons="SELECT * FROM boleta WHERE estado='$estado_nula' AND sede='$sede' AND fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
	if(DEBUG){ echo"-->$cons<br>";}
		$sql=mysql_query($cons)or die("Boleta".mysql_error());
		$num_boletas_anuladas=mysql_num_rows($sql);
		if($num_boletas_anuladas)
		{
			$total_nulas=0;
			while($BA=mysql_fetch_assoc($sql))
			{
				$id_boleta=$BA["id_boleta"];
				$folio_anulada=$BA["folio"];
				$fecha_anulada=$BA["fecha"];
				$valor_anulada=$BA["valor"];
				$total_nulas+=$valor_anulada;
				
				
				$tabla.='<tr>
						<td>'.$fecha_anulada.'</td>
						<td>'.$folio_anulada.'</td>
						<td>'.$valor_anulada.'</td>
						</tr>';
			}
			$tabla.='<tr>
						<td colspan="2">Total</td>
						<td>'.$total_nulas.'</td>
					</tr>';
		}
		else
		{
			//no hay nulas
			$tabla.='<td colspan="3"> No hay Boletas Nulas</td>';
		}
		$tabla.='</tbody>
				</table>';
	
	return($tabla);
}
?>
