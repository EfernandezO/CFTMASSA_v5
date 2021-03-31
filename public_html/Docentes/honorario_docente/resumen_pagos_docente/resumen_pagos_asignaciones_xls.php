<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("ver_resumen_pagos_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//



       
	if(DEBUG){ var_dump($_GET);}
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	$ARRAY_HONORARIO=array();
	
if($_GET)	
{
	$sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
	$semestre=mysqli_real_escape_string($conexion_mysqli,  base64_decode($_GET["semestre"]));
	$year=mysqli_real_escape_string($conexion_mysqli,  base64_decode($_GET["year"]));
	
	
	$html_tabla='<table width="100%" align="center" border="1">
<thead>
	<tr>
		<th colspan="15"> Estado pagos docente '.$sede.' Periodo ['.$semestre.' - '.$year.']</th>
	</tr>
	<tr>
    	<th>N.</th>
        <th>id funcionario</th>
        <th>Funcionario</th>';
	
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=resumen_pago_asignacion_docente[".$sede."_".$semeste."_".$year."].xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
	
	
	
	
	$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M, personal.nombre";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	
	$aux=0;
	$mes_menor=9999;
	$year_menor=9999;
	$max_num_registros=0;
	
	
	if($num_registros>0)
	{
		while($F=$sqli->fetch_row())
		{
			
			$id_funcionario=$F[0];
			$primera_vuelta=true;
			$cons_H="SELECT * FROM honorario_docente WHERE id_funcionario='$id_funcionario' AND sede='$sede' AND semestre='$semestre' AND year='$year' ORDER by year_generacion, mes_generacion";
			if(DEBUG){ echo"-->$cons_H<br>";}
			$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
			$num_honorarios=$sqli_H->num_rows;
			if($num_honorarios>0)
			{
				if($num_honorarios>$max_num_registros){$max_num_registros=$num_honorarios;}
				while($H=$sqli_H->fetch_assoc())
				{
					
					$id_honorario=$H["id_honorario"];
					$H_mes=$H["mes_generacion"];
					$H_valor=$H["total"];
					$H_estado=$H["estado"];
					$H_fecha_estado=$H["fecha_estado"];
					$H_year_generacion=$H["year_generacion"];
					if(DEBUG){ echo"$H_year_generacion -  $H_mes<br>";}
					//busco pagos previo al honorario
					if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
					$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
					if(DEBUG){echo"-->$consPP<br>";}
					$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
					$PP=$sqliPP->fetch_row();
					$pagosPrevios=$PP[0];
					if(empty($pagosPrevios)){$pagosPrevios=0;}
					$sqliPP->free();
					if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
					
					if($primera_vuelta){ 
					
							if($H_year_generacion<$year_menor){$year_menor=$H_year_generacion;}
							if($H_mes<$mes_menor){$mes_menor=$H_mes;}
							$primera_vuelta=false;
						}
					
					

					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["estado"]=$H_estado;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["valor"]=$H_valor;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["pagosPrevios"]=$pagosPrevios;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["id_honorario"]=$id_honorario;
					
				}
			}

		}
	}
	else
	{}
	$sqli->free();
}

if(DEBUG){echo"Mes menor: $mes_menor year menor: $year_menor num max registros: $max_num_registros<br>";}

$year_ini=$year_menor;	
$mes_ini=$mes_menor;

$mesx=$mes_ini;
$year_inix=$year_ini;
for($j=0;$j<$max_num_registros;$j++)
{
		
	$html_tabla.='<th>'.$mesx.'-'.$year_inix.'</th>';	
	$mesx++;
	if($mesx>12){$mesx=1; $year_inix++;}
}
$html_tabla.='</tr>
	</thead>
	<tbody>';



$ARRAY_TOTAL=array();
	
foreach($ARRAY_HONORARIO as $aux_id_funcionario => $array_1)
{
	
	
	$y=$year_menor;
	$aux++;
	$html_tabla.='<tr>
			<td align="center">'.$aux.'</td>
			<td align="center">'.$aux_id_funcionario.'</td>
			<td align="center">'.utf8_decode(NOMBRE_PERSONAL($aux_id_funcionario)).'</td>';
		
	if(DEBUG){ echo"<strong>--->id_funcionario: $aux_id_funcionario</strong><br>";}
	
	$mes=$mes_ini;
	for($i=0;$i<$max_num_registros;$i++)
	{
		if($mes>12){ $mes=1; $y++;}
		if(DEBUG){ echo"consultando: $mes - $y<br>";}
		if(isset($array_1[$y][$mes]))
		{
			$aux_estado=$array_1[$y][$mes]["estado"]; 
			$aux_valor=number_format($array_1[$y][$mes]["valor"],0,"",""); 
			$aux_pagosPrevios=number_format($array_1[$y][$mes]["pagosPrevios"],0,"",""); 
			$aux_id_honorario=$array_1[$y][$mes]["id_honorario"]; 
			
			//reemplazo de valor x pagosprevios
				if($aux_estado!=="pendiente"){$valorAguardar=$aux_pagosPrevios;}
				else{$valorAguardar=$aux_valor;}
			
				if(isset($ARRAY_TOTAL[$y][$mes][$aux_estado]))
				{$ARRAY_TOTAL[$y][$mes][$aux_estado]+=$valorAguardar;}
				else{$ARRAY_TOTAL[$y][$mes][$aux_estado]=$valorAguardar;}
			
			if($aux_estado=="cancelado"){ $color='#AAFFAA';}
			elseif($aux_estado=="abonado"){$color=" #e9efa4 ";}
			else{  $color='#FFAAAA';}
			
		}
		else
		{ $aux_estado=""; $aux_valor=""; $color=""; $aux_pagosPrevios="";}
		$html_tabla.='<td align="center" bgcolor="'.$color.'">'.@number_format($aux_valor,0,"","").'</td>';
		$mes++;
	}
	$html_tabla.='</tr>';
}

$conexion_mysqli->close();

$html_tabla.='<tr>
		<td colspan="3"><strong>Total Cancelado</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["cancelado"]))
		{$valor=$array_3["cancelado"];}
		else{$valor=0;}
		$html_tabla.='<td align="center"><strong>'.number_format($valor,0,"","").'</strong></td>';
		
	}
}
$html_tabla.='</tr>';
$html_tabla.='<tr>
		<td colspan="3"><strong>Total Abonado</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["abonado"]))
		{$valor=$array_3["abonado"];}
		else{$valor=0;}
		$html_tabla.='<td align="center"><strong>'.number_format($valor,0,",",".").'</strong></td>';
		
	}
}
$html_tabla.='</tr>';
$html_tabla.='<tr>
		<td colspan="3"><strong>Total Pendiente</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["pendiente"]))
		{$valor=$array_3["pendiente"];}
		else{$valor=0;}
		$html_tabla.='<td align="center"><strong>'.number_format($valor,0,"","").'</strong></td>';
		
	}
}
$html_tabla.='</tr>';
$html_tabla.='</table>';

echo $html_tabla;
?>