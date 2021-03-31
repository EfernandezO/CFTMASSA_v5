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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>resumen x item</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 139px;
}
-->
</style>
</head>
<?php
if($_POST)
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	if(DEBUG){ var_dump($_POST);}
	$sede=$_POST["fsede"];
	
	if($sede=="todas"){  $condicion_sede="";}
	else{ $condicion_sede="sede='$sede' AND";}
	
	$fecha_inicio=$_POST["fecha_inicio"];
	$fecha_fin=$_POST["fecha_fin"];
	
	
	$cons_1="SELECT * FROM pagos WHERE $condicion_sede fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER by por_concepto";
	if(DEBUG){ echo"<br>--> $cons_1<br>";}
	$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
	$num_reg=$sql_1->num_rows;
	
	$msj="Resumen Por Concepto </br>Del ".fecha_format($fecha_inicio)." al ".fecha_format($fecha_fin)."</br>Sede: $sede<br>";
}
else
{ echo"sin datos<br>";}
?>
<body>
<h1 id="banner">Administrador - Resum&eacute;n por Item</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<table width="100%" border="1">
  <caption><?php echo $msj;?></caption>
  <thead>
  <tr>
    <th width="7%"><div align="center"><strong>N&deg;</strong></div></th>
    <th width="24%"><div align="center"><strong>Concepto</strong></div></th>
    <th width="23%"><div align="center"><strong>Ingreso ($) </strong></div></th>
    <th width="25%"><div align="center"><strong>Egreso</strong> <strong>($)</strong></div></th>
    <th width="21%"><div align="center"><strong>Total</strong> <strong>($)</strong></div></th>
  </tr>
  </thead>
  <tbody>

<?php
if($num_reg>0)
{
	$RESUMEN=array();
	while($P=$sql_1->fetch_assoc())
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
		
		if(isset($MOV["I"])){$aux_ingreso=$MOV["I"];}
		else{$aux_ingreso=0;}
		if(isset($MOV["E"])){$aux_egreso=$MOV["E"];}
		else{ $aux_engreso=0;}
		
		
		if(empty($aux_ingreso)){ $aux_ingreso=0;}
		if(empty($aux_engreso)){ $aux_engreso=0;}
		$aux_total=($aux_ingreso-$aux_egreso);
		
		$TOTALES["I"]+=$aux_ingreso;
		$TOTALES["E"]+=$aux_egreso;
		$TOTALES["T"]+=$aux_total;
		$html_tabla.='<tr>
			<td>'.$contador.'</td>
			<td>'.$concepto.'</td>
			<td><div align="right">'.number_format($aux_ingreso,0,",",".").'</div></td>
			<td><div align="right">'.number_format($aux_egreso,0,",",".").'</div></td>
			<td><div align="right">'.number_format($aux_total,0,",",".").'</div></td>
			</tr>';
	}
	$html_tabla.='<tr>
			<td colspan="2"><strong>TOTALES</strong></td>
			<td><div align="right">'.number_format($TOTALES["I"],0,",",".").'</div></td>
			<td><div align="right">'.number_format($TOTALES["E"],0,",",".").'</div></td>
			<td><div align="right">'.number_format($TOTALES["T"],0,",",".").'</div></td>
			</tr>';
	
}
else
{
	$html_tabla.='<tr><td colspan="5">Sin Registros</td></tr>';
}
$sql_1->free();
$conexion_mysqli->close();

echo $html_tabla;
?>
</tbody>
</table>
<div align="right"><a href="export_xls.php?fecha_inicio=<?php echo $fecha_inicio;?>&fecha_fin=<?php echo $fecha_fin;?>&sede=<?php echo $sede;?>" title="Exportar a Excel"><img src="../../BAses/Images/excel_icon.png" width="31" height="31" /></a><a href="export_xls.php?fecha_inicio=<?php echo $fecha_inicio;?>&fecha_fin=<?php echo $fecha_fin;?>&sede=<?php echo $sede;?>"></a></div>
</div>
</body>
</html>