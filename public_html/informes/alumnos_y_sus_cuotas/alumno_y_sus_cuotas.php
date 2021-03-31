<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Administrador - Alumnos y sus cuotas</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 16px;
	font-weight: bold;
}
.Estilo2 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 84px;
}
-->
</style>
</head>
<?php
$sede=$_POST["fsede"];
$array_carrera=$_POST["carrera"];
$array_carrera=explode("_",$array_carrera);
$id_carrera=$arrat_carrera[0];
$carrera=$array_carrera[1];
$ver_cuotas=$_POST["ver_cuotas"];
?>
<body>
<h1 id="banner">Administrador - Informe Alumnos con Cuotas</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<table width="100%" border="0" align="center">
<thead>
  <tr>
	<th colspan="10" >Alumnos de <?php echo "$carrera - $sede";?></th>
</tr>
  <tr>
    <td><span class="Estilo2">N</span></td>
     <td><span class="Estilo2">ID Alumno</span></td>
     <td><span class="Estilo2">RUT</span></td>
     <td><span class="Estilo2">Nombre</span></td>
     <td><span class="Estilo2">Apellido</span></td>
     <td><span class="Estilo2">Nivel</span></td>
     <td><span class="Estilo2">ingreso</span></td>
     <td><span class="Estilo2">Matricula Vigente</span></td>
     <td><span class="Estilo2">BNM</span></td>
     <td><span class="Estilo2">BET</span></td>
  </tr>	
  </thead>
<tbody>
<?php
//////////////////////////
$verificar_contrato=true;
////////////////////////////---> Datos actuales de Semestre y año
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual<8)/////porque los contratos semestrales vencen en agosto
{ $semestre_actual=1;}
else
{ $semestre_actual=2;}

/////////////////////////////


if($sede=="")
{$sede="Talca";}
$condicion=" alumno.sede='$sede' AND alumno.carrera='$carrera' AND contratos2.condicion='ok'";
$condicion=" alumno.sede='$sede' AND alumno.carrera='$carrera'";

///////////////////////////
include("../../../funciones/conexion.php");
	
	$cons="SELECT DISTINCT(alumno.id) FROM alumno INNER JOIN letras ON alumno.id=letras.idalumn WHERE $condicion ORDER by apellido_P, apellido_M";

$sql=mysql_query($cons)or die(mysql_error());
$num_reg=mysql_num_rows($sql);
if(DEBUG)
{echo"--> $cons <br>Num. ALumnos: $num_reg<br>VER CUOTAS: $ver_cuotas<br><br>";}
///////////////////////////////////
if($num_reg>0)
{
	$aux=1;
	 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Ve Informe(alumnos y sus cuotas)->".$carrera."-".$sede;
		 REGISTRA_EVENTO($evento);
	///////////////////////
	while($A=mysql_fetch_assoc($sql))
	{
		
		$id_alumno=$A["id"];
		
		$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sql_A=mysql_query($cons_A)or die(mysql_error());
			$DA=mysql_fetch_assoc($sql_A);
		mysql_free_result($sql_A);	
		
		
		$rut=$DA["rut"];
		$nombre=$DA["nombre"];
		$apellido=$DA["apellido"];
		$year_ingreso=$DA["ingreso"];
		/////------------ACTUALIZACION----------------/////
		$apellido_P=$DA["apellido_P"];
		$apellido_M=$DA["apellido_M"];
		$apellido_aux=$apellido_P." ".$apellido_M;
		$nivel_alumno=$DA["nivel"];
		$grupo_curso=$DA["grupo"];
		$jornada=$DA["jornada"];
		/////////////////////------------Datos Becas------------/////////////
		$matriculado_actualmente=ACTUALMENTE_MATRICULADO($id_alumno, $semestre_actual, $year_actual);
		if($matriculado_actualmente)
		{ $esta_matriculado="si"; $TOTAL_ALUMNOS_MATRICULADOS_ACTUALMENTE++;}
		else
		{ $esta_matriculado="no";}
		list($aporte_BNM, $aporte_BET)=INFO_BECAS($id_alumno);
		/////////////////////////------------------------------/////////////////////
		$situacion=$DA["situacion"];
		
		
		
		//**-**-***-***-***-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-//	
	/////////////////////////////////////////////////////////////////////////////////
		
				$cons_L="SELECT * FROM letras WHERE idalumn='$id_alumno' AND sede='$sede' order by fechavenc";
				$sql_L=mysql_query($cons_L)or die("busca cuotas".mysql_error());
				$num_cuotas=mysql_num_rows($sql_L);
				$html_cuota='<tr><td colspan="10" align="right">
				<table id="cuotas" border="0" width="90%">
				<thead>
				<tr align="center">
				<th><em>N</em></th>
				<th><em>Tipo</em></th>
				<th><em>Semestre</em></th>
				<th><em>Año</em></th>
				<th><em>Valor</em></th>
				<th><em>Deuda</em></th>
				<th><em>Vencimiento</em></th>
				<th><em>Ultimo Pago</em></th>
				</tr>
				</thead>
				<tbody>';
					$num_cuoX=1;
					$suma_valor=0;
					$suma_deuda=0;
					$mostrar_alumno_actual=false;
				if($num_cuotas>0)
				{
					
					while($DATOS_L=mysql_fetch_assoc($sql_L))
					{
						$id_cuota=$DATOS_L["id"];
						$fecha_vence=$DATOS_L["fechavenc"];
						$valor=$DATOS_L["valor"];
						$deudaXcuota=$DATOS_L["deudaXletra"];
						$ano=$DATOS_L["ano"];
						$semestre=$DATOS_L["semestre"];
						$pagada=$DATOS_L["pagada"];
						$tipo=$DATOS_L["tipo"];
						$fecha_ultimo_pago=$DATOS_L["fecha_ultimo_pago"];
						
						if($fecha_ultimo_pago=="0000-00-00")
						{ $ultimo_pago_label="---";}
						else
						{ $ultimo_pago_label=$fecha_ultimo_pago;}
						
						switch($ver_cuotas)
						{
							case"todas":
								$mostrar_cuota_actual=true;
								$mostrar_alumno_actual=true;
								break;
							case"pendientes":
								if(($pagada=="A")or($pagada=="N"))
								{ $mostrar_cuota_actual=true; $mostrar_alumno_actual=true;}
								else{ $mostrar_cuota_actual=false;}
								break;
							case"pagadas":
								if($pagada=="S")
								{ $mostrar_cuota_actual=true; $mostrar_alumno_actual=true;}
								else{ $mostrar_cuota_actual=false;}
								break;
						}
						
							if($mostrar_cuota_actual)
							{
								$html_cuota.='<tr align="center">
								<td><em>'.$num_cuoX.'</em></td>
								<td><em>'.$tipo.'</em></td>
								<td><em>'.$semestre.'</em></td>
								<td><em>'.$ano.'</em></td>
								<td><em>$'.number_format($valor,0,",",".").'</em></td>
								<td><em>$'.number_format($deudaXcuota,0,",",".").'</em></td>
								<td><em>'.$fecha_vence.'</em></td>
								<td><em>'.$ultimo_pago_label.'</em></td>
								</tr>';
								$num_cuoX++;
								$suma_valor+=$valor;
								$suma_deuda+=$deudaXcuota;
							}
					}
				}
				else
				{$html_cuota.='<tr><td colspan="8"><strong>Sin Cuotas</strong></td></tr>';}
				//resumen x cuota
				$html_cuota.='<tr>
					<td colspan="8" align="right"><strong>Total Cuota: $'.number_format($suma_valor,0,",",".").'  Total Deuda: $'.number_format($suma_deuda,0,",",".").' </strong></td>
					</tr>
					</tbody>
					</table></td></tr>';
					
				if($mostrar_alumno_actual)
				{
					 echo'<tr align="center">
					 <td>'.$aux.'</td>
					 <td>'.$id_alumno.'</td>
					 <td>'.$rut.'</td>
					 <td>'.ucwords(strtolower($nombre)).'</td>
					 <td>'.ucwords(strtolower($apellido_label)).'</td>
					 <td>'.$nivel_alumno.'</td>
					 <td>'.$year_ingreso.'</td>
					 <td>'.$esta_matriculado.'</td>
					 <td>'.$aporte_BNM.'</td>
					 <td>'.$aporte_BET.'</td>
					 </tr>';
					$aux++;
					
					echo $html_cuota;
				}
				mysql_free_result($sql_L);
				$ACUMULADOR_VALOR+=$suma_valor;
				$ACUMULADOR_DEUDA+=$suma_deuda;
			}
		//**-**-***-***-***-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-//
}
else
{
		echo'<tr><td colspan="10">Sin Alumnos encontrados...</td></tr>';	
}
//fin documento
	mysql_close($conexion);
?>
<tr>
<td><strong>TOTAL CUOTAS</strong></td>
<td colspan="8">&nbsp;</td>
<td><strong><?php echo "$".number_format($ACUMULADOR_VALOR,0,",",".");?></strong></td>
</tr>
<tr>
<td><strong>TOTAL DEUDA</strong></td>
<td colspan="8">&nbsp;</td>
<td><strong><?php echo "$".number_format($ACUMULADOR_DEUDA,0,",",".");?></strong></td>
</tr>
<tr>
<td><strong>TOTAL PAGADO</strong></td>
<td colspan="8">&nbsp;</td>
<td><strong><?php echo "$".number_format(($ACUMULADOR_VALOR-$ACUMULADOR_DEUDA),0,",",".");?></strong></td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
<?php
function INFO_BECAS($id_alumno)
{
	$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion<>'inactivo' ORDER by id desc LIMIT 1";
	$sql=mysql_query($cons)or die(mysql_error());
		$C=mysql_fetch_assoc($sql);
			$aporte_BNM=$C["aporte_beca_nuevo_milenio"];
			$aporte_BET=$C["aporte_beca_excelencia"];
			if(empty($aporte_BNM)){ $aporte_BNM=0;}
			if(empty($aporte_BET)){ $aporte_BET=0;}
	mysql_free_result($sql);	
	
	if(DEBUG){ echo"BECAS<br> BNM: $aporte_BNM<br> BET: $aporte_BET<br><br>";}
	
	$respuesta=array($aporte_BNM, $aporte_BET);
	return($respuesta);
}
///
function ACTUALMENTE_MATRICULADO($id_alumno, $semeste_actual, $year_actual)
{
	$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$year_actual' ORDER by id";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_contratos=mysql_num_rows($sql);
	if(DEBUG){ echo"CONTRATOS ($semeste_actual-$year_actual)<br>$cons<br>Num. $num_contratos<br>";}
	if($num_contratos>0)
	{
		while($C=mysql_fetch_assoc($sql))
		{
			$C_id=$C["id"];
			$C_vigencia=strtolower($C["vigencia"]);
			$C_condicion=strtolower($C["condicion"]);
			$C_semestre=$C["semestre"];
			$C_year=$C["ano"];
			if(DEBUG){ echo"--->$C_id | $C_vigencia | $C_condicion |$C_semestre| $C_year| ";}
			
			if($C_condicion=="ok")
			{
				switch($C_vigencia)
				{
					case"semestral":
						if(($C_semestre==$semeste_actual)and($C_year==$year_actual))
						{ $alumno_vigente=true;}
						else
						{ $alumno_vigente=false;}
						break;
					case"anual":
						if($C_year==$year_actual)
						{ $alumno_vigente=true;}
						else
						{ $alumno_vigente=false;}
						break;	
				}
			}
			else
			{ $alumno_vigente=false;}
			if(DEBUG){if($alumno_vigente){ echo"OK<br>";}else{ echo"NO<br>";}}
		}
	}
	else
	{ $alumno_vigente=false;}
	
	if(DEBUG){ if($alumno_vigente){ echo"ALUMNO VIGENTE: <strong>OK</strong><br><br>";}else{ echo"ALUMNO VIGENTE: <strong>NO</strong><br><br>";} }
	mysql_free_result($sql);
	
	return($alumno_vigente);
}
?>