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
	define("DEBUG",false);
//-----------------------------------------//	
$year_actual=date("Y");
$fecha_corte=date("Y-m-d");
$semestre_actual=2;
$verificar_contrato=true;
$mostrar_solo_contratos_OK=true;
if(isset($_GET["sede"]))
{
	$sede=$_GET["sede"];
}
else
{$sede="Talca";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Morosidad Alumnos Matriculados</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:137px;
	z-index:1;
	left: 5%;
	top: 178px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:40px;
	z-index:2;
	left: 5%;
	top: 100px;
	text-align: center;
	font-size: 18px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:37px;
	z-index:2;
	left: 30%;
	top: 103px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe morosos</h1>
<div id="link"><br />
<a href="informe_morosos_total_info_beca.php?sede=Talca" class="button">Talca</a><br /><br />
<a href="informe_morosos_total_info_beca.php?sede=Linares" class="button">Linares</a></div>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
  <tr>
  	<th colspan="15">Alumno MOROSOS <?php echo"- $sede<br>Semestre $semestre_actual semestre-$year_actual<br>FECHA CORTE: $fecha_corte";?></th>
  </tr>
  </thead>
  <tbody>
<?php
include("../../../funciones/conexion.php");

$sede=mysql_real_escape_string($sede);
///////////////////////////////////

									echo'<tr>
									<td>N.</td>
									<td>Matricula Vigente</td>	
									<td>Carrera</td>
									<td>Nivel</td>
									<td>jornada</td>
									<td>Rut</td>
									<td>Nombre</td>
									<td>Apellido P</td>
									<td>Apellido M</td>
									<td>Ingreso</td>
									<td>Situacion Financiera</td>
									<td>BNM</td>
									<td>BEA</td>
									<td>N. Cuotas (M)</td>
									<td>Total adeudado</td>
									</tr>';
									
						
						$cons_M1="SELECT * FROM alumno WHERE sede='$sede' AND situacion_financiera='M' ORDER by carrera, apellido_P, apellido_M";	 
						$aux=0;
						$SUMA_TOTAL_DEUDA=0;
						$TOTAL_ALUMNOS_MATRICULADOS_ACTUALMENTE=0;
						$sql_M1=mysql_query($cons_M1)or die(mysql_error());
						$num_alumno=mysql_num_rows($sql_M1);
						if($num_alumno>0)
						{
							while($A=mysql_fetch_assoc($sql_M1))
							{
								$aux++;
								$id_alumno=$A["id"];
								$A_rut=$A["rut"];
								$A_nombre=$A["nombre"];
								$A_apellido_P=$A["apellido_P"];
								$A_apellido_M=$A["apellido_M"];
								$A_carrera=$A["carrera"];
								$A_jornada=$A["jornada"];
								$A_nivel=$A["nivel"];
								$A_situacion=$A["situacion"];
								$A_situacion_financiera=$A["situacion_financiera"];
								$A_ingreso=$A["ingreso"];
								
								if(DEBUG){ echo"$aux -> $A_rut<br>";}
								
								$matriculado_actualmente=ACTUALMENTE_MATRICULADO($id_alumno, $semestre_actual, $year_actual);
								if($matriculado_actualmente)
								{ $esta_matriculado="si"; $TOTAL_ALUMNOS_MATRICULADOS_ACTUALMENTE++;}
								else
								{ $esta_matriculado="no";}
								
								list($num_cuotas_morosas, $deuda_total_a_la_fecha)=INFO_DEUDA($id_alumno, $fecha_corte);
								list($aporte_BNM, $aporte_BET)=INFO_BECAS($id_alumno);
								
								$SUMA_TOTAL_DEUDA+=$deuda_total_a_la_fecha;
								
								echo'<tr>
										<td>'.$aux.'</td>
										<td>'.$esta_matriculado.'</td>
										<td>'.$A_carrera.'</td>
										<td>'.$A_nivel.'</td>
										<td>'.$A_jornada.'</td>
										<td>'.$A_rut.'</td>
										<td>'.$A_nombre.'</td>
										<td>'.$A_apellido_P.'</td>
										<td>'.$A_apellido_M.'</td>
										<td>'.$A_ingreso.'</td>
										<td>'.$A_situacion_financiera.'</td>
										<td>'.$aporte_BNM.'</td>
										<td>'.$aporte_BET.'</td>
										<td>'.$num_cuotas_morosas.'</td>
										<td>'.$deuda_total_a_la_fecha.'</td>
									 </tr>';
								
							}
						}
						else
						{
							if(DEBUG){ echo"No hay Alumno...<br>";}
						}
								
	

		//fin documento
	mysql_free_result($sql_M1);
	mysql_close($conexion);
//////////////////////////////////////////////
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

function INFO_DEUDA($id_alumno, $fecha_corte)
{
	$cons="SELECT * FROM letras WHERE idalumn='$id_alumno' AND pagada<>'S' AND fechavenc<='$fecha_corte' ORDER by fechavenc";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_cuotas=mysql_num_rows($sql);
	
	if(DEBUG){ echo"CUOTAS<br>$cons<br>N Cuotas: $num_cuotas<br>";}
	if($num_cuotas>0)
	{
		$TOTAL_DEUDA=0;
		while($C=mysql_fetch_assoc($sql))
		{
			$C_id=$C["id"];
			$C_deudaXletra=$C["deudaXletra"];
			$C_pagada=$C["pagada"];
			
			$TOTAL_DEUDA+=$C_deudaXletra;
		}
	}
	else
	{$TOTAL_DEUDA=0;}
	
	$respuesta=array($num_cuotas, $TOTAL_DEUDA);
	if(DEBUG){ echo"<strong>DEUDA TOTAL: $TOTAL_DEUDA</strong><br><br>";}
	mysql_free_result($sql);
	return($respuesta);
}

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
?>   
<tr>
	<td colspan="2"><strong>Total</strong></td>
    <td colspan="12">&nbsp;</td>
    <td><strong><?php echo $SUMA_TOTAL_DEUDA;?></strong></td>
</tr>
<tr>
	<td colspan="2"><strong>N. matriculas V</strong></td>
    <td><strong><?php echo $TOTAL_ALUMNOS_MATRICULADOS_ACTUALMENTE;?></strong></td>
    <td colspan="12">&nbsp;</td>
</tr>
</tbody> 
  </table>
</div>
<div id="apDiv3">Busca Alumnos X sede cuya situaci&oacute;n Financiera sea &quot;moroso&quot; y muestra la informacion con respecto a becas reflejado en el ultimo contrato ademas la cantidad de cuotas adeudadas cuya fecha de vencimiento sea menor o igual que la fecha de corte y el valor que estas representan.</div>
</body>
</html>