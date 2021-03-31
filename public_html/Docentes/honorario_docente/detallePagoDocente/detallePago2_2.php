<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("ver_asignaciones_general");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	$continuar=true;
	$sede=$_POST["fsede"];
	$year=$_POST["year"];
	$semestre=$_POST["semestre"];
	$ordenar=$_POST["ordenar"];
}
else
{ $continuar=false;}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Revision Asignaciones General</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:101px;
	z-index:1;
	left: 5%;
	top: 160px;
}
#apDiv2 {
	position:absolute;
	width:35%;
	height:31px;
	z-index:2;
	left: 35%;
	top: 198px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Revision Asignaciones General</h1>
<div id="link"><br>
<a href="ver_asignacion_general_1.php" class="button">Volver a seleccion</a><br /><br />
<a href="ver_asignacion_general_2_xls.php?sede=<?php echo $sede;?>&semestre=<?php echo $semestre;?>&year=<?php echo $year;?>&ordenar=<?php echo $ordenar;?>" class="button" target="_blank">.xls</a><br /><br />
<br />
<a target="_blank" href="ver_asignacion_general_Xdocente_xls.php?sede=<?php echo $sede;?>&semestre=<?php echo $semestre;?>&year=<?php echo $year;?>&ordenar=<?php echo $ordenar;?>" class="button">Listado Docentes Xls</a></div>
<div id="apDiv1">

    <table width="100%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="12">Asignaciones <?php echo "$sede Periodo [$semestre - $year]";?></th>
        </tr>
      </thead>
      <tr>
      	<td>N.</td>
      	<td>Rut</td>
        <td>Funcionario</td>
        <td>Carrera</td>
        <td>Asignatura</td>
        <td>Nivel</td>
        <td>Jor-Grup</td>
        <td>$. Hrs</td>
        <td>N. Hrs</td>
        <td>Total</td>
        <td>N. Cuotas</td>
        <td>Condicion</td>
      </tr>
      <tbody>
      <?php
      if($continuar)
	  {
		  require("../../../../../funciones/conexion_v2.php");
		  require("../../../../../funciones/funciones_sistema.php");
		  
		  switch($ordenar)
		  {
			  	case"funcionario":
					$ordenar_por="personal.apellido_P, personal.apellido_M";
					$mostrar_valor_curso=false;
			  		break;
				case"curso":
					$ordenar_por="toma_ramo_docente.id_carrera, mallas.nivel, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
					$mostrar_valor_curso=true;
					break;
				default:
					$ordenar_por="personal.apellido_P, personal.apellido_M";	
		  }
		  
		  $sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		  $semeste=mysqli_real_escape_string($conexion_mysqli, $semestre);
		  $year=mysqli_real_escape_string($conexion_mysqli, $year);
		  
	  		$cons="SELECT toma_ramo_docente.*, mallas.nivel AS nivel FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id  LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera=mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by $ordenar_por";
			if(DEBUG){echo"->$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			if($num_registros>0)
			{
				$contador=0;
				$id_funcionario_old=0;
				$id_carrera_old=0;
				$jornada_old="";
				$grupo_old="";
				$nivel_old="";
				
				$primera_vuelta=true;
				$SUMA_TOTAL_FUNCIONARIO=0;
				$SUMA_HORAS_FUNCIONARIO=0;
				$SUMA_TOTALES=0;
				$color='#01AE1A';
				$color_2='#AAAE11';
				while($AS=$sqli->fetch_assoc())
				{
					$contador++;
					$AS_nivel=$AS["nivel"];
					if(empty($AS_nivel)){ $AS_nivel=0;}
					$AS_id_funcionario=$AS["id_funcionario"];
					$AS_id_carrera=$AS["id_carrera"];
					$AS_jornada=$AS["jornada"];
					$AS_grupo=$AS["grupo"];
					$AS_cod_asignatura=$AS["cod_asignatura"];
					$AS_numero_horas=$AS["numero_horas"];
					$AS_valor_hora=$AS["valor_hora"];
					$AS_total=$AS["total"];
					$AS_numero_cuotas=$AS["numero_cuotas"];
					$AS_condicion=$AS["condicion"];
					
					$SUMA_TOTALES+=$AS_total;
					//---------------------------------------------------------//
					 switch($ordenar)
					  {
							case"funcionario":
								if(!$primera_vuelta)
								{
									if($id_funcionario_old!=$AS_id_funcionario)
									{
										echo'<tr bgcolor="'.$color.'">
												<td><strong>TOTAL</strong></td>
												<td colspan="6">&nbsp;</td>
												<td>&nbsp;</td>
												<td>'.$SUMA_HORAS_FUNCIONARIO.'</td>
												<td align="right"><strong>'.number_format($SUMA_TOTAL_FUNCIONARIO,0,",",".").'</strong></td>
												<td colspan="3">&nbsp;</td>
												</tr>
												<tr>
													<td colspan="12">&nbsp;</td>
												</tr>';
										$SUMA_TOTAL_FUNCIONARIO=$AS_total;	
										$SUMA_HORAS_FUNCIONARIO=$AS_numero_horas;
									}
									else
									{
										$SUMA_TOTAL_FUNCIONARIO+=$AS_total;
										$SUMA_HORAS_FUNCIONARIO+=$AS_numero_horas;
									}
								}
								else
								{ 
									$SUMA_TOTAL_FUNCIONARIO=$AS_total;
									$SUMA_HORAS_FUNCIONARIO=$AS_numero_horas;
								}
								break;
							case"curso":
								
								
								if(!$primera_vuelta)
							{
								if(($id_carrera_old==$AS_id_carrera)and($AS_jornada==$jornada_old)and($AS_grupo==$grupo_old)and($nivel_old==$AS_nivel))
								{
									$SUMA_TOTAL_FUNCIONARIO+=$AS_total;
									$SUMA_HORAS_FUNCIONARIO+=$AS_numero_horas;
								}
								else
								{
									echo'<tr bgcolor="'.$color.'">
												<td><strong>TOTAL</strong></td>
												<td colspan="6">&nbsp;</td>
												<td>&nbsp;</td>
												<td>'.$SUMA_HORAS_FUNCIONARIO.'</td>
												<td align="right"><strong>'.number_format($SUMA_TOTAL_FUNCIONARIO,0,",",".").'</strong></td>
												<td colspan="3">&nbsp;</td>
												</tr>
									  <tr bgcolor="'.$color_2.'">
												<td colspan="2"><strong>ARANCEL</strong></td>
												<td colspan="5">&nbsp;</td>
												<td>&nbsp;</td>
												<td>0</td>
												<td align="right"><strong>'.number_format($aux_valor_arancel,0,",",".").'</strong></td>
												<td colspan="3">&nbsp;</td>
												</tr>	
									 <tr bgcolor="'.$color_2.'">
												<td colspan="2"><strong>Ingreso Becas</strong></td>
												<td colspan="5">&nbsp;</td>
												<td>&nbsp;</td>
												<td>0</td>
												<td align="right"><strong>'.number_format(($aux_aporte_BNM+$aux_aporte_BET),0,",",".").'</strong></td>
												<td colspan="3">&nbsp;</td>
												</tr>				
									 <tr bgcolor="'.$color_2.'">
												<td colspan="2"><strong>Linea de Credito</strong></td>
												<td colspan="5">&nbsp;</td>
												<td>&nbsp;</td>
												<td>0</td>
												<td align="right"><strong>'.number_format($aux_linea_credito,0,",",".").'</strong></td>
												<td colspan="3">&nbsp;</td>
												</tr>				
												<tr>
													<td colspan="12">&nbsp;</td>
												</tr>';
										$SUMA_TOTAL_FUNCIONARIO=$AS_total;	
										$SUMA_HORAS_FUNCIONARIO=$AS_numero_horas;
										if($mostrar_valor_curso)
										{
											if($AS_nivel>0)
											{
												$cons_Ax="SELECT SUM(arancel), SUM(aporte_beca_nuevo_milenio), SUM(aporte_beca_excelencia), SUM(linea_credito_paga) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE contratos2.id_carrera='$AS_id_carrera' AND contratos2.sede='$sede' AND nivel_alumno='$AS_nivel' AND alumno.grupo='$AS_grupo' AND alumno.jornada='$AS_jornada' AND alumno.situacion='V' AND contratos2.condicion='OK' AND contratos2.ano='$year'";
												$sqli_AX=$conexion_mysqli->query($cons_Ax)or die($conexion_mysqli->error);
												$DAx=$sqli_AX->fetch_row();
												$aux_valor_arancel=$DAx[0];
												$aux_aporte_BNM=$DAx[1];
												$aux_aporte_BET=$DAx[2];
												$aux_linea_credito=$DAx[3];
												$sqli_AX->free();
											}
											else
											{
												$aux_valor_arancel=0;
												$aux_aporte_BNM=0;
												$aux_aporte_BET=0;
												$aux_linea_credito=0;
											}
											if(DEBUG){ echo"BUSCA VALORES<br>--->$cons_Ax<br> aux_valor_arancel: $aux_valor_arancel aux_aporte_BNM: $aux_aporte_BNM aux_aporte_BET:$aux_aporte_BET<br>";}
											
										}
								}
							}
							else
							{
								$SUMA_TOTAL_FUNCIONARIO=$AS_total;
								$SUMA_HORAS_FUNCIONARIO=$AS_numero_horas;
							}
								break;
					  }
					//------------------------------------------------------/
					//Datos funcionarios
					$cons_DF="SELECT * FROM personal WHERE id='$AS_id_funcionario' LIMIT 1";
					$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
						$DF=$sqli_DF->fetch_assoc();
						$F_rut=$DF["rut"];
						$F_nombre=$DF["nombre"];
						$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
					$sqli_DF->free();
					//--------------------------------------------------------------------//	
					//carrera
					$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
					//----------------------------//
					//asignatura
						list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
					//----------------------------------------------------------------//
					
					echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$F_rut.'</td>
							<td>'.$F_apellido.' '.$F_nombre.'</td>
							<td>'.$AS_id_carrera.'_'.$nombre_carrera.'</td>
							<td>'.$nombre_asignatura.'</td>
							<td>'.$AS_nivel.'</td>
							<td align="center">'.$AS_jornada.'-'.$AS_grupo.'</td>
							<td align="right">'.number_format($AS_valor_hora,0,",",".").'</td>
							<td>'.$AS_numero_horas.'</td>
							<td align="right">'.number_format($AS_total,0,",",".").'</td>
							<td align="center">'.$AS_numero_cuotas.'</td>
							<td>'.$AS_condicion.'</td>
							</tr>';	
					//-----------------------------------------------//		
					$primera_vuelta=false;
					
					$id_funcionario_old=$AS_id_funcionario;
					$id_carrera_old=$AS_id_carrera;
					$nivel_old=$AS_nivel;
					$jornada_old=$AS_jornada;
					$grupo_old=$AS_grupo;
							
					
				}
				echo'<tr bgcolor="'.$color.'">
					<td><strong>TOTAL</strong></td>
					<td colspan="6">&nbsp;</td>
					<td>&nbsp;</td>
					<td>'.$SUMA_HORAS_FUNCIONARIO.'</td>
					<td align="right"><strong>'.number_format($SUMA_TOTAL_FUNCIONARIO,0,",",".").'</strong></td>
					<td colspan="3">&nbsp;</td></tr>';
			
			if($mostrar_valor_curso)
			{
			echo'<tr bgcolor="'.$color_2.'">
						<td colspan="2"><strong>ARANCEL</strong></td>
						<td colspan="5">&nbsp;</td>
						<td>&nbsp;</td>
						<td>0</td>
						<td align="right"><strong>'.number_format($aux_valor_arancel,0,",",".").'</strong></td>
						<td colspan="3">&nbsp;</td>
						</tr><tr bgcolor="'.$color_2.'">
						<td colspan="2"><strong>Ingreso Beca</strong></td>
						<td colspan="5">&nbsp;</td>
						<td>&nbsp;</td>
						<td>0</td>
						<td align="right"><strong>'.number_format(($aux_aporte_BNM+$aux_aporte_BET),0,",",".").'</strong></td>
						<td colspan="3">&nbsp;</td>
						</tr>
						<tr bgcolor="'.$color_2.'">
					<td colspan="2"><strong>Linea de Credito</strong></td>
					<td colspan="5">&nbsp;</td>
					<td>&nbsp;</td>
					<td>0</td>
					<td align="right"><strong>'.number_format($aux_linea_credito,0,",",".").'</strong></td>
					<td colspan="3">&nbsp;</td>
					</tr>';
			}
				echo'<td colspan="12">&nbsp;</td>
					</tr>
					<tr bgcolor="#FF9900">
						<td colspan="2"><strong>TOTAL FINAL</strong></td>
						<td colspan="6">&nbsp;</td>
						<td align="right" colspan="2"><strong>'.number_format($SUMA_TOTALES,0,",",".").'</strong></td>
						<td colspan="2">&nbsp;</td>
					</tr>';
			}
			else
			{ echo'<tr><td colspan="12">Sin Asignaciones Creadas</td></tr>';}
			$sqli->free();
			
		  mysql_close($conexion);
		  $conexion_mysqli->close();
	  }
	  else
	  { echo'<tr><td colspan="11">Sin datos</td></tr>';}
	  ?>
      </tbody>
    </table><br />
<br />
</div>
</body>
</html>