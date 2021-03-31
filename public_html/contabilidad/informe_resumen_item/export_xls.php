<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_GET)
{
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe_caja.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
	if(DEBUG){ var_export($_POST);}
	$sede=$_GET["sede"];
	
	if($sede=="todas"){  $condicion_sede="";}
	else{ $condicion_sede="sede='$sede' AND";}
	
	$fecha_inicio=$_GET["fecha_inicio"];
	$fecha_fin=$_GET["fecha_fin"];
	
	$cons_1="SELECT * FROM pagos WHERE $condicion_sede fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER by por_concepto";
	if(DEBUG){ echo"--> $cons_1<br>";}
	$sql_1=mysql_query($cons_1)or die(mysql_error());
	$num_reg=mysql_num_rows($sql_1);
	
	$msj="Resumen Por Concepto Del ".fecha_format($fecha_inicio)." al ".fecha_format($fecha_fin)."Sede: $sede";
	
	echo'<table width="100%" border="1">
  <caption>'.$msj.'</caption>
  <thead>
  <tr>
    <td><div align="center"><strong>N&deg;</strong></div></td>
    <td><div align="center"><strong>Concepto</strong></div></td>
    <td><div align="center"><strong>Ingreso($)</strong></div></td>
    <td><div align="center"><strong>Egreso($)</strong></div></td>
    <td><div align="center"><strong>Total($)</strong></div></td>
  </tr>
  </thead>
  <tbody>';
  if($num_reg>0)
	{
	  $RESUMEN=array();
		while($P=mysql_fetch_assoc($sql_1))
		{
			$id_pago=$P["idpago"];
			$fecha_pago=$P["fechapago"];
			$valor=$P["valor"];
			$por_concepto=$P["por_concepto"];
			$forma_pago=$P["forma_pago"];
			$movimiento=$P["movimiento"];
			
			if(DEBUG){echo"$id_pago - $fecha_pago - $valor - $por_concepto - $forma_pago - $movimiento <br>";}
			$RESUMEN[$por_concepto][$movimiento]+=$valor;
		}
		
		if(DEBUG){var_export($RESUMEN);}
		$contador=0;
		$TOTALES=array();
		foreach($RESUMEN as $concepto => $MOV)
		{
			$contador++;
			$aux_ingreso=$MOV["I"];
			$aux_egreso=$MOV["E"];
			if(empty($aux_ingreso)){ $aux_ingreso=0;}
			if(empty($aux_engreso)){ $aux_engreso=0;}
			$aux_total=($aux_ingreso-$aux_egreso);
			
			$TOTALES["I"]+=$aux_ingreso;
			$TOTALES["E"]+=$aux_egreso;
			$TOTALES["T"]+=$aux_total;
			echo'<tr>
				<td>'.$contador.'</td>
				<td>'.$concepto.'</td>
				<td><div align="right">'.number_format($aux_ingreso,0,",",".").'</div></td>
				<td><div align="right">'.number_format($aux_egreso,0,",",".").'</div></td>
				<td><div align="right">'.number_format($aux_total,0,",",".").'</div></td>
				</tr>';
		}
		echo'<tr>
				<td colspan="2"><strong>TOTALES</strong></td>
				<td><div align="right">'.number_format($TOTALES["I"],0,",",".").'</div></td>
				<td><div align="right">'.number_format($TOTALES["E"],0,",",".").'</div></td>
				<td><div align="right">'.number_format($TOTALES["T"],0,",",".").'</div></td>
				</tr>';
		
	}
	else
	{
		echo'<tr><td colspan="5">Sin Registros</td></tr>';
	}
mysql_free_result($sql_1);
mysql_close($conexion);

echo"</tbody>
</table>";
}
else
{ echo"sin datos<br>";}
?>