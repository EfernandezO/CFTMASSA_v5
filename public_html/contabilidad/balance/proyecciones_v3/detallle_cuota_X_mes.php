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
<h1 id="banner">Administrador - Cuotas X Mes</h1>
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
		{ $condicion_nivel="alumno.nivel='$nivel' AND";}
		else
		{ $condicion_nivel="";}
		
		if($jornada!="0")
		{ $condicion_jornada="alumno.jornada='$jornada' AND";}
		else
		{ $condicion_jornada="";}
		
		if($situacion_academica!="0")
		{ $condicion_situacion_academica="alumno.situacion='$situacion_academica' AND";}
		else
		{ $condicion_situacion_academica="";}
		
		if($id_carrera!="0")
		{ $condicion_carrera="alumno.id_carrera='$id_carrera' AND";}
		else
		{ $condicion_carrera="";}
		
		if($year_ingreso!="0"){ $condicion_year_ingreso="alumno.ingreso='$year_ingreso' AND";}
		else{ $condicion_year_ingreso="";}
		
		
		echo"<strong>Proyecciones Año $year - Sede: $sede</br>Carrera: $id_carrera $nombre_carrera Year ingreso: $year_ingreso Nivel: $nivel - Jornada: $jornada</strong><br><strong>Tipo de Morosidad:</strong> ";
	foreach($ARRAY_TIPO_MOROSIDAD as $j=>$k)
	{echo"$k ";}
	echo"<br><strong>Situacion Academica:</strong> $situacion_academica<br><br>";
	
		if($mes>0)
		{
			
			$fecha_ini=$array_vencimientos[$m]["inicio"];
			$fecha_fin=$array_vencimientos[$m]["fin"];
			
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
    <th rowspan="2">N</th>
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
    <tbody>
<?php
		
		$cons_CUO="SELECT letras.id, letras.idalumn, letras.id_contrato, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.ingreso, alumno.nivel, alumno.situacion, letras.fechavenc, letras.valor, letras.deudaXletra, letras.semestre, letras.ano, letras.sede FROM letras INNER JOIN alumno ON letras.idalumn=alumno.id WHERE $condicion_carrera $condicion_nivel $condicion_year_ingreso  letras.ano='$year' AND alumno.sede='$sede' AND letras.tipo='cuota' $condicion_mes ORDER by sede, carrera, fechavenc, alumno.apellido_P, alumno.apellido_M";
		
		
		$sql_CUO=$conexion_mysqli->query($cons_CUO)or die($conexion_mysqli->error);
		$num_cuotas_encontradas=$sql_CUO->num_rows;
		
		if(DEBUG){ echo"<br><br>-->$cons_CUO<br>Num cuotas: $num_cuotas_encontradas<br><br>";}
		
		if($num_cuotas_encontradas>0)
		{
			$SUMA_TOTAL_VALOR=0;
			$SUMA_TOTAL_DEUDA=0;
			$contador=0;
			while($L=$sql_CUO->fetch_assoc())	
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
				
				
				$aux_dias_morosidad=DIAS_MOROSIDAD($A_id);
				$aux_tipo_morosidad=TIPO_MOROSIDAD($aux_dias_morosidad);
				
				if(in_array($aux_tipo_morosidad, $ARRAY_TIPO_MOROSIDAD))
				{$utilizar_alumno=true;}
				else
				{$utilizar_alumno=false;}
					
					if(DEBUG){ echo" <b>$A_id</b> $C_id - $C_valor - $C_deuda - $C_vence - $C_semestre - $C_year - $A_carrera - $A_sede - [$C_id_contrato]<br>";}
					
				
				if($utilizar_alumno)	
				{
					$SUMA_TOTAL_DEUDA+=$C_deuda;
					$SUMA_TOTAL_VALOR+=$C_valor;
					
					$contador++;
					
					$cons_C="SELECT * FROM contratos2 WHERE id='$C_id_contrato' AND id_alumno='$A_id'";
					if(DEBUG){ echo":---> $cons_C<br>";}
					$sql_C=mysql_query($cons_C)or die("contratos ".mysql_error());
					$CNT=mysql_fetch_assoc($sql_C);
					mysql_free_result($sql_C);
					
					$CNT_linea_credito=$CNT["linea_credito_paga"];
					$CNT_n_cuotas=$CNT["numero_cuotas"];
					
					
					echo'<tr>
						<td>'.$contador.'</td>
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
						<td align="center">'.fecha_format($C_vence).'</td>
						<td align="right">'.number_format($C_valor,0,",",".").'</td>
						<td align="right">'.number_format($C_deuda,0,",",".").'</td>
					</tr>';
				}
			}
			if($contador==0)
			{
				echo'<tr><td colspan="15">No se encontraron Cuotas que cumplan condicion</td></tr>';
			}
			
			if(DEBUG){ echo"<br>TOTAL V----->$SUMA_TOTAL_VALOR  TOTAL D --->$SUMA_TOTAL_DEUDA<br>";}
			echo'<tr>
					<td><strong>TOTALES</strong></td>
					<td colspan="12">&nbsp;</td>
					<td align="right"><strong>'.number_format($SUMA_TOTAL_VALOR,0,",",".").'</strong></td>
					<td align="right"><strong>'.number_format($SUMA_TOTAL_DEUDA,0,",",".").'</strong></td>
				</tr>';
			
		}
		else
		{
			echo'<tr><td colspan="15">No se encontraron Cuotas</td></tr>';
		}
	$sql_CUO->free();	
	mysql_close($conexion);
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