<?php 
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG", true);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Cuotas X Vencimiento</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 107px;
}
</style>
</head>
<?php
	    $id_carrera=$_POST["carreras"];
		$sede=$_POST["sede"];
		$year_cuotas=$_POST["year"];
		$nivel=$_POST["nivel"];
?>
<body>
<h1 id="banner">Administrador - Cuotas X Mes</h1>
<div id="link"><br />
<a href="index.php" class="button">volver a Seleccion</a><br /><br />

<a href="../balance/proyecciones_v2/proyeccion_1.php" class="button">Ir a Proyecciones</a><br />
<br />
<a href="cuota_deuda_X_mes_XLS.php?carrera=<?php echo base64_encode($carrera);?>&sede=<?php echo base64_encode($sede);?>&year=<?php echo base64_encode($year);?>&mes=<?php echo base64_encode($mes);?>&nivel=<?php echo base64_encode($nivel);?>" class="button">.XLS </a></div>
<div id="apDiv1">
<?php
if($_POST)
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	     
		if(DEBUG){ var_export($_POST);}
		
		if($mes>0)
		{
			$ultimo_dia_mes=MAX_DIA_MES($mes, $year);
			
			$fecha_ini=$year."-".$mes."-01";
			$fecha_fin=$year."-".$mes."-".$ultimo_dia_mes;
			
			$condicion_mes="AND letras.fechavenc BETWEEN '$fecha_ini' AND '$fecha_fin'";
		}else{ $condicion_mes="";}
		
		if($year!="todos")
		{ $condicion_year_cuota="AND letras.ano='$year_cuotas'";}
		else
		{ $condicion_year_cuota="";}
		
		if($id_carrera!="0")
		{ $condicion_carrera="contratos2.id_carrera='$id_carrera' AND";}
		else
		{ $condicion_carrera="";}
		
		
		
?>
<strong>Carrera:</strong> <?php echo $carrera;?> <strong>Sede:</strong> <?php echo $sede;?> <strong>Nivel:</strong> <?php echo $nivel;?><br />
<strong>Mes:</strong> <?php echo $mes;?> <strong>Año:</strong> <?php echo $year;?><br />

<table border="1" align="center" width="100%">
<thead>
	<tr>
    <th rowspan="2">id_alumno</th>
    <th rowspan="2">id_carrera</th>
    <th rowspan="2">Jornada</th>
    <th rowspan="2">yearIngresoCarrera</th>
    <th rowspan="2">Rut</th>
    <th rowspan="2">Nombre</th>
    <th rowspan="2">Apellido P</th>
    <th rowspan="2">Apellido M</th>
    <th colspan="4">Periodo</th>
    <th rowspan="2">Tipo</th>
    <th rowspan="2">Fecha Vencimiento</th>
    <th rowspan="2">Valor Cuota</th>
    <th rowspan="2">Deuda Cuota</th>
    <tr>
      <th>Semestre</th>
      <th>A&ntilde;o</th>
      <th>id_contrato</th>
      <th>Total</th>
    <tbody>
<?php
		
		$cons_CUO="SELECT letras.id AS id_letra, letras.idalumn, letras.id_contrato, letras.fechavenc, letras.valor, letras.deudaXletra, letras.semestre, letras.ano, letras.sede, letras.tipo, contratos2.id_alumno, contratos2.yearIngresoCarrera, contratos2.id_carrera, contratos2.jornada, contratos2.linea_credito_paga FROM letras INNER JOIN contratos2 ON letras.id_contrato = contratos2.id WHERE $condicion_carrera contratos2.sede='$sede'  $condicion_year_cuota ORDER by sede, id_carrera, fechavenc";
		
		if(DEBUG){ echo"<br><br>-->$cons_CUO<br><br>";}
		$sql_CUO=$conexion_mysqli->query($cons_CUO)or die($conexion_mysqli->error);
		$num_cuotas_encontradas=$sql_CUO->num_rows;
		
		if($num_cuotas_encontradas>0)
		{
			$SUMA_TOTAL_VALOR=0;
			$SUMA_TOTAL_DEUDA=0;
			while($L=$sql_CUO->fetch_assoc())	
			{
				$A_id=$L["id_alumno"];
				
				
				$A_year_ingreso=$L["yearIngresoCarrera"];
				$A_carrera=$L["carrera"];
				$A_sede=$L["sede"];
				$A_nivel=$L["nivel"];
				$A_situacion=$L["situacion"];
				
				$C_id_contrato=$L["id_contrato"];
				$C_id=$L["id_letra"];
				$C_id_carrera=$L["id_carrera"];
				$C_jornada=$L["jornada"];
				$C_vence=$L["fechavenc"];
				$C_valor=$L["valor"];
				$C_deuda=$L["deudaXletra"];
				$C_semestre=$L["semestre"];
				$C_year=$L["ano"];
				
				$C_tipoCuota=$L["tipo"];
				
				$SUMA_TOTAL_DEUDA+=$C_deuda;
				$SUMA_TOTAL_VALOR+=$C_valor;
				
				if(DEBUG){ echo" <b>$A_id</b> $C_id - $C_valor - $C_deuda - $C_vence - $C_semestre - $C_year - $A_carrera - $A_sede - [$C_id_contrato]<br>";}
				
				
				
				$CNT_linea_credito=$CNT["linea_credito_paga"];
				$CNT_n_cuotas=$CNT["numero_cuotas"];
				
				
				echo'<tr>
					<td>'.$A_id.'</td>
					<td>'.$C_id_carrera.'</td>
					<td>'.$C_jornada.'</td>
					<td>'.$A_year_ingreso.'</td>
					
					<td>'.$A_rut.'</td>
					<td>'.$A_nombre.'</td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>
					<td>'.$C_semestre.'</td>
					<td>'.$C_year.'</td>
					<td>'.$C_id_contrato.'</td>
					<td>'.$CNT_linea_credito.'</td>
					<td>'.$C_tipoCuota.'</td>
					<td>'.fecha_format($C_vence).'</td>
					<td align="right">'.$C_valor.'</td>
					<td align="right">'.$C_deuda.'</td>
				</tr>';
			}
			
			if(DEBUG){ echo"<br>TOTAL V----->$SUMA_TOTAL_VALOR  TOTAL D --->$SUMA_TOTAL_DEUDA<br>";}
			echo'<tr>
					<td><strong>Totales</strong></td>
					<td colspan="11">&nbsp;</td>
					<td align="right"><strong>'.$SUMA_TOTAL_VALOR.'</strong></td>
					<td align="right"><strong>'.$SUMA_TOTAL_DEUDA.'</strong></td>
				</tr>';
			
		}
		else
		{
			if(DEBUG){ echo"NO se Encontraron Cuotas...<br>";}
		}
	$sql_CUO->free();	
	$conexion_mysqli->close();
}

?>
</tbody>
</table>
</div>
</body>
</html>