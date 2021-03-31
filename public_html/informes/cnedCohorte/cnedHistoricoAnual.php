<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
$tiempo_inicio_script = microtime(true);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_CNEDhistoricoAnual_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	

if(!$_GET){	
	$js='y=prompt(" Ingrese el YEAR a consultar " ,"'.date("Y").'");';
	echo'<script languaje="javascript">
		'.$js.'
		window.location="cnedHistoricoAnual.php?yearConsulta="+y
		</script>';
}
else{

if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=CNEDHistoricoAnual.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}

	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/class_ALUMNO.php");
	//buscando todas las carreras
	
	
	echo'<table border="1" width="60%">
	<tr>
		<th></th>
		<th>yearConsulta</th>
		<th>yearIngresoCarrera</th>
		<th>Sede</th>
		<th>Carrera</th>
		<th>idAlumno</th>
		<th>Rut</th>
		<th bgcolor="#66CCFF">Situacion</th>
		<th>idContrato</th>
		<th>nivel 1 semestre</th>
		<th>Arancel</th>
		<th>SaldoAfavor</th>
		<th>lineaCredito</th>
		<th>CantidadBeca</th>
		<th>%beca</th>
		<th>BNM</th>
		<th>BET</th>
		<th>Cuotas</th>
		<th>Pagos</th>
		<th bgcolor="#00FF99">Situacion</th>
		<th>idContrato</th>
		<th>nivel 2 semestre</th>
		<th>Arancel</th>
		<th>SaldoAfavor</th>
		<th>lineaCredito</th>
		<th>CantidadBeca</th>
		<th>%beca</th>
		<th>BNM</th>
		<th>BET</th>
		<th>Cuotas</th>
		<th>Pagos</th>
		
	</tr>';
	$ARRAY_COLOR=array('#66CCFF', '#00FF99');
	
	$datosOK=false;
	if(isset($_GET["yearConsulta"])){
		if(is_numeric($_GET["yearConsulta"])){$YEARCONSULTA=array($_GET["yearConsulta"]); $datosOK=true;}
		}
	
	if($datosOK){$ARRAY_YEAR_CONSULTA=$YEARCONSULTA;}
	else{$ARRAY_YEAR_CONSULTA=array(date("Y"));}
	
			$ARRAY_PAGOS_ARANCEL=array();
				$aux=0;
				foreach($ARRAY_YEAR_CONSULTA as $x => $yearConsulta){
					
						$valorMostrar=0;	
					
						$consCA="SELECT DISTINCT(id_alumno) FROM contratos2  WHERE ano=$yearConsulta";		
						$consCA=mysqli_real_escape_string($conexion_mysqli, $consCA);
						$sqliCA=$conexion_mysqli->query($consCA) or die($conexion_mysqli->error);
						$numAlumnos=$sqliCA->num_rows;
						if(DEBUG){ echo"CANTIDAD ALUMNOS: $consCA<br>num Alumnos: $numAlumnos<br>";}
						$auxCantidadAlumnos=0;
						$auxTotalCancelado=0;
						
						$auxidContrato=array();
						$auxNivelAlumno=array();
						while($CA=$sqliCA->fetch_row()){
							$auxIdAlumno=$CA[0];
							$ALUMNO=new ALUMNO($auxIdAlumno);
							$ALUMNO->SetDebug(DEBUG);
							
							
							//primer semestre
							$ALUMNO->IR_A_PERIODO(1,$yearConsulta);
							$auxSituacionAlumno[0]=$ALUMNO->getSituacionAlumnoPeriodo();
							$auxidContrato[0]=$ALUMNO->getidContratoPeriodo();
							$auxNivelAlumno[0]=$ALUMNO->getNivelAlumnoPeriodo();
							
							$yearIngresoCarreraAlumno=$ALUMNO->getYearIngresoCarreraPeriodo();
							$sedeAlumno=$ALUMNO->getSedeAlumnoPeriodo();//guardo sede del semestre 1
							if(DEBUG){ echo"--Situacion alumno periodo: $auxSituacionAlumno[0]<br>";}
							
							//segundo Semestre
							$ALUMNO->IR_A_PERIODO(2,$yearConsulta);
							$auxSituacionAlumno[1]=$ALUMNO->getSituacionAlumnoPeriodo();
							$auxidContrato[1]=$ALUMNO->getidContratoPeriodo();
							$auxNivelAlumno[1]=$ALUMNO->getNivelAlumnoPeriodo();
							if(DEBUG){ echo"--Situacion alumno periodo: $auxSituacionAlumno[1]<br>";}
							
							$aux++;
							$PAGOS=array();
							$CUOTAS=array();
							$CONTRATO=array();
							for($xc=0;$xc<2;$xc++){
								
								//busco Pagos realizados por estos alumnos a ccuaotas del contrato
								$consPA="SELECT SUM(valor) FROM pagos WHERE por_concepto='arancel' AND id_cuota IN (SELECT letras.id FROM letras INNER join contratos2 ON letras.id_contrato=contratos2.id WHERE contratos2.id='$auxidContrato[$xc]')";
								$sqliPA=$conexion_mysqli->query($consPA) or die($conexion_mysqli->error);
								$PA=$sqliPA->fetch_row();
								$pagoAlumno=$PA[0];
								$sqliPA->free();
								if(empty($pagoAlumno)){$pagoAlumno=0;}
								$PAGOS[$xc]=$pagoAlumno;
								
								//busco cuotas del contrato
								$consCC="SELECT SUM(valor) FROM letras WHERE id_contrato='$auxidContrato[$xc]'";
								$sqliCC=$conexion_mysqli->query($consCC) or die($conexion_mysqli->error);
								$CC=$sqliCC->fetch_row();
								$cuotasAlumno=$CC[0];
								$sqliCC->free();
								if(empty($cuotasAlumno)){$cuotasAlumno=0;}
								$CUOTAS[$xc]=$cuotasAlumno;
								
								//datos del contrato
								$consDC="SELECT * FROM contratos2 WHERE id='$auxidContrato[$xc]'";
								$sqliDC=$conexion_mysqli->query($consDC) or die($conexion_mysqli->error);
								$DC=$sqliDC->fetch_assoc();
								$CONTRATO[$xc]["arancel"]=$DC["arancel"];
								$CONTRATO[$xc]["total"]=$DC["total"];
								$CONTRATO[$xc]["lineaCredito"]=$DC["linea_credito_paga"];
								$CONTRATO[$xc]["saldoAfavor"]=$DC["saldo_a_favor"];
								$CONTRATO[$xc]["lineaCredito"]=$DC["linea_credito_paga"];
								$CONTRATO[$xc]["cantidadBeca"]=$DC["cantidad_beca"];
								$CONTRATO[$xc]["porcentajeBeca"]=$DC["porcentaje_beca"];
								$CONTRATO[$xc]["BNM"]=$DC["aporte_beca_nuevo_milenio"];
								$CONTRATO[$xc]["BET"]=$DC["aporte_beca_excelencia"];
								
								$sqliDC->free();
							
								
								//si los contratos son iguales, no continuo con el ciclo
								if($auxidContrato[0]==$auxidContrato[1]){break;}
							}
								

							$validador=md5("GDXT".date("d-m-Y"));
							$url_destino='http://186.10.233.98/~cftmassa/buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$auxIdAlumno;
							echo'<tr>
									<td>'.$aux.'</td>
									<td>'.$yearConsulta.'</td>
									<td>'.$yearIngresoCarreraAlumno.'</td>
									<td>'.$sedeAlumno.'</td>
									<td bgcolor="'.COLOR_CARRERA($ALUMNO->getIdCarreraPeriodo()).'">'.NOMBRE_CARRERA($ALUMNO->getIdCarreraPeriodo()).'</td>
									<td><a href="'.$url_destino.'" title="Revisar este Alumno">'.$auxIdAlumno.'</a></td>
									<td>'.$ALUMNO->getRut().'</td>';
									
							  for($xc=0;$xc<2;$xc++){
							   echo'<td bgcolor="'.$ARRAY_COLOR[$xc].'">'.$auxSituacionAlumno[$xc].'</td>
									<td>'.$auxidContrato[$xc].'</td>';
									if(isset($CONTRATO[$xc]["arancel"])){$arancel=$CONTRATO[$xc]["arancel"];}else{$arancel=0;}
									if(isset($CONTRATO[$xc]["saldoAfavor"])){$saldoAfavor=$CONTRATO[$xc]["saldoAfavor"];}else{$saldoAfavor=0;}
									if(isset($CONTRATO[$xc]["lineaCredito"])){$lineaCredito=$CONTRATO[$xc]["lineaCredito"];}else{$lineaCredito=0;}
									if(isset($CONTRATO[$xc]["cantidadBeca"])){$cantidadBeca=$CONTRATO[$xc]["cantidadBeca"];}else{$cantidadBeca=0;}
									if(isset($CONTRATO[$xc]["porcentajeBeca"])){$porcentajeBeca=$CONTRATO[$xc]["porcentajeBeca"];}else{$porcentajeBeca=0;}
									if(isset($CONTRATO[$xc]["BNM"])){$BNM=$CONTRATO[$xc]["BNM"];}else{$BNM=0;}
									if(isset($CONTRATO[$xc]["BET"])){$BET=$CONTRATO[$xc]["BET"];}else{$BET=0;}
									
									if(isset($CUOTAS[$xc])){$cuotas=$CUOTAS[$xc];}else{$cuotas=0;}
									if(isset($PAGOS[$xc])){$pagos=$PAGOS[$xc];}else{$pagos=0;}
									if(isset($auxNivelAlumno[$xc])){$auxNivel=$auxNivelAlumno[$xc];}else{$auxNivel=0;}
									
									
								echo'<td>'.$auxNivel.'</td>
									<td>'.$arancel.'</td>
									<td>'.$saldoAfavor.'</td>
									<td>'.$lineaCredito.'</td>
									<td>'.$cantidadBeca.'</td>
									<td>'.$porcentajeBeca.'</td>
									<td>'.$BNM.'</td>
									<td>'.$BET.'</td>
									
									<td>'.$cuotas.'</td>
									<td>'.$pagos.'</td>';
							  }
									
							echo'</tr>';
						}//fin while
					}//fin foreach
	
	echo'</table>';
	$conexion_mysqli->close();
}
?>