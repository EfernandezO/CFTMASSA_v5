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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Libro de Ventas X Item</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 90px;
}
</style>
</head>
<?php
	set_time_limit(90);
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
		if(DEBUG){ var_export($_POST);}
		$year=$_POST["year"];
		$mes=$_POST["mes"];
		$sede=$_POST["fsede"];
		 $ver_todos_los_dias=true;
		$ultimo_dia_mes=MAX_DIA_MES($mes,$year);
		
?>
<body>
<h1 id="banner">Administrador - Finanzas Libro de Venta X Item</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a><br /><br />

<a href="por_item_xls.php?year=<?php echo $year;?>&mes=<?php echo $mes;?>&sede=<?php echo $sede;?>&ver_todos_los_dias=<?php echo $ver_todos_los_dias;?>" class="button">.XLS </a></div>
<div id="apDiv1">
<h2><?php echo"$sede - [$mes - $year] fecha generacion: ".date("d-m-Y");?></h2>
<table width="100%" border="1" align="center">
<thead>
  <tr>
    <th width="17%" rowspan="2">Dia</th>
    <th colspan="2">Rango</th>
    <th width="19%" rowspan="2">Matricula</th>
    <th width="13%" rowspan="2">Arancel</th>
    <th width="11%" rowspan="2">Otros Ingreso</th>
    <th width="7%" rowspan="2">Total</th>
  </tr>
  <tr>
    <th width="14%">Desde</th>
    <th width="19%">Hasta</th>
    </tr>
</thead>
<tbody>
<?php		
	$SUMA_TOTAL_MES=0;
	$SUMA_MES=array("matricula"=>0, "arancel"=>0, "otro"=>0);
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
		$sqli_cajas=$conexion_mysqli->query($cons_cajas)or die($conexion_mysqli->error);
		$num_cajas=$sqli_cajas->num_rows;
		if(DEBUG){ echo"N. Cajas: $num_cajas<br>";}
		
		$total_dia=0;
		$SUMA_DIA=array("matricula"=>0, "arancel"=>0, "otro"=>0);
		
		if($num_cajas>0)
		{
			
			while($CX=$sqli_cajas->fetch_row())
			{
				$aux_caja=$CX[0];
				list($min_folio,$max_folio)=MM_FOLIO_DIA($aux_fecha_consultar, $sede, $aux_caja);
				
				if(DEBUG){ echo"<strong>CAJA: $aux_caja</strong><br>";}
				$cons_MAIN="SELECT pagos.* FROM pagos INNER JOIN boleta ON pagos.id_boleta=boleta.id WHERE pagos.fechapago='$aux_fecha_consultar' AND pagos.id_boleta>'0' AND pagos.sede='$sede' AND boleta.caja='$aux_caja' ORDER by pagos.fechapago";
				if(DEBUG){ echo"<br>-->$cons_MAIN<br>";}
				$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die("MAIN".$conexion_mysqli->error);
				$num_registros=$sqli_MAIN->num_rows;	
					
				if($num_registros>0)
				{
					$hay_movimiento=true;
					while($P=$sqli_MAIN->fetch_assoc())
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
						{ $SUMA_DIA[$por_concepto]+=$valor; $SUMA_MES[$por_concepto]+=$valor;}
						else{ $SUMA_DIA["otro"]+=$valor; $SUMA_MES["otro"]+=$valor;}
						
						$total_dia+=$valor;
						$SUMA_TOTAL_MES+=$valor;
					}
					if(DEBUG){ var_dump($SUMA_DIA); echo"FM:$max_folio Fm:$min_folio<br>";}				
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
			
			////////////////////////////	
			$sqli_MAIN->free();
			}
		}
		else
		{ 
			if(DEBUG){ echo"No hay Registros caja este Dia :(<br>";}
			$aux_caja="-X-";
			$hay_movimiento=false;
			$max_folio="---";
			$min_folio="---";
		}
	//-----------------------------------------------------------------------------------------//
		if(($hay_movimiento)or($ver_todos_los_dias))
		{
			echo'<tr>
			<td>['.$aux_caja.']->'.fecha_format($aux_fecha_consultar).'</td>
			<td align="center">'.$min_folio.'</td>
			<td align="center">'.$max_folio.'</td>
			<td align="right">'.number_format($SUMA_DIA["matricula"],0,",",".").'</td>
			<td align="right">'.number_format($SUMA_DIA["arancel"],0,",",".").'</td>
			<td align="right">'.number_format($SUMA_DIA["otro"],0,",",".").'</td>
			<td align="right">'.number_format($total_dia,0,",",".").'</td>
			</tr>';
		}
		$sqli_cajas->free();
	}//fin for
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
				$id_boleta=$BA["id"];
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
</tbody>
 <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><?php echo "<strong>".number_format($SUMA_MES["matricula"],0,",",".")."</strong>";?></td>
    <td align="right"><?php echo "<strong>".number_format($SUMA_MES["arancel"],0,",",".")."</strong>";?></td>
    <td align="right"><?php echo "<strong>".number_format($SUMA_MES["otro"],0,",",".")."</strong>";?></td>
    <td align="right"><?php echo "<strong>".number_format($SUMA_TOTAL_MES,0,",",".")."</strong>";?></td>
  </tr>
</table>
<?php
	echo DATOS_BOLETAS_NULAS($ultimo_dia_mes,$mes,$year,$sede);
	mysql_close($conexion);	
?>
</div>
</body>
</html>