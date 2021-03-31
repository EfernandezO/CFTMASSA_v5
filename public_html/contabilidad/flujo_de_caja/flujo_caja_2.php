<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Flujo_de_caja->tipo_1_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if(DEBUG){ var_dump($_POST);}
if($_POST)
{
	if(isset($_POST["year"])){$year_consulta=$_POST["year"];}
	else{$year_consulta=date("Y");}
	$continuar=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 85px;
}
</style>
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Flujo de Caja</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
</head>

<body>
<h1 id="banner">Administrador - Flujo de Caja</h1>
<div id="link"><br />
<a href="flujo_caja_1.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<?php if($continuar){?>
	
    <table border="1" width="100%">
    <thead>
    	<tr>
    		<th colspan="14">FLUJO CAJA CONSOLIDADO <?php echo $year_consulta;?>(INGRESOS)</th>
         </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
		require("../../../funciones/conexion_v2.php");
		
		$year_consulta=mysqli_real_escape_string($conexion_mysqli, $year_consulta);
		
		//--------------------------------------------//
		include("../../../funciones/VX.php");
		$evento="Genera Flujo de Caja tipo 1 year: $year_consulta";
		REGISTRA_EVENTO($evento);
		//-----------------------------------------------//
		
		$cons="SELECT month(fechapago) AS MES, `por_concepto`, SUM(valor) AS TOTAL, sede FROM `pagos` WHERE movimiento='I' AND year(fechapago)=$year_consulta GROUP BY `por_concepto`, month(fechapago), sede  ORDER BY `por_concepto`";
		if(DEBUG){ echo"<strong>--->$cons</strong><br>";}
		$sqli=$conexion_mysqli->query($cons);
		$num_registros=$sqli->num_rows;
		if(DEBUG){ echo"Numero Registros: $num_registros<br>";}
			
		$ARRAY_INGRESOS_EFECTIVOS=array();
		if($num_registros>0)
		{
			while($P=$sqli->fetch_assoc())
			{
				$P_mes=$P["MES"];
				$P_concepto=$P["por_concepto"];
				$P_total=$P["TOTAL"];
				$P_sede=$P["sede"];
				
				if(DEBUG){ echo"Sede: $P_sede Mes: $P_mes Concepto: $P_concepto Total: $P_total ->";}
				if(isset($ARRAY_INGRESOS_EFECTIVOS[$P_concepto][$P_mes][$P_sede])){ if(DEBUG){ echo"ARRAY YA definido..raro<br>";}}
				else{$ARRAY_INGRESOS_EFECTIVOS[$P_concepto][$P_mes][$P_sede]=$P_total; if(DEBUG){ echo"ARRAY NO definido..OK<br>";}}
				
			}
		}
	$sqli->free();
	//--------------------------------------------------------------------------------------------------------------------------//
	//busco ahora las deuda de cuotas
	$cons_C="SELECT month(fechavenc) AS MES, SUM(deudaXletra) AS TOTAL, sede FROM `letras` WHERE deudaXletra>'0' AND year(fechavenc)=$year_consulta GROUP BY month(fechavenc), sede ORDER BY `MES` ";
	if(DEBUG){ echo"<strong>--->$cons_C</strong><br>";}
		$sqli_C=$conexion_mysqli->query($cons_C);
		$num_registros=$sqli_C->num_rows;
		if(DEBUG){ echo"Numero Registros: $num_registros<br>";}
			
		$ARRAY_CUOTAS_PENDIENTES=array();
		if($num_registros>0)
		{
			while($C=$sqli_C->fetch_assoc())
			{
				$C_mes=$C["MES"];
				$C_total=$C["TOTAL"];
				$C_sede=$C["sede"];
				
				if(DEBUG){ echo"Sede: $C_sede Mes: $C_mes  Total: $C_total ->";}
				if(isset($ARRAY_CUOTAS_PENDIENTES[$C_mes][$C_sede])){ if(DEBUG){ echo"ARRAY YA definido..raro<br>";}}
				else{$ARRAY_CUOTAS_PENDIENTES[$C_mes][$C_sede]=$C_total; if(DEBUG){ echo"ARRAY NO definido..OK<br>";}}
				
			}
		}
	$sqli_C->free();
   // var_dump($ARRAY_INGRESOS_EFECTIVOS);
//-----------------------------------------inicio escritura consolidado------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	foreach($ARRAY_INGRESOS_EFECTIVOS as $aux_por_concepto => $aux_array_meses)
	{
		echo'<tr>
				<td>'.$aux_por_concepto.'</td>';
		$aux_total_X_concepto=0;
		
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{

			$aux_total=0;
			if(isset($aux_array_meses[$aux_mes]))
			{
				
				foreach($aux_array_meses[$aux_mes] as $aux_sede => $aux_valor)
				{$aux_total+=$aux_valor;}
			}
			
	
			$aux_total_X_concepto+=$aux_total;
			$SUMA_TOTAL+=$aux_total;
			
			if(isset($array_total_X_mes[$aux_mes])){$array_total_X_mes[$aux_mes]+=$aux_total;}
			else{$array_total_X_mes[$aux_mes]=$aux_total;}
			echo'<td align="right">'.$aux_total.'</td>';
			
			
		}//fin meses
		echo'<td align="right">'.$aux_total_X_concepto.'</td></tr>';
			
	}
	echo'<tr>
			<td><strong>TOTAL</strong></td>';	
	for($aux_mes=1;$aux_mes<=12;$aux_mes++)
	{
		echo'<td align="right">'.$array_total_X_mes[$aux_mes].'</td>';
	}
	echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
	?>
    </tbody>
    </table>
    <br /><br />
     <table border="1" width="100%">
    <thead>
    	<tr>
    		<th colspan="14">FLUJO CAJA Talca <?php echo $year_consulta;?>(INGRESOS)</th>
      </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
    //-----------------------------------------inicio escritura Sede Talca------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	$sede_consulta="Talca";
	foreach($ARRAY_INGRESOS_EFECTIVOS as $aux_por_concepto => $aux_array_meses)
	{
		echo'<tr>
				<td>'.$aux_por_concepto.'</td>';
		$aux_total_X_concepto=0;
		
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{

			$aux_total=0;
			if(isset($aux_array_meses[$aux_mes][$sede_consulta]))
			{
				$aux_total=$aux_array_meses[$aux_mes][$sede_consulta];
			}
			
	
			$aux_total_X_concepto+=$aux_total;
			$SUMA_TOTAL+=$aux_total;
			
			if(isset($array_total_X_mes[$aux_mes])){$array_total_X_mes[$aux_mes]+=$aux_total;}
			else{$array_total_X_mes[$aux_mes]=$aux_total;}
			echo'<td align="right">'.$aux_total.'</td>';
			
			
		}//fin meses
		echo'<td align="right">'.$aux_total_X_concepto.'</td></tr>';
			
	}
	echo'<tr>
			<td><strong>TOTAL</strong></td>';	
	for($aux_mes=1;$aux_mes<=12;$aux_mes++)
	{
		echo'<td align="right">'.$array_total_X_mes[$aux_mes].'</td>';
	}
	echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
		
	?>
    </tbody>
    </table>
    <br />
     <br />
     <table border="1" width="100%">
    <thead>
    	<tr>
    		<th colspan="14">FLUJO CAJA Linares <?php echo $year_consulta;?>(INGRESOS)</th>
      </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
    //-----------------------------------------inicio escritura Sede Linares------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	$sede_consulta="Linares";
	foreach($ARRAY_INGRESOS_EFECTIVOS as $aux_por_concepto => $aux_array_meses)
	{
		echo'<tr>
				<td>'.$aux_por_concepto.'</td>';
		$aux_total_X_concepto=0;
		
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{

			$aux_total=0;
			if(isset($aux_array_meses[$aux_mes][$sede_consulta]))
			{
				$aux_total=$aux_array_meses[$aux_mes][$sede_consulta];
			}
			
	
			$aux_total_X_concepto+=$aux_total;
			$SUMA_TOTAL+=$aux_total;
			
			if(isset($array_total_X_mes[$aux_mes])){$array_total_X_mes[$aux_mes]+=$aux_total;}
			else{$array_total_X_mes[$aux_mes]=$aux_total;}
			echo'<td align="right">'.$aux_total.'</td>';
			
			
		}//fin meses
		echo'<td align="right">'.$aux_total_X_concepto.'</td></tr>';
			
	}
	echo'<tr>
			<td><strong>TOTAL</strong></td>';	
	for($aux_mes=1;$aux_mes<=12;$aux_mes++)
	{
		echo'<td align="right">'.$array_total_X_mes[$aux_mes].'</td>';
	}
	echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
	?>
    </tbody>
    </table>
 </tbody>
    </table>
    <br /><br />
     <table border="1" width="100%">
    <thead>
    	<tr>
   		  <th colspan="14">FLUJO CAJA CONSOLIDADO  <?php echo $year_consulta;?>(Cuotas Pendientes)</th>
      </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
    //-----------------------------------------inicio escritura deuda CONSOLIDADO------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	if(DEBUG){var_dump($ARRAY_CUOTAS_PENDIENTES);}
		echo'<tr><td>Cuotas</td>';
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{
				$aux_total_X_concepto=0;
				$aux_total=0;
				
			$aux_total=0;	
			foreach($ARRAY_CUOTAS_PENDIENTES[$aux_mes] as $aux_sede => $aux_deuda)
			{
				$aux_total+=$aux_deuda;
			}
			
			$SUMA_TOTAL+=$aux_total;
			echo'<td align="right">'.$aux_total.'</td>';
		}//fin meses
		echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
	?>
    </tbody>
    </table>
     <br /><br />
     <table border="1" width="100%">
    <thead>
    	<tr>
    		<th colspan="14">FLUJO CAJA Talca  <?php echo $year_consulta;?>(Cuotas Pendientes)</th>
      </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
    //-----------------------------------------inicio escritura deuda Talca------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	$sede_consulta="Talca";
		echo'<tr><td>Cuotas</td>';
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{
				$aux_total_X_concepto=0;
				$aux_total=0;
				
			$aux_total=0;	
			if(isset($ARRAY_CUOTAS_PENDIENTES[$aux_mes][$sede_consulta]))
			{$aux_total=$ARRAY_CUOTAS_PENDIENTES[$aux_mes][$sede_consulta];}
			
			
			$SUMA_TOTAL+=$aux_total;
			echo'<td align="right">'.$aux_total.'</td>';
		}//fin meses
		echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
	?>
    </tbody>
    </table>
     <br /><br />
     <table border="1" width="100%">
    <thead>
    	<tr>
    		<th colspan="14">FLUJO CAJA Linares  <?php echo $year_consulta;?>(Cuotas Pendientes)</th>
      </tr>
    	<tr>
    	  <th>Concepto</th>
    	  <th>Enero</th>
    	  <th>Febrero</th>
    	  <th>Marzo</th>
    	  <th>Abril</th>
    	  <th>Mayo</th>
    	  <th>Junio</th>
    	  <th>Julio</th>
    	  <th>Agosto</th>
    	  <th>Septiembre</th>
    	  <th>Octubre</th>
    	  <th>Noviembre</th>
    	  <th>Diciembre</th>
    	  <th>TOTAL</th>
      </tr>
     </thead>
     <tbody>     
    <?php
    //-----------------------------------------inicio escritura deuda Linares------------------------------------------------------------------///	
	$array_total_X_mes=array();	
	$SUMA_TOTAL=0;
	$sede_consulta="Linares";
		echo'<tr><td>Cuotas</td>';
		for($aux_mes=1;$aux_mes<=12;$aux_mes++)
		{
				$aux_total_X_concepto=0;
				$aux_total=0;
				
			$aux_total=0;	
			if(isset($ARRAY_CUOTAS_PENDIENTES[$aux_mes][$sede_consulta]))
			{$aux_total=$ARRAY_CUOTAS_PENDIENTES[$aux_mes][$sede_consulta];}
			
			
			$SUMA_TOTAL+=$aux_total;
			echo'<td align="right">'.$aux_total.'</td>';
		}//fin meses
		echo'<td align="right">'.$SUMA_TOTAL.'</td></tr>';
	?>
    </tbody>
    </table>
   <?php }else{ echo"sin Datos para generar... :(<br>";}?> 
</div>
</body>
</html>