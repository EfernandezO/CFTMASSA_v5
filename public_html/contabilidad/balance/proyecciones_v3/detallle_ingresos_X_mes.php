<?php 
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Proyecciones_v3");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Proyecciones Anuales - Cuotas X Vencimiento</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 59px;
}
</style>
</head>
<?php
if($_GET)
{
	$continuar=true;
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$mes=base64_decode($_GET["mes"]);
	$year_ingreso=base64_decode($_GET["year_ingreso"]);
	$year=base64_decode($_GET["year"]);
	$sede=base64_decode($_GET["sede"]);
	$jornada=base64_decode($_GET["jornada"]);
	$nivel=base64_decode($_GET["nivel"]);
	$situacion_academica=base64_decode($_GET["situacion_academica"]);
	$ARRAY_TIPO_MOROSIDAD=unserialize(urldecode($_GET["tipo_morosidad"]));
	
	if((!is_numeric($id_carrera))or($id_carrera<=0))
	{ $continuar=false; if(DEBUG){ echo"id_carrera incorrecto<br>";}}
	
	//echo"Mes: $mes<br>";
	if((!is_numeric($mes))or($mes<0))
	{ $continuar=false; if(DEBUG){ echo"Mes incorrecto <br>";}}
	
	if(!is_numeric($year))
	{ $continuar=false; if(DEBUG){ echo"year incorrecto<br>";}}
	
	if(!is_numeric($nivel))
	{
		if($nivel!=="Todos")
		{ $continuar=false; if(DEBUG){ echo"Nivel incorrecto<br>";}} 
	}
	
	//--------------------------------------------//
	
	$year_proyeccion=$year;
	$year_avance=0;
		if($mes>12)
		{
			$year_avance=floor(($mes/12));
			$mes=($mes-($year_avance*12));
			 //echo"Year avance: $year_avance MES: $aux_mes<br>";
		}
	$year_proyeccion+=$year_avance;	
		
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
	
	
	if(DEBUG){var_dump($array_vencimientos);}
	
}
else{ $continuar=false;}
	
	
?>
<body>
<h1 id="banner">Administrador - Ingresos de Alumnos realizados en el Mes</h1>
<div id="apDiv1">
<?php
if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/funciones_sistema.php");
	include("../../../../funciones/VX.php");
	
	//--------------------------------------//
	$evento="Revisa Proyecciones Anuales V3 -> Detalle de Mes: $mes para year: $year Sede: $sede id_carrera: $id_carrera nivel: $nivel jornada: $jornada situacion_academica: $situacion_academica";
	REGISTRA_EVENTO($evento);
	//------------------------------------------//
	if($id_carrera>0)
	{
		
		$cons_ca="SELECT carrera FROM carrera WHERE id='$id_carrera' LIMIT 1";
		$sqli_ca=$conexion_mysqli->query($cons_ca);
		$CA=$sqli_ca->fetch_assoc();
			$nombre_carrera=$CA["carrera"];
		$sqli_ca->free();
	}
	else
	{ $nombre_carrera="Todas";}
	
	
		if(DEBUG){ var_dump($_GET);}
		
		if($nivel!="0")
		{ $condicion_nivel="AND alumno.nivel='$nivel'";}
		else
		{ $condicion_nivel="";}
		
		if($jornada!="0")
		{ $condicion_jornada="AND alumno.jornada='$jornada'";}
		else
		{ $condicion_jornada="";}
		
		if($situacion_academica!="0")
		{ $condicion_situacion_academica="AND alumno.situacion='$situacion_academica'";}
		else
		{ $condicion_situacion_academica="";}
		
		if($id_carrera!="0")
		{ $condicion_carrera="AND alumno.id_carrera='$id_carrera'";}
		else
		{ $condicion_carrera="";}
		
		if($year_ingreso!="0"){ $condicion_year_ingreso="AND alumno.ingreso='$year_ingreso'";}
		else{ $condicion_year_ingreso="";}
		
		
		echo"<strong>Proyecciones Año $year - Sede: $sede</br>Carrera: $id_carrera $nombre_carrera Year ingreso: $year_ingreso Nivel: $nivel - Jornada: $jornada</strong><br><strong>Tipo de Morosidad:</strong> ";
	foreach($ARRAY_TIPO_MOROSIDAD as $j=>$k)
	{echo"$k ";}
	echo"<br><strong>Situacion Academica:</strong> $situacion_academica<br><br>";
	
		if($mes>0)
		{
			
			$fecha_ini=$array_vencimientos[$m]["inicio"];
			$fecha_fin=$array_vencimientos[$m]["fin"];
			
			$time_inicio=strtotime($fecha_ini);
			$time_fin=strtotime($fecha_fin);
			
			$condicion_mes="AND letras.fechavenc BETWEEN '$fecha_ini' AND '$fecha_fin'";
		}
		else{ $condicion_mes="";}
		
	
?>
<strong>Carrera:</strong> <?php echo $id_carrera;?> <strong>Sede:</strong> <?php echo $sede;?> <strong>Nivel:</strong> <?php echo $nivel;?><br />
<strong>Mes Proyeccion:</strong> <?php echo $mes;?> <strong>Año Cuotas:</strong> <?php echo $year;?><br />
<strong>Periodo de Vencimiento</strong> <?php echo "[$fecha_ini - $fecha_fin]<br>";?> 
<table border="1" align="center" width="100%">
<thead>
	<tr>
    <th>N</th>
    <th>Situacion</th>
    <th>Ingreso</th>
    <th>Nivel</th>
    <th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Semestre Cuota</th>
    <th>Year Cuota</th>
    <th>Vencimiento Cuota</th>
    <th>Fecha Pago</th>
    <th>Forma Pago</th>
    <th>Valor Pago</th>
    <tbody>
<?php
		
		$cons_A="SELECT DISTINCT(idalumn) FROM letras INNER JOIN  alumno ON letras.idalumn = alumno.id WHERE tipo='cuota' AND alumno.sede='$sede' AND letras.ano='$year' $condicion_carrera $condicion_nivel $condicion_year_ingreso $condicion_jornada $condicion_situacion_academica ORDER by idalumn";
		
		
		if(DEBUG){ echo"<br><br>-->$cons_A<br><br>";}
		$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		$num_alumnos=$sql_A->num_rows;
		if(DEBUG){echo "$cons_A<br>N. $num_alumnos<br>";}
		$contador_pagos=0;
		if($num_alumnos>0)
		{
			$SUMA_TOTAL_VALOR=0;
			$SUMA_TOTAL_DEUDA=0;
			$SUMA_PAGOS_PERIODO=0;
			$SUMA_PAGO_FUERA_PERIODO=0;
			$SUMA_TOTAL=0;
			$contador=0;
			while($L=$sql_A->fetch_row())	
			{
				$A_id=$L[0];
				
				$aux_dias_morosidad=DIAS_MOROSIDAD($A_id);
				$aux_tipo_morosidad=TIPO_MOROSIDAD($aux_dias_morosidad);
				
				if(in_array($aux_tipo_morosidad, $ARRAY_TIPO_MOROSIDAD))
				{$utilizar_alumno=true;}
				else
				{$utilizar_alumno=false;}
				
				if($utilizar_alumno)	
				{
					$cons_pagos_cuotas_otros_meses="SELECT alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.ingreso, alumno.carrera, alumno.sede, alumno.nivel, alumno.situacion, pagos.valor, pagos.fechapago, pagos.forma_pago, pagos.idpago, pagos.id_cuota FROM pagos INNER JOIN alumno ON pagos.id_alumno=alumno.id  WHERE pagos.por_concepto='arancel' AND alumno.id ='$A_id' AND pagos.fechapago BETWEEN '$fecha_ini' AND '$fecha_fin'";
						$sqli_pagos_otros_meses=$conexion_mysqli->query($cons_pagos_cuotas_otros_meses)or die("PAGOS TOTALES ".$conexion_mysqli->error);
						$num_pagos=$sqli_pagos_otros_meses->num_rows;
						if(DEBUG){ echo"---><strong>Pagos de cuotas </strong> $cons_pagos_cuotas_otros_meses<br>N. $num_pagos<br>";}
						if($num_pagos>0)
						{
							while($POM=$sqli_pagos_otros_meses->fetch_assoc())
							{
								$contador_pagos++;
						
								
								$A_rut=$POM["rut"];
								$A_nombre=$POM["nombre"];
								$A_apellido_P=$POM["apellido_P"];
								$A_apellido_M=$POM["apellido_M"];
								
								$A_year_ingreso=$POM["ingreso"];
								$A_carrera=$POM["carrera"];
								$A_sede=$POM["sede"];
								$A_nivel=$POM["nivel"];
								$A_situacion=$POM["situacion"];
								
								$aux_id_pago=$POM["idpago"];
								$aux_id_cuota=$POM["id_cuota"];
								$aux_fecha_pago=$POM["fechapago"];
								$aux_forma_pago=$POM["forma_pago"];
								$aux_pago_valor=$POM["valor"];
							
								$SUMA_TOTAL+=$aux_pago_valor;
								
								////datos de cuota
								$cons_C1="SELECT * FROM letras WHERE id='$aux_id_cuota' LIMIT 1";
								//echo $cons_C1;
								$sqli_C1=$conexion_mysqli->query($cons_C1)or die($conexion_mysqli->error);
								$C1=$sqli_C1->fetch_assoc();
									$C_valor=$C1["valor"];
									$C_vencimiento=$C1["fechavenc"];
									$C_semestre=$C1["semestre"];
									$C_year=$C1["ano"];
								$sqli_C1->free();
								
								
								echo'<tr>
										<td>'.$contador_pagos.'</td>
										<td>'.$A_situacion.'</td>
										<td>'.$A_year_ingreso.'</td>
										<td>'.$A_nivel.'</td>
										<td>'.$A_rut.'</td>
										<td>'.$A_nombre.'</td>
										<td>'.$A_apellido_P.'</td>
										<td>'.$A_apellido_M.'</td>
										
										<td>'.$C_semestre.'</td>
										<td>'.$C_year.'</td>
										<td>'.$C_vencimiento.'</td>
										
										<td>'.$aux_fecha_pago.'</td>
										<td>'.$aux_forma_pago.'</td>
										<td align="right">$'.number_format($aux_pago_valor,0,",",".").'</td>
									</tr>';
							}
						}
						$sqli_pagos_otros_meses->free();	
						
	
				}
			}
			echo'
				<tr>
					<td colspan="2"><strong>TOTALES</strong></td>
					<td colspan="11">&nbsp;</td>
					<td align="right"><strong>'.number_format($SUMA_TOTAL,0,",",".").'</strong></td>
				</tr>';
			
		}
		else
		{
		}
	$sql_A->free();	
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{
	echo"No se puede continuar";
}
///////////////////////////////////

///////////////////////
?>
</tbody>
</table>
</div>
</body>
</html>