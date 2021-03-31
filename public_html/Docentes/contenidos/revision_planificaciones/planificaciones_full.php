<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_planificaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		require("../../../../funciones/funcion.php");
		require('../../../libreria_publica/fpdf/mc_table.php');
		
$continuar=false;

if(isset($_GET["sede"])){$sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"])); $var_get_1=true;}
if(isset($_GET["semestre"])){$semestre=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["semestre"])); $var_get_2=true;}
if(isset($_GET["year"])){$year=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year"])); $var_get_3=true;}	

if($var_get_1 and $var_get_2 and $var_get_3){ $continuar=true;}		
		
if($continuar)		
{
	//---------------------------------------------------//
		require("../../../../funciones/VX.php");
		$evento="Exporta  Planificaciones FULL -> PDF  Sede $sede periodo [ $semestre - $year]";
		REGISTRA_EVENTO($evento);
		//---------------------------------------------------//
		$logo="../../../BAses/Images/logo_cft.jpg";
		$fecha_actual_palabra=fecha();
		$fecha_actual=date("d-m-Y");
		$salto_Y=15;//separacion entre parrafos
		$hoja_oficio[0]=217;
		$hoja_oficio[1]=330;
		
		$borde=1;
		
		$letra_1=14;
		$autor="ACX";
		$pdf=new PDF_MC_Table('L','mm',$hoja_oficio);
		$zoom=75;
		$pdf->SetAuthor($autor);	
		$pdf->SetDisplayMode($zoom);
		
		$cons_MAIN="SELECT toma_ramo_docente.* FROM toma_ramo_docente LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera = mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' ORDER by toma_ramo_docente.id_carrera, toma_ramo_docente.cod_asignatura, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
			
			if(DEBUG){ echo"--> $cons_MAIN<br>";}
			$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
			$num_toma_ramo_docente=$sqli_MAIN->num_rows;
			if(DEBUG){ echo"N. Toma ramo docente: $num_toma_ramo_docente<br>";}
			$aux=0;
			$planificaciones_ok=0;
			$planificaciones_error=0;
			
			if($num_toma_ramo_docente>0)
			{
				while($TRD=$sqli_MAIN->fetch_assoc())
				{
					$TRD_cod_asignatura=$TRD["cod_asignatura"];
					$TRD_id_carrera=$TRD["id_carrera"];
					$TRD_jornada=$TRD["jornada"];
					$TRD_grupo=$TRD["grupo"];
					$TRD_id_funcionario=$TRD["id_funcionario"];
					$TRD_sede=$TRD["sede"];
					$TRD_year=$TRD["year"];
					$TRD_semestre=$TRD["semestre"];
					
					if($TRD_cod_asignatura>0)
					{
						 list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($TRD_id_carrera, $TRD_cod_asignatura);
						$condicion_planificacion=ESTADO_PLANIFICACION_DOCENTE($TRD_sede, $TRD_year, $TRD_semestre, $TRD_id_carrera, $TRD_cod_asignatura, $TRD_jornada, $TRD_grupo, $TRD_id_funcionario);
							
						
						if($nivel_asignatura>0)
						{
							$aux++;
							$escribir_registro=true;
							if($condicion_planificacion=="OK")
							{ $planificaciones_ok++; $color_condicion="#00FF00";}
							else
							{ $planificaciones_error++; $color_condicion="#FF0000";}
						
						}
						else{ $escribir_registro=false;}
					}
					else
					{$escribir_registro=false;}
					
					if(DEBUG){ echo"id_funcionario: $TRD_id_funcionario id_carrera: $TRD_id_carrera asignatura: $TRD_cod_asignatura semestre: $TRD_semestre YEAR: $TRD_year<br>";}
					
					if($escribir_registro){if(DEBUG){ echo"Si escribir esta Planificacion<br>";}}
					else{ if(DEBUG){ echo"No escribir esta Planificacion<br>";}}
					
					if($escribir_registro)
					{
						$nombre_carrera=NOMBRE_CARRERA($TRD_id_carrera);
						$nombre_funcionario=NOMBRE_PERSONAL($TRD_id_funcionario);
						
						///horas de programa
						$TOTAL_HORAS_PROGRAMA=0;
						$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura'";
						if(DEBUG){ echo"--> $cons_HT<br>";}
						$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
						$num_programas=$sqli_HT->num_rows;
						if($num_programas>0)
						{
							while($HT=$sqli_HT->fetch_row())
							{
								$aux_numero_unidad=$HT[0];
								$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
								$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
									$Pnh=$sqli_aux->fetch_row();
									$aux_numero_hora_x_unidad=$Pnh[0];
									if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
								$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
								$sqli_aux->free();	
							}
						}
						$sqli_HT->free();
						
						////definicion de parametros
						$sub_titulo=" $nombre_carrera - $nombre_asignatura Jornada: $TRD_jornada Grupo: $TRD_grupo ($TRD_semestre / $TRD_year)";
						$pdf->AddPage();
						$pdf->SetTitle($sub_titulo);	
						//---------INICIO ESCRITURA---------//
						///logo
						$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
						$pdf->Ln(5);
						//titulo
						$pdf->SetFont('Times','',12);
						//$pdf->MultiCell(310,6,$sede.", ".fecha().".-",$borde*0,'R');
						$Y_actual=$pdf->GetY();
					
						$pdf->SetFont('Times','',24);
						$pdf->MultiCell(310,12,"Planificacion de Clases",$borde*0,'C');
						$pdf->Ln();
						
						$pdf->SetFont('Times','',12);
						$pdf->Cell(30,6,"Sede",$borde,0,"L");
						$pdf->Cell(130,6,$TRD_sede,$borde,1,"L-");
						$pdf->Cell(30,6,"Carrera",$borde,0,"L");
						$pdf->Cell(130,6,$TRD_id_carrera." ".utf8_decode($nombre_carrera),$borde,1,"L");
						
						$pdf->Cell(30,6,"Jornada",$borde,0,"L");
						$pdf->Cell(10,6,$TRD_jornada,$borde,0,"L");
						$pdf->Cell(30,6,"Nivel",$borde,0,"L");
						$pdf->Cell(90,6,$nivel_asignatura,$borde,1,"L-");
						
						$pdf->Cell(30,6,"Asignatura",$borde,0,"L");
						$pdf->Cell(130,6,$TRD_cod_asignatura." ".utf8_decode($nombre_asignatura),$borde,1,"L");
						$pdf->Cell(30,6,"Docente",$borde,0,"L");
						$pdf->Cell(130,6,utf8_decode($nombre_funcionario),$borde,1,"L");
						
						$pdf->Cell(30,6,"Periodo",$borde,0,"L");
						$pdf->Cell(130,6,"$TRD_semestre Semestre - $TRD_year",$borde,1,"L");
						$pdf->Cell(30,6,"Hrs Programa",$borde,0,"L");
						$pdf->Cell(130,6,$TOTAL_HORAS_PROGRAMA,$borde,1,"L");
						$pdf->ln();
						//-----------------------------------------------------------------//
						
						$pdf->SetAligns(array("C","C","C","C","C","C","C"));
						$pdf->SetWidths(array(22,33,85,80,30,30,30));
						$pdf->Row(array("N. Semana","hrs. por Semana","Contenidos Tematicos","Actividad", "Implemento Apoyo a la Docencia", "Evaluacion", "Bibliografia"));

						///planificaciones
						$cons_e="SELECT * FROM planificaciones WHERE sede='$TRD_sede' AND id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura' AND jornada='$TRD_jornada' AND grupo='$TRD_grupo' AND semestre='$TRD_semestre' AND year='$TRD_year' AND id_funcionario='$TRD_id_funcionario' ORDER by numero_semana";
						if(DEBUG){ echo"---->$cons_e<br>";}
						$sqli_e=$conexion_mysqli->query($cons_e)or die("Planificaciones ".$conexion_mysqli->error);
						$num_planificaciones=$sqli_e->num_rows;
						if(DEBUG){ echo"Numero Planificaciones: $num_planificaciones<br>";}
						if(DEBUG){ echo"$cons_e<br>num planificaciones: $num_planificaciones<br>";}
						$SUMA_HORAS_SEMANALES=0;
						if($num_planificaciones>0)
						{
							$aux=0;
							while($P=$sqli_e->fetch_assoc())
							{
								$id_planificacion=$P["id_planificacion"];
								$id_programa=$P["id_programa"];
								$numero_semana=$P["numero_semana"];
								$horas_semana=$P["horas_semana"];
								
								$SUMA_HORAS_SEMANALES+=$horas_semana;
								
								$actividad=$P["actividad"];
								$implemento=$P["implemento"];
								$evaluacion=$P["evaluacion"];
								$bibliografia=$P["bibliografia"];
								$contenido_tematico_opcional=$P["contenido_tematico_opcional"];
								//-----------------------------------------------//
								if($id_programa>0)
								{
									$cons="SELECT * FROM programa_estudio WHERE id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura' AND id_programa='$id_programa' LIMIT 1";
									$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
									$PX=$sqli->fetch_assoc();
									$P_contenido=$PX["contenido"];
										
										$P_numero_unidad=$PX["numero_unidad"];
										$P_nombre_unidad=$PX["nombre_unidad"];
									$sqli->free();	
								}
								else
								{
									$P_contenido=$contenido_tematico_opcional;
									$P_numero_unidad="otro";
									$P_nombre_unidad="";
								}
								//------------------------------------------------//
								$pdf->SetAligns(array("C","C","L","L","C","C","C"));
								$pdf->SetWidths(array(22,33,85,80,30,30,30));
								$pdf->Row(array($numero_semana,$horas_semana,utf8_decode($P_contenido),utf8_decode($actividad), utf8_decode($implemento), utf8_decode($evaluacion), utf8_decode($bibliografia)));
							}
							$pdf->Cell(22,6,"Total",$borde,0,"C");
							$pdf->Cell(33,6,$SUMA_HORAS_SEMANALES,$borde,1,"C");
						}
						else
						{$pdf->Cell(310,7,"Sin Planificaciones Creadas",1,1,"C");}
						$sqli_e->free();
							//FIN EVALUACIONES
							
							
					}///fin si escribir registro
				}//fin while
			}//fin si num toma ramos >0
			$sqli_MAIN->free();
			$conexion_mysqli->close();
			@mysql_close($conexion);
			$nombre_archivo="planificaciones_FULL";
			$pdf->Output($nombre_archivo, "I");
}
else
{
	echo"No se puede Continuar";
}
?>