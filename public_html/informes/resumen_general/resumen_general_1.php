<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	set_time_limit(360);
	define("DEBUG",false);
	$tiempo_inicio_script = microtime(true);
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////

$tipo_programa="todos";

$id_carrera=0;
$carrera="todas";
$ano_ingreso="Todos";
$jornada="T";
$situacion="A";
$grupo="Todos";

$nivel=array(1,2,3,4,5);
$estado_financiero="Todos";
//---------------------------------------------//
//Periodo
$year_actual=date("Y");
$mes_actual=date("m");

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}
//--------------------------------------------//
$verificar_contrato=true;
$no_mostrar_retirados=false;
/////////////////////////////

$condicion=" contratos2.condicion<>'inactivo'";

if($id_carrera>0)
{ $condicion.=" AND alumno.id_carrera='$id_carrera'";}

if($ano_ingreso!="Todos")
{$condicion.=" AND alumno.ingreso='$ano_ingreso'";}

if($jornada!="T")
{$condicion.=" AND alumno.jornada='$jornada'";}

if($situacion!="A")
{$condicion.=" AND alumno.situacion IN('$situacion','M')";}

if($grupo!="Todos")
{$condicion.=" AND alumno.grupo='$grupo'";}

$inicio_ciclio=true;
$niveles="";
if(is_array($nivel))
{
	foreach($nivel as $nn=>$valornn)
	{
		if($inicio_ciclio)
		{ 
			$niveles.="'$valornn'";
			$inicio_ciclio=false;
		}
		else
		{ $niveles.=", '$valornn'";}
		//echo"--> $niveles<br>";
	}
}
else{ $niveles="'sin nivel'";}

$condicion.="AND alumno.nivel IN($niveles)";
$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
include("../../../funciones/conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resumen General</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:50px;
	z-index:1;
	left: 5%;
	top: 83px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n General V.1</h1>
<div id="link"><br>
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu </a>
  </div>
<div id="apDiv1">
<?php
///////////////////////////////////
						
		$borde=1;
		$letra_1=10;
		$letra_2=12;
		$autor="ACX";
		$titulo="Listado Alumnos";
		$descripcion=$carrera." - Ano $ano_ingreso - Jornada $jornada";
		$descripcion_more="Nivel ".str_replace("'","",$niveles)." - Grupo $grupo";
		$zoom=75;
		$msj_sin_reg="No hay resultados en esta Busqueda";
		
	
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Ver  Resumen General V.1";
			 REGISTRA_EVENTO($evento);
											
		$aux=0;	 
	
	$cons_main_1="SELECT DISTINCT (id_alumno) FROM (contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id) INNER JOIN carrera ON alumno.id_carrera = carrera.id WHERE $condicion ORDER by alumno.id_carrera";
		
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			$num_alumnos_morosos=0;
			$num_alumno_al_dia=0;
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				
					if($verificar_contrato)
						{
							$cons="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.nivel_alumno FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id_alumno='$id_alumno' AND contratos2.condicion<>'inactivo' ORDER by contratos2.id Desc LIMIT 1";
						}
						else
						{$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";}	
						
						$sql=mysql_query($cons)or die(mysql_error());
						$num_reg=mysql_num_rows($sql);
						if(DEBUG){echo"<br><br>--> $cons <br>NUm Contratos: $num_reg<br>";}	
						if($num_reg>0)
						{
							///////////////////////
							while($A=mysql_fetch_assoc($sql))
							{
								$aux++;
								
								$alumno_vigente="";
								$id_alumno=$A["id"];
								$id_carrera_alumno=$A["id_carrera"];
								$year_ingreso=$A["ingreso"];
								$carrera_alumno=$A["carrera"];
								$sede_alumno=$A["sede"];
								/////------------ACTUALIZACION----------------/////
							
								$nivel_alumno=$A["nivel"];
								$grupo_curso=$A["grupo"];
								$jornada=$A["jornada"];
								/////////////////////------------Datos del Contrato------------/////////////
								$id_contrato=$A["id_contrato"];
								$semestre_contrato=$A["semestre"];
								$year_contrato=$A["ano"];
								$vigencia=$A["vigencia"];
								$condicion_contrato=strtoupper($A["condicion"]);
								$nivel_alumno_realiza_contrato=$A["nivel_alumno"];
								/////////////////////////------------------------------/////////////////////
								$situacion=strtoupper($A["situacion"]);
								//---------------------------------------------------------------------------//
								if($verificar_contrato)
								{
									switch($vigencia)
									{
										case"semestral":
											if(($semestre_contrato==$semestre_actual)and($year_contrato==$year_actual))
											{ $alumno_vigente=true;}
											else
											{ $alumno_vigente=false;}
											break;
										case"anual":
											if($year_contrato==$year_actual)
											{ $alumno_vigente=true;}
											else
											{ $alumno_vigente=false;}
											break;	
									}
								}
								else
								{  $alumno_vigente=true; if(DEBUG){ echo"Sin Registro de Contrato <br>";}}
								//----------------------------------------------------------------------------////
								if($no_mostrar_retirados)
								{
									switch($condicion_contrato)
									{
										case"OK":
											$contrato_mostrar=true;
											break;
										case"OLD":
											$contrato_mostrar=true;
											break;
										case"RETIRO":
											$contrato_mostrar=true;
											break;
										default:
											$contrato_mostrar=false;	
									}
								}
								else
								{ $contrato_mostrar=true;} 
								///////////////////////////////////////////////////////////////////////
								//$A_deuda_actual=DEUDA_ACTUAL($id_alumno, $fecha_actual);
								$A_deuda_actual=0;
								////////////////////////////////////////////////////////////////////////
								if(DEBUG)
								{ 
									echo"<br>$aux - Sede: $sede_alumno<br><strong>Carrera: </strong> $carrera_alumno<br><strong>ID_ALUMNO:</strong> $id_alumno <br><strong>Carrera:</strong> $id_carrera_alumno<br><strong>Rut</strong>$rut<br><strong>Situacion</strong> $situacion<br><strong>Nivel</strong> $nivel_alumno <br><strong>ID CONTRATO</strong> $id_contrato <br><strong>Vigencia</strong> $vigencia <br><strong>Semestre: </strong>$semestre_contrato<br><strong>ano Contrato: </strong>$year_contrato<br><strong>Condicion Contrato</strong>[$condicion_contrato] <br>|$contrato_mostrar| contrato cumple vigencia=";
									if($alumno_vigente)
									{ echo"<strong>OK</strong><br>";}
									else{  echo"<strong>NO</strong><br>";}
									
								}
								
								if(($alumno_vigente)and($contrato_mostrar))
								{
									switch($situacion)
									{
										case"V":
											$considerar_alumno=true;
											if(DEBUG){ echo"Alumno vigente sumar<br>";}
											if(!isset($ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["vigentes"]))
											{$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["vigentes"]=0;}
											$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["vigentes"]+=1;
											break;
										case"P":
											$considerar_alumno=true;
											if(DEBUG){ echo"Alumno Pendiente sumar<br>";}
											if(!isset($ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["pendientes"]))
											{$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["pendientes"]=0;}
											$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["pendientes"]+=1;
											break;	
										case"R":
											$considerar_alumno=true;
											if(DEBUG){ echo"Alumno Retirado sumar<br>";}
											if(!isset($ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["retirados"]))
											{$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["retirados"]=0;}
											$ARRAY[$sede_alumno][$jornada][$carrera_alumno][$nivel_alumno]["retirados"]+=1;
											break;		
										default:
											$considerar_alumno=false;
											if(DEBUG){ echo"situacion de alumno no establecida NO sumar<br>";}
									}
										///////////////////////////////////////////////////////////////////////
										
										////////////////////////////////////////////////////////////////////////
								}//fin alumno vigentes
							}
						}
			
			}
		}
		else
		{	
			echo"Sin Registros<br>";
		}
		//fin documento
	mysql_free_result($sql_main_1);
	mysql_close($conexion);
/////////////////////////////////////////////
function DEUDA_ACTUAL($id_alumno, $fecha_actual)
{
	$cons_D="SELECT SUM(deudaXletra) FROM letras WHERE fechavenc<='$fecha_actual' AND idalumn='$id_alumno' AND deudaXletra>'0'";
	$sql_D=mysql_query($cons_D)or die(mysql_error);
	$D=mysql_fetch_row($sql_D);
	$deuda_actual=$D[0];
	if(empty($deuda_actual)){ $deuda_actual=0;}
	if(DEBUG){ echo"$cons_D<br>deuda actual: $deuda_actual<br>";}
	mysql_free_result($sql_D);
	return($deuda_actual);
}

//////////////////////////////////////////////
?>
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="6">Resumen Talca Diurno</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Talca"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
foreach($ARRAY["Talca"]["D"] as $aux_programa => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux_programa.'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="8">Resumen Talca Vespertino</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Talca"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
foreach($ARRAY["Talca"]["V"] as $aux_programa => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux_programa.'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="8">Resumen Linares Diurno</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Linares"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
foreach($ARRAY["Linares"]["D"] as $aux_programa => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux_programa.'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="8">Resumen Linares Vespertino</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Linares"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
foreach($ARRAY["Linares"]["V"] as $aux_programa => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux_programa.'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}


$tiempo_fin_script = microtime(true);
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
</tr>
</tbody>
</table>
<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
</body>
</html>