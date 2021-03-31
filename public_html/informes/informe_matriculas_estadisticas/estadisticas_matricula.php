<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Comparador_matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>estadisticas Matricula</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:80%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 172px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:2;
	left: 0%;
	top: 282px;
}
#apDiv3 {
	position:absolute;
	width:80%;
	height:54px;
	z-index:3;
	left: 5%;
	top: 100px;
	text-align: center;
	border: medium ridge #60F;
}
#apDiv4 {
	position:absolute;
	width:33%;
	height:115px;
	z-index:4;
	left: 65%;
	top: 189px;
}
</style>
<script language="javascript">
function mostrar_informacion(dato_1, dato_2, dato_3)
{
	alert("Hoy: +"+dato_1+"\n Total a la Fecha:"+dato_2);
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Matriculas Estadisticas</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver al Seleccion</a></div>
<div id="apDiv1">
<?php
if($_POST)
{
	$graficar=true;
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
		if(DEBUG){ var_export($_POST);}
		$array_mes_consulta=str_inde($_POST["mes_consulta"]);
		$year_consulta_1=str_inde($_POST["year_consulta_1"]);
		$year_consulta_2=str_inde($_POST["year_consulta_2"]);
		$tipo_alumnos=str_inde($_POST["tipo_alumnos"]);
		
		$array_mes_consulta=mysql_real_escape_string($array_mes_consulta);
		$year_consulta_1=mysql_real_escape_string($year_consulta_1);
		$year_consulta_2=mysql_real_escape_string($year_consulta_2);
		$tipo_alumnos=mysql_real_escape_string($tipo_alumnos);
		
		$array_mes_consulta=explode("_",$array_mes_consulta);
		$mes_consulta=$array_mes_consulta[0];
		$mes_consulta_nombre=$array_mes_consulta[1];
		
		////////////////////////////////////////////////////////////
		$ARRAY_YEAR_1=array();
		$ARRAY_YEAR_2=array();
		////////////////////////////////////////////////////////////
		//consulta 1
		
		if($mes_consulta<10){$mes_consulta="0".$mes_consulta; $fecha_corte="$year_consulta_1-$mes_consulta-01";}
		else{$fecha_corte=($year_consulta_1-1)."-$mes_consulta-01";}
		
		switch($tipo_alumnos)
		{
			case"todos":
				$cons_1="SELECT *, MONTH(fecha_generacion) AS mes FROM contratos2 WHERE MONTH(fecha_generacion)=$mes_consulta AND ano='$year_consulta_1'ORDER by sede, ano, fecha_generacion";
				$tipo_alumnos_label="todos los alumnos";
				$cons_1_full="SELECT contratos2.id, contratos2.sede FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE ano='$year_consulta_1' AND fecha_generacion<'$fecha_corte'ORDER by contratos2.sede, ano, fecha_generacion";
				
				break;
			case"nuevos":
				$cons_1="SELECT contratos2.*,MONTH( contratos2.fecha_generacion) AS mes, contratos2.yearIngresoCarrera FROM contratos2 WHERE MONTH(fecha_generacion)='$mes_consulta' AND ano='$year_consulta_1' AND contratos2.yearIngresoCarrera='$year_consulta_1' ORDER by sede, ano, fecha_generacion";
				
				$tipo_alumnos_label="los nuevos alumnos";

				$cons_1_full="SELECT contratos2.id, contratos2.sede FROM contratos2 WHERE ano='$year_consulta_1' AND contratos2.yearIngresoCarrera='$year_consulta_1' AND fecha_generacion<'$fecha_corte'ORDER by contratos2.sede, ano, fecha_generacion";
				break;
		}
		////////////////////////////////////////////
		$total_contratos_antes_periodo_talca_1=0;
		$total_contratos_antes_periodo_linares_1=0;
		$sql_full_1=mysql_query($cons_1_full)or die("FULL 1".mysql_error());
		$num_registros_full_1=mysql_num_rows($sql_full_1);
		if($num_registros_full_1>0)
		{
			while($DF1=mysql_fetch_assoc($sql_full_1))
			{
				$aux_sede_contrato=$DF1["sede"];
				switch($aux_sede_contrato)
				{
					case"Talca":
						$total_contratos_antes_periodo_talca_1+=1;
						break;
					case"Linares":
						$total_contratos_antes_periodo_linares_1+=1;
						break;
				}
			}
		}
		mysql_free_result($sql_full_1);	
		if(DEBUG){ echo"<br><br>FULL 1: $cons_1_full<br> Total full 1 talca: $total_contratos_antes_periodo_talca_1<br>full linares: $total_contratos_antes_periodo_linares_1<br>";}
		
		//-----------------------------------------------------------------------------//
		
		$sql_1=mysql_query($cons_1)or die(mysql_error());
		$num_registros_1=mysql_num_rows($sql_1);
		if(DEBUG){ echo"<br>$cons_1<br>Registros: $num_registros_1<br>";}
		if($num_registros_1>0)
		{
			while($C1=mysql_fetch_assoc($sql_1))
			{
				$C_id=$C1["id"];
				$C_fecha_generacion=$C1["fecha_generacion"];
				$array_fecha_generacion=explode("-", $C_fecha_generacion);
					$dia_generacion=abs($array_fecha_generacion[2]);
				$C_mes=$C1["mes"];
				$C_sede=$C1["sede"];
				$C_year=$C1["ano"];
				
				if(DEBUG){ echo"$C_id - $C_fecha_generacion - [$C_mes / $dia_generacion] - $C_sede - $C_year<br>";}
				
				if(isset($ARRAY_YEAR_1[$C_sede][$dia_generacion]))
				{ $ARRAY_YEAR_1[$C_sede][$dia_generacion]+=1;}
				else{ $ARRAY_YEAR_1[$C_sede][$dia_generacion]=1;}
			}
		}
		else
		{
			if(DEBUG){ echo"Sin Registros consulta 1<br>";}
		}
		mysql_free_result($sql_1);
		
		if(DEBUG){ var_dump($ARRAY_YEAR_1);}
		//consulta 2
		if($mes_consulta<10){$fecha_corte="$year_consulta_2-$mes_consulta-01";}
		else{$fecha_corte=($year_consulta_2-1)."-$mes_consulta-01";}
		
		switch($tipo_alumnos)
		{
			case"todos":
				$cons_2="SELECT *, MONTH(fecha_generacion) AS mes FROM contratos2 WHERE MONTH(fecha_generacion)=$mes_consulta AND ano='$year_consulta_2'ORDER by sede, ano, fecha_generacion";
				$cons_2_full="SELECT contratos2.id, contratos2.sede FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE ano='$year_consulta_2' AND fecha_generacion<'$fecha_corte'ORDER by contratos2.sede, ano, fecha_generacion";
				
				break;
			case"nuevos":
				$cons_2="SELECT contratos2.*,MONTH( contratos2.fecha_generacion) AS mes, contratos2.yearIngresoCarrera FROM contratos2  WHERE MONTH(fecha_generacion)=$mes_consulta AND ano='$year_consulta_2' AND contratos2.yearIngresoCarrera='$year_consulta_2' ORDER by sede, ano, fecha_generacion";
				
			
				$cons_2_full="SELECT contratos2.id, contratos2.sede FROM contratos2 WHERE ano='$year_consulta_2' AND contratos2.yearIngresoCarrera='$year_consulta_2' AND fecha_generacion<'$fecha_corte'ORDER by contratos2.sede, ano, fecha_generacion";
				break;
		}
		$total_contratos_antes_periodo_talca_2=0;
		$total_contratos_antes_periodo_linares_2=0;
		$sql_full_2=mysql_query($cons_2_full)or die("FULL 2".mysql_error());
		$num_registros_full_2=mysql_num_rows($sql_full_2);
		if($num_registros_full_2>0)
		{
			while($DF2=mysql_fetch_assoc($sql_full_2))
			{
				$aux_sede_contrato=$DF2["sede"];
				switch($aux_sede_contrato)
				{
					case"Talca":
						$total_contratos_antes_periodo_talca_2+=1;
						break;
					case"Linares":
						$total_contratos_antes_periodo_linares_2+=1;
						break;
				}
			}
		}
		mysql_free_result($sql_full_2);	
		if(DEBUG){ echo"<br><br>FULL 2: $cons_2_full<br> Total full 2 talca: $total_contratos_antes_periodo_talca_2<br>full linares: $total_contratos_antes_periodo_linares_2<br>";}
		
		$sql_2=mysql_query($cons_2)or die(mysql_error());
		$num_registros_2=mysql_num_rows($sql_2);
		if(DEBUG){ echo"<br>$cons_2<br>Registros: $num_registros_2<br>";}
		if($num_registros_2>0)
		{
			while($C2=mysql_fetch_assoc($sql_2))
			{
				$C_id=$C2["id"];
				$C_fecha_generacion=$C2["fecha_generacion"];
				$array_fecha_generacion=explode("-", $C_fecha_generacion);
					$dia_generacion=abs($array_fecha_generacion[2]);
				$C_mes=$C2["mes"];
				$C_sede=$C2["sede"];
				$C_year=$C2["ano"];
				
				if(DEBUG){ echo"$C_id - $C_fecha_generacion - [$C_mes / $dia_generacion] - $C_sede - $C_year<br>";}
				
				if(isset($ARRAY_YEAR_2[$C_sede][$dia_generacion]))
				{ $ARRAY_YEAR_2[$C_sede][$dia_generacion]+=1;}
				else{ $ARRAY_YEAR_2[$C_sede][$dia_generacion]=1;}
			}
		}
		else
		{
			if(DEBUG){ echo"Sin Registros consulta 2<br>";}
		}
		mysql_free_result($sql_2);
		
		if(DEBUG){ var_dump($ARRAY_YEAR_2);}
		
	mysql_close($conexion);
	/////////////////////////////////////////////////////////////////////////////////
	$primer_dia_mes=1;
	$ultimo_dia_mes_1=ULTIMO_DIA_MES($mes_consulta, $year_consulta_1);
	$ultimo_dia_mes_2=ULTIMO_DIA_MES($mes_consulta, $year_consulta_2);
	////////////////////////////////////////////////////////////////////////////////
	
	
	///recorro arreglos para graficar 1
	$aux=true;
	$max_1=0;
	$aux_max_2=0;
	$label="";
	$num_matriculas_talca_1=0;
	$num_matriculas_linares_1=0;
	$total_talca_1=0;
	$total_linares_1=0;
	///recorro arreglos para graficar 2
	$aux=true;
	$max_2=0;
	$aux_max_1=0;
	$label="";
	$num_matriculas_talca_2=0;
	$num_matriculas_linares_2=0;
	$total_talca_2=0;
	$total_linares_2=0;
	
	$total_full_talca_1=$total_contratos_antes_periodo_talca_1;
	$total_full_talca_2=$total_contratos_antes_periodo_talca_2;
	$total_full_linares_1=$total_contratos_antes_periodo_linares_1;
	$total_full_linares_2=$total_contratos_antes_periodo_linares_2;
	$tabla_comparativa='<table border="1" width="55%">
						<thead>
							<tr>
								<th colspan="5">Comparativa Matriculas Acumuladas del Mes</th>
							</tr>
							<tr>
								<td rowspan="2">Dia</td>
								  <td colspan="2">Talca</td>
								  <td colspan="2">Linares</td>
								</tr>
								<tr>
								  <td>'.$year_consulta_1.'</td>
								  <td>'.$year_consulta_2.'</td>
								  <td>'.$year_consulta_1.'</td>
								  <td>'.$year_consulta_2.'</td>
							</tr>
						</thead>';
	$posicion_celda=0;					
	for($x=1;$x<=$ultimo_dia_mes_2;$x++)
	{
		if(isset($ARRAY_YEAR_2["Talca"][$x]))
		{ $aux_consulta_dia_talca_2=$ARRAY_YEAR_2["Talca"][$x];}
		else{ $aux_consulta_dia_talca_2=0;}
		
		if(isset($ARRAY_YEAR_2["Linares"][$x]))
		{ $aux_consulta_dia_linares_2=$ARRAY_YEAR_2["Linares"][$x];}
		else{ $aux_consulta_dia_linares_2=0;}
		
		if(isset($ARRAY_YEAR_1["Talca"][$x]))
		{ $aux_consulta_dia_talca_1=$ARRAY_YEAR_1["Talca"][$x];}
		else{ $aux_consulta_dia_talca_1=0;}
		
		if(isset($ARRAY_YEAR_1["Linares"][$x]))
		{ $aux_consulta_dia_linares_1=$ARRAY_YEAR_1["Linares"][$x];}
		else{ $aux_consulta_dia_linares_1=0;}
		
		///determino maximo
		if($aux_consulta_dia_talca_2>$aux_consulta_dia_linares_2)
		{ $aux_max=$aux_consulta_dia_talca_2;}
		else
		{ $aux_max=$aux_consulta_dia_linares_2;}
		
		if($aux_max_2>$max_2)
		{$max_2=$aux_max_2;}
		//////
		///determino maximo
		if($aux_consulta_dia_talca_1>$aux_consulta_dia_linares_1)
		{ $aux_max=$aux_consulta_dia_talca_1;}
		else
		{ $aux_max=$aux_consulta_dia_linares_1;}
		
		if($aux_max_1>$max_1)
		{$max_1=$aux_max_1;}
		//////
		//cuento matriculas x sede
		$total_linares_1+=$aux_consulta_dia_linares_1;
		$total_talca_1+=$aux_consulta_dia_talca_1;
		//cuento matriculas x sede
		$total_linares_2+=$aux_consulta_dia_linares_2;
		$total_talca_2+=$aux_consulta_dia_talca_2;
		
		$total_full_talca_1+=$aux_consulta_dia_talca_1;
		$total_full_talca_2+=$aux_consulta_dia_talca_2;
		$total_full_linares_1+=$aux_consulta_dia_linares_1;
		$total_full_linares_2+=$aux_consulta_dia_linares_2;
		
		if(DEBUG){ echo"--->$x T: $aux_consulta_dia_talca L: $aux_consulta_dia_linares<br>";}
				if($aux)
				{
					$label.="|$x|";
					$num_matriculas_talca_2.="$aux_consulta_dia_talca_2";
					$num_matriculas_linares_2.="$aux_consulta_dia_linares_2";
					
					$num_matriculas_talca_1.="$aux_consulta_dia_talca_1";
					$num_matriculas_linares_1.="$aux_consulta_dia_linares_1";
					$aux=false;
				}
				else
				{
					$num_matriculas_talca_2.=",$aux_consulta_dia_talca_2";
					$num_matriculas_linares_2.=",$aux_consulta_dia_linares_2";
					
					$num_matriculas_talca_1.=",$aux_consulta_dia_talca_1";
					$num_matriculas_linares_1.=",$aux_consulta_dia_linares_1";
					$label.="$x|";
				}
				
				$tabla_comparativa.='<tr>
								<td align="center"><strong>'.$x.'</strong></td>
								<td align="center" id="celda_'.($posicion_celda+1).'">
								<a href="#celda_'.($posicion_celda+1).'" title="Hoy +'.$aux_consulta_dia_talca_1.' A la Fecha['.$total_full_talca_1.']" onclick="mostrar_informacion('.$aux_consulta_dia_talca_1.', '.$total_full_talca_1.', '.$total_talca_1.');">'.$total_talca_1.'</a></td>
								
								<td align="center" id="celda_'.($posicion_celda+2).'">
								<a href="#" title="Hoy +'.$aux_consulta_dia_talca_2.' A la Fecha['.$total_full_talca_2.']" onclick="mostrar_informacion('.$aux_consulta_dia_talca_2.', '.$total_full_talca_2.', '.$total_talca_2.');">'.$total_talca_2.'</a></td>
								
								<td align="center" id="celda_'.($posicion_celda+3).'">
								<a href="#celda_'.($posicion_celda+3).'" title="Hoy +'.$aux_consulta_dia_linares_1.'  A la Fecha['.$total_full_linares_1.']" onclick="mostrar_informacion('.$aux_consulta_dia_linares_1.', '.$total_full_linares_1.', '.$total_linares_1.');">'.$total_linares_1.'</a></td>
								
								<td align="center" id="celda_'.($posicion_celda+4).'">
								<a href="#celda_'.($posicion_celda+4).'" title="Hoy +'.$aux_consulta_dia_linares_2.'  A la Fecha['.$total_full_linares_2.']" onclick="mostrar_informacion('.$aux_consulta_dia_linares_2.', '.$total_full_linares_2.', '.$total_linares_2.');">'.$total_linares_2.'</a></td>
								</tr>';
			$posicion_celda+=4;					
	}
	$tabla_comparativa.='</table>';
	////////////////////////////////////////////////////////////////////////////////////////////
	
	//grafico 1
		require("../../../funciones/G_chart.php");
		$array_grafico_1["tipo"]="lc";//"bvs";"lc"
		$array_grafico_1["datos"][]=$num_matriculas_talca_1;
		$array_grafico_1["datos"][]=$num_matriculas_talca_2;
		$array_grafico_1["rango_X"]=$label;
		$array_grafico_1["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico_1["dato_max"]=$max_1;
		$array_grafico_1["etiqueta_izquierda"]="matriculas";
		$array_grafico_1["etiqueta_inferior"]="dias";
		$array_grafico_1["titulo"]="Matriculas $mes_consulta_nombre - Talca";
		$array_grafico_1["simbologia"]="$year_consulta_1($total_talca_1)|$year_consulta_2($total_talca_2)";
		$array_grafico_1["colores_lineas_hex"]="1673C0,FA0000";
		$array_grafico_1["color_titulo_hex"]="F10000";
		$array_grafico_1["size_titulo"]=20;
	///////////////////////////////////////////////////////////////////////	
	
	//grafico 2
		$array_grafico_2["tipo"]="lc";//"bvs";"lc"
		$array_grafico_2["datos"][]=$num_matriculas_linares_1;
		$array_grafico_2["datos"][]=$num_matriculas_linares_2;
		$array_grafico_2["rango_X"]=$label;
		$array_grafico_2["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico_2["dato_max"]=$max_2;
		$array_grafico_2["etiqueta_izquierda"]="matriculas";
		$array_grafico_2["etiqueta_inferior"]="dias";
		$array_grafico_2["titulo"]="Matriculas $mes_consulta_nombre - Linares";
		$array_grafico_2["simbologia"]="$year_consulta_1($total_linares_1)|$year_consulta_2($total_linares_2)";
		$array_grafico_2["colores_lineas_hex"]="1673C0,FA0000";
		$array_grafico_2["color_titulo_hex"]="F10000";
		$array_grafico_2["size_titulo"]=20;
	
	/////////////////////////////////////////////////////////////////////////
		if($graficar)
		{
			GRAFICO_GOOGLE($array_grafico_1);
			GRAFICO_GOOGLE($array_grafico_2);
		}	
		unset($array_grafico_1);
		unset($array_grafico_2);
}
else
{header("location: index.php");}

function ULTIMO_DIA_MES($mes, $year)
{
	switch($mes)
	{
		case"1":
			$ultimo_dia_mes=31;
			break;
		case"2":
			if($year%4==0)
			{ $ultimo_dia_mes=29;}
			else
			{ $ultimo_dia_mes=28;}
			break;
		case"3":
			$ultimo_dia_mes=31;
			break;
		case"4":
			$ultimo_dia_mes=30;
			break;
		case"5":
			$ultimo_dia_mes=31;
			break;
		case"6":
			$ultimo_dia_mes=30;
			break;					
		case"7":
			$ultimo_dia_mes=31;
			break;
		case"8":
			$ultimo_dia_mes=31;
			break;
		case"9":
			$ultimo_dia_mes=30;
			break;
		case"10":
			$ultimo_dia_mes=31;
			break;
		case"11":
			$ultimo_dia_mes=30;
			break;
		case"12":
			$ultimo_dia_mes=31;
			break;						
	}
	return($ultimo_dia_mes);
}
?>

<?php echo $tabla_comparativa;?>
</div>

<div id="apDiv3">Comparativa de Matriculas para  <?php echo $tipo_alumnos_label;?> matriculados<br />
  En el mes de <?php echo $mes_consulta_nombre;?> en los a&ntilde;os <?php echo"$year_consulta_1 y $year_consulta_2";?>
</div>

</body>
</html>