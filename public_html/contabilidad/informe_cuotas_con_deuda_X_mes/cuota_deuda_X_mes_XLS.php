<?php 
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//

if($_GET)
{
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
		
		
		$carrera=base64_decode($_GET["carrera"]);
		$sede=base64_decode($_GET["sede"]);
		$year=base64_decode($_GET["year"]);
		$mes=base64_decode($_GET["mes"]);
		$nivel=base64_decode($_GET["nivel"]);
		
		$nombre_archivo="cuota_mes(".$carrera."_".$sede.")";
		
		if(DEBUG)
		{ var_export($_GET);}
		else
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=$nombre_archivo.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		if($mes>0)
		{
			$ultimo_dia_mes=MAX_DIA_MES($mes, $year);
			
			$fecha_ini=$year."-".$mes."-01";
			$fecha_fin=$year."-".$mes."-".$ultimo_dia_mes;
			$condicion_mes="AND letras.fechavenc BETWEEN '$fecha_ini' AND '$fecha_fin'";
		}else{ $condicion_mes="";}
		
		if($year!="todos")
		{ $condicion_year_cuota="AND letras.ano='$year'";}
		else
		{ $condicion_year_cuota="";}
		
		if($carrera!="todas")
		{ $condicion_carrera="alumno.carrera='$carrera' AND";}
		else
		{ $condicion_carrera="";}
		
		if($nivel!="todos")
		{ $condicion_nivel="alumno.nivel='$nivel' AND";}
		else
		{ $condicion_nivel="";}
		
		
$tabla='<table border="1" align="center" width="100%">
<thead>
	<td colspan="11" align="center">Carrera:'.$carrera.' - Sede:'.$sede.'  - Nivel: '.$nivel.' - Mes: '.$mes.' - Año: '.$year.'</td>
	<tr>
	<th rowspan="2">Situacion</th>
	<th rowspan="2">Ingreso</th>
    <th rowspan="2">Nivel</th>
    <th rowspan="2">Rut</th>
    <th rowspan="2">Nombre</th>
    <th rowspan="2">Apellido P</th>
    <th rowspan="2">Apellido M</th>
    <th colspan="4">Periodo</th>
    <th rowspan="2">Fecha Vencimiento</th>
    <th rowspan="2">Valor Cuota</th>
    <th rowspan="2">Deuda Cuota</th>
    <tr>
      <th>Semestre</th>
      <th>A&ntilde;o</th>
      <th>N. Cuotas</th>
      <th>Total</th>
    <tbody>';

		
		$cons_CUO="SELECT letras.id, letras.idalumn, letras.id_contrato, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.nivel, alumno.ingreso, alumno.situacion, letras.fechavenc, letras.valor, letras.deudaXletra, letras.semestre, letras.ano, letras.sede FROM letras INNER JOIN alumno ON letras.idalumn=alumno.id WHERE $condicion_carrera $condicion_nivel alumno.sede='$sede' $condicion_mes $condicion_year_cuota ORDER by sede, carrera, fechavenc";
		
		if(DEBUG){ echo"<br><br>-->$cons_CUO<br>";}
		$sql_CUO=mysql_query($cons_CUO)or die(mysql_error());
		$num_cuotas_encontradas=mysql_num_rows($sql_CUO);
		
		if($num_cuotas_encontradas>0)
		{
			$SUMA_TOTAL_VALOR=0;
			$SUMA_TOTAL_DEUDA=0;
			while($L=mysql_fetch_assoc($sql_CUO))	
			{
				$A_id=$L["idalumn"];
				$A_rut=$L["rut"];
				$A_nombre=$L["nombre"];
				$A_apellido_P=$L["apellido_P"];
				$A_apellido_M=$L["apellido_M"];
				
				$A_year_ingreso=$L["ingreso"];
				$A_carrera=$L["carrera"];
				$A_sede=$L["sede"];
				$A_nivel=$L["nivel"];
				$A_situacion=$L["situacion"];
				
				$C_id_contrato=$L["id_contrato"];
				$C_id=$L["id"];
				$C_vence=$L["fechavenc"];
				$C_valor=$L["valor"];
				$C_deuda=$L["deudaXletra"];
				$C_semestre=$L["semestre"];
				$C_year=$L["ano"];
				
				$SUMA_TOTAL_DEUDA+=$C_deuda;
				$SUMA_TOTAL_VALOR+=$C_valor;
				
				if(DEBUG){ echo" <b>$A_id</b> $C_id - $C_valor - $C_deuda - $C_vence - $C_semestre - $C_year - $A_carrera - $A_sede - [$C_id_contrato]<br>";}
				
				$cons_C="SELECT * FROM contratos2 WHERE id='$C_id_contrato' AND id_alumno='$A_id'";
				if(DEBUG){ echo":---> $cons_C<br>";}
				$sql_C=mysql_query($cons_C)or die("contratos ".mysql_error());
				$CNT=mysql_fetch_assoc($sql_C);
				mysql_free_result($sql_C);
				
				$CNT_linea_credito=$CNT["linea_credito_paga"];
				$CNT_n_cuotas=$CNT["numero_cuotas"];
				
				
				$tabla.='<tr>
					<td>'.$A_situacion.'</td>
					<td>'.$A_year_ingreso.'</td>
					<td>'.$A_nivel.'</td>
					<td>'.$A_rut.'</td>
					<td>'.$A_nombre.'</td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>
					<td>'.$C_semestre.'</td>
					<td>'.$C_year.'</td>
					<td>'.$CNT_n_cuotas.'</td>
					<td>'.$CNT_linea_credito.'</td>
					<td>'.fecha_format($C_vence).'</td>
					<td>'.$C_valor.'</td>
					<td>'.$C_deuda.'</td>
				</tr>';
			}
			
			if(DEBUG){ echo"<br>TOTAL V----->$SUMA_TOTAL_VALOR  TOTAL D --->$SUMA_TOTAL_DEUDA<br>";}
			$tabla.='<tr>
					<td><strong>Totales</strong></td>
					<td colspan="11">&nbsp;</td>
					<td><strong>'.$SUMA_TOTAL_VALOR.'</strong></td>
					<td><strong>'.$SUMA_TOTAL_DEUDA.'</strong></td>
				</tr>';
			
		}
		else
		{
			if(DEBUG){ echo"NO se Encontraron Cuotas...<br>";}
		}
	mysql_free_result($sql_CUO);	
	mysql_close($conexion);
}
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
$tabla.='</tbody>
</table>';
echo $tabla;
?>
