<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Alumno Solicitud de Pase Esc.</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:442px;
	z-index:1;
	left: 5%;
	top: 157px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:29px;
	z-index:2;
	left: 5%;
	top: 79px;
}
</style>
</head>
<?php
if($_POST)
{
	if(DEBUG){ var_export($_POST);}
	$sede=$_POST["fsede"];
	$carrera=$_POST["carrera"];
	$ano_ingreso=$_POST["ano_ingreso"];
	$jornada=$_POST["jornada"];
	$estado="solo_con_contrato";
	
	
	$mes_actual=date("m");
	if($mes_actual>=8)
	{ $semestre_actual=2;}
	else{ $semestre_actual=1;}
	
	$year_actual=date("Y");
	
	$contrato_semestre_vigencia=$semestre_actual;
	$contrato_year_vigencia=$year_actual;
}
?>
<body>
<h1 id="banner">Administrador - Estadisticas</h1>
<div id="link"><br><a href="alumno_solicita_pase.php" class="button">Volver a Selecci&oacute;n</a></div>
<div id="apDiv2"><strong>Alumnos de Carrera:<?php echo $carrera; ?><br />
    	  A&ntilde;o Ingreso:<?php echo $ano_ingreso;?> - <?php echo $sede;?><br />
    	  Contrato:<?php echo "$estado ($contrato_semestre_vigencia Semestre - $contrato_year_vigencia)";?></strong></div>
<div id="apDiv1">
    <table width="80%" border="1" align="center">
<thead>
	<tr>
    	<td colspan="11" align="center">Listado de Alumnos Solicitan Pase Escolar</td>
    </tr>
	<tr>
    	<th>N&deg;</th>
        <th>ID</th>
        <th>Run</th>
        <th>Nombre</th>
        <th>Apellido P</th>
        <th>Apellido M</th>
        <th>Ingreso</th>
        <th>Carrera</th>
        <th>Nivel</th>
        <th>Jornada</th>
        <th>Contrato</th>
    </tr>
</thead>
<tbody>
<?php
if($_POST)
{
	$contador_parcial=0;
	$contador=0;
	$primera=true;
	include("../../../funciones/conexion.php");
	///////////////////////
	$situacion="V";
	///////////////////////
	//carrera
	if($carrera!="todas")
	{ $condicion_carrera="AND carrera='$carrera'";}
	//ingreso
	if($ano_ingreso!="todos")
	{ $condicion_ingreso="AND ingreso='$ano_ingreso'";}
	
	$cons_main="SELECT * FROM alumno WHERE situacion='$situacion' AND sede='$sede' $condicion_ingreso $condicion_carrera $condicion_nivel $condicion_jornada $condicion_grupo ORDER BY ingreso, carrera, nivel, jornada, grupo, apellido_P, apellido_M";
	if(DEBUG){ echo"<br><strong>MAIN: $cons_main</strong><br>";}
	$sql_main=mysql_query($cons_main)or die(mysql_error());
	$num_reg=mysql_num_rows($sql_main);
	if($num_reg>0)
	{
		while($A=mysql_fetch_assoc($sql_main))
		{
			$A_id_alumno=$A["id"];
			$A_rut=$A["rut"];
			$A_nivel=$A["nivel"];
			$A_carrera=$A["carrera"];
			$A_ingreso=$A["ingreso"];
			$A_jornada=$A["jornada"];
			$A_grupo=$A["grupo"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			////////////////////////////////////////////////
			$A_ciudad=ucwords(strtolower($A["ciudad"]));
			$A_liceo_dependencia=$A["liceo_dependencia"];
			$A_liceo_formacion=$A["liceo_formacion"];
			if(empty($A_liceo_dependencia)){$A_liceo_dependencia="sin info";}
			if(empty($A_liceo_formacion)){ $A_liceo_formacion="sin info";}
			///////////////////////////////////////////////////////
			
			$estado_contrato=VERIFICA_CONTRATO($A_id_alumno, $contrato_year_vigencia, $contrato_semestre_vigencia);
		
			if($estado_contrato)
			{ $estado_contrato_label="OK";}
			else
			{ $estado_contrato_label="NO";}
			///////
			//reviso registro de pase
			$cons_PA="SELECT pase_escolar FROM alumno_antecedentes WHERE id_alumno='$A_id_alumno' ORDER by id desc LIMIT 1";
			$sql_PA=mysql_query($cons_PA)or die(mysql_error());
			$num_registros_pase=mysql_num_rows($sql_PA);
			if($num_registros_pase>0)
			{
				$Datos=mysql_fetch_assoc($sql_PA);
				$A_solicita_pase_escolar=$Datos["pase_escolar"];
			}
			else
			{$A_solicita_pase_escolar=0;}
			mysql_free_result($sql_PA);
				
			if(DEBUG){ echo"---->$cons_PA<br>Pase Escolar: $A_solicita_pase_escolar<br>";}
			
			//determino a quien muestro
			if(($estado_contrato)and($A_solicita_pase_escolar==1))
			{ $mostrar=true;}
			else{ $mostrar=false;}
			
			if($mostrar)
			{
				
				if(($contador>0)and($carrera_old!=$A_carrera)or($nivel_old!=$A_nivel)or($ingreso_old!=$A_ingreso))
				{
					if(!$primera)
					{ 

						$salto=true;
					}
					else
					{
						if(DEBUG){ echo"primera no saltar<br>";}
						 $salto=false;
					}
				}
				else
				{
					$salto=false;
				}
				$carrera_old=$A_carrera;
				$nivel_old=$A_nivel;
				$ingreso_old=$A_ingreso;
				
				if($primera)
				{ $primera=false;}
				if($salto)
				{
					echo'<tr>
						<td colspan="3"><strong>TOTAL</strong><td>
						<td colspan="8" align="right"><strong>'.$contador_parcial.'</strong></td>
						</tr>
						<tr>
							<td colspan="11">&nbsp;</td>
						</tr>';
						$contador_parcial=0;
				}
				$contador++;
				$contador_parcial++;
				echo'<tr>
						<td>'.$contador.'</td>
						<td>'.$A_id_alumno.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_apellido_M.'</td>
						<td>'.$A_ingreso.'</td>
						<td>'.$A_carrera.'</td>
						<td>'.$A_nivel.'</td>
						<td>'.$A_jornada.'</td>
						<td>'.$estado_contrato_label.'</td>
						</tr>';
						//-----------------------------------------------------------//		
						
						
						//-----------------------------------------------------------//		
			}//fin si mostrar
			
		}
		
					echo'<tr>
						<td colspan="3"><strong>TOTAL</strong><td>
						<td colspan="8" align="right"><strong>'.$contador_parcial.' de '.$contador.'</strong></td>
						</tr>
						<tr>
							<td colspan="11">&nbsp;</td>
						</tr>';
	}
	else
	{ echo"Sin Resultados X esta consulta<br>";}
	
	mysql_close($conexion);
}
else
{ echo"Sin Datos<br>";}
/////////////////////////---------------------------------------------///////////////////////////
function VERIFICA_CONTRATO($id_alumno, $year_vigencia, $semestre_vigencia)
{
	$cons_C="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion IN('ok', 'old') ORDER by id";
	if(DEBUG){ echo"<tt>|====>$cons_C</tt><br>";}
	$sql_C=mysql_query($cons_C)or die("verifica contrato ".mysql_error());
	$num_contratos=mysql_num_rows($sql_C);
	if($num_contratos>0)
	{
		while(($C=mysql_fetch_assoc($sql_C))and(!$contrato_ok))
		{
			$C_id=$C["id"];
			$C_semestre=$C["semestre"];
			$C_year=$C["ano"];
			$C_condicion=$C["condicion"];
			$C_vigencia=$C["vigencia"];
			
			if(DEBUG){ echo"---> |$C_id| $C_semestre |$C_year| $C_condicion |$C_vigencia|";}
			
			switch($C_vigencia)
			{
				case"semestral":
					if(($C_year==$year_vigencia)and($C_semestre==$semestre_vigencia))
					{ 
						$contrato_ok=true;
						if(DEBUG){echo"S :-)<br>";}
					}
					else
					{
						 $contrato_ok=false;
						 if(DEBUG){echo"S :-(<br>";}
					}
					break;
				case"anual":	
					if($C_year==$year_vigencia)
					{ 
						$contrato_ok=true;
						if(DEBUG){echo"A :-)<br>";}
					}
					else
					{ 
						$contrato_ok=false;
						if(DEBUG){echo"A :-(<br>";}
					}
					break;
				default:
					$contrato_ok=false;	
					if(DEBUG){echo"D :-(<br>";}
			}
		}
	}
	else
	{
		$contrato_ok=false;
	}
	mysql_free_result($sql_C);
	return($contrato_ok);
}
?>
</tbody>
</table>
</div>
</body>
</html>