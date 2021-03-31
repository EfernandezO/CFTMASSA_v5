<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Ingresos_totales_X_mes_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_datos_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PAGOS");
//////////////////////////////////////////////////////////////
function BUSCA_PAGOS($year)
{
	require("../../../funciones/conexion_v2.php");
	
	$fecha_inicio=$year.'-01-01';
	$fecha_fin=$year.'-12-31';
	$cons="SELECT * FROM pagos WHERE movimiento='I' AND fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	$array_meses_label=array("---", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$T_array_valores=array();
	$L_array_valores=array();
	if($num_registros>0)
	{
		while($P=$sqli->fetch_assoc())
		{
			$P_fecha_pago=$P["fechapago"];
			$P_valor=$P["valor"];
			$P_sede=$P["sede"];
			
			$array_fecha=explode("-",$P_fecha_pago);
			$mes=$array_fecha[1];
			
			switch($P_sede)
			{
				case"Talca":
					if(isset($T_array_valores[$mes])){$T_array_valores[$mes]+=$P_valor;}
					else{$T_array_valores[$mes]=$P_valor;}
					break;
				case"Linares":
					if(isset($L_array_valores[$mes])){$L_array_valores[$mes]+=$P_valor;}
					else{$L_array_valores[$mes]=$P_valor;}
					break;
			}
		}
	}
	else
	{
		$html="Sin Registros...";
	}
	$sqli->free();
	/////////////////////////////////////////////////
	if($num_registros>0)
	{
		$html='<table border="1" width="80%">
		<thead>
			<tr>
				<th colspan="7">'.$year.'</th>
			</tr>
			<tr>
				<td>Mes</td>
				<td colspan="2" align="center">Talca</td>
				<td colspan="2" align="center">Linares</td>
				<td colspan="2" align="center">Total</td>
			</tr>
			</thead>
			<tbody>';
		$acumulado_talca=0;
		$acumulado_linares=0;	
		$TOTAL=0;
		$acumula_total=0;
		for($m=1;$m<=12;$m++)
		{
			if($m<10){$m_label="0".$m;}
			else{ $m_label=$m;}
			
			$aux_valor_T=$T_array_valores[$m_label];
			$aux_valor_L=$L_array_valores[$m_label];
			
			if(empty($aux_valor_T)){$aux_valor_T=0;}
			if(empty($aux_valor_L)){$aux_valor_L=0;}
			
			$TOTAL=($aux_valor_T+$aux_valor_L);
			
			$acumulado_talca+=$aux_valor_T;
			$acumulado_linares+=$aux_valor_L;
			$acumula_total+=$TOTAL;
			
			$html.='<tr>
						<td align="left">'.$m.'-'.$array_meses_label[$m].'</td>
						<td align="right">'.number_format($aux_valor_T,0,",",".").'</td>
						<td align="right">'.number_format($acumulado_talca,0,",",".").'</td>
						<td align="right">'.number_format($aux_valor_L,0,",",".").'</td>
						<td align="right">'.number_format($acumulado_linares,0,",",".").'</td>
						<td align="right">'.number_format($TOTAL,0,",",".").'</td>
						<td align="right">'.number_format($acumula_total,0,",",".").'</td>
					</tr>';
			
		}
		$html.='</tbody></table>';
	}
	///////////////////////////////////////////////////
	
		$div='div_datos';
		$objResponse = new xajaxResponse();
		$objResponse->Assign($div,"innerHTML","<br><br>".$html);
		@mysql_close($conexion);
		$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>