<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->informeImprimible");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	

	
if($_GET)
{
	
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funciones_sistema.php");
	include("../../../../funciones/funcion.php");
	require_once('../../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel.php');
	
	$id_contenidoMain=base64_decode($_GET["id_contenidoMain"]);
	
	$consMAIN="SELECT * FROM contenidosMain WHERE idContenidoMain='$id_contenidoMain'";
	if(DEBUG){ echo"-->$consMAIN<br>";}
	$sqliMain=$conexion_mysqli->query($consMAIN)or die("1111:".$conexion_mysqli->error);
	$DMain=$sqliMain->fetch_assoc();
	
	$numeroSemanas=$DMain["numero_semanas"];
	$id_carrera=$DMain["id_carrera"];
	$cod_asignatura=$DMain["cod_asignatura"];
	$sede=$DMain["sede"];
	$semestre=$DMain["semestre"];
	$year=$DMain["year"];
	$jornada=$DMain["jornada"];
	$grupo_curso=$DMain["grupo"];
	$id_funcionario=$DMain["id_funcionario"];
	
	if(empty($id_contenidoMain)){$id_contenidoMain=0;}
$sqliMain->free();
	
	////----------------------------------------------------------------///
	

	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	$nombre_funcionario=NOMBRE_PERSONAL($id_funcionario);
	
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
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
			$logo="../../../BAses/Images/logo_cft.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=15;//separacion entre parrafos
			
			
			$borde=1;
			
			$letra_1=14;
			$autor="ACX";
			$sub_titulo=" $nombre_carrera - $nombre_asignatura Jornada: $jornada Grupo: $grupo_curso ($semestre / $year)";
			$zoom=75;	
			//inicializacion de pdf
			/** Include PHPExcel */

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Elias Fernandez")
							 ->setLastModifiedBy("Elias Fernandez")
							 ->setTitle("planificaciones")
							 ->setSubject("Traspaso de formatos")
							 ->setCategory("Cambio Formato");
		
		// Add a drawing to the worksheet
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath('../../../BAses/Images/logo_cft.jpg');
		$objDrawing->setHeight(65);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());	

		$objPHPExcel->setActiveSheetIndex(0)
           // ->setCellValue("A1", "")
            ->setCellValue('A5', "Contenidos de Clases")
            ->setCellValue('A6', "Sede")
            ->setCellValue('B6', "$sede")
			->setCellValue('A7', "Carrera")
			->setCellValue('B7', $nombre_carrera)
			->setCellValue('A8', "Jornada")
			->setCellValue('B8', "$jornada")
			->setCellValue('A9', "Nivel")
			->setCellValue('B9', $nivel_asignatura)
			->setCellValue('A10', "Asignatura")
			->setCellValue('B10', $nombre_asignatura)
			->setCellValue('A11', "Docente")
			->setCellValue('B11', $nombre_funcionario)
			->setCellValue('A12', "Periodo")
			->setCellValue('B12', "$semestre Semestre - $year")
			->setCellValue('A13', "Hrs Programa")
			->setCellValue('B13', $TOTAL_HORAS_PROGRAMA);
		
	$sharedStyle1 = new PHPExcel_Style();
	$sharedStyle2 = new PHPExcel_Style();
	$sharedStyle3 = new PHPExcel_Style();
	$sharedStyle4 = new PHPExcel_Style();
	
	$sharedStyle1->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'FFCCFFCC')
							),
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 ));
	$sharedStyle2->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'FF99CCFF')
							),
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )); 
		
	$sharedStyle3->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'FFCCCCCC')
							),
		'font'    => array(
						'bold'      => true
							),
		'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )); 
	$sharedStyle4->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'FFFF0000')
							),
		'font'    => array(
						'bold'      => true
							),					
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )); 	 
		 
	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A5:B13");	
	 	 	
	$objPHPExcel->setActiveSheetIndex(0)
		//-----------------------------------------------------------------//
				->setCellValue("A16", "N. Semana")
				->setCellValue('B16', "Fecha de Clase")
				->setCellValue('C16', "Horario Inicio Clase")
				->setCellValue('D16', "Duracion Clase Hrs.")
				->setCellValue('E16', "Contenido")
				->setCellValue('F16', "Tipo Actividad")
				->setCellValue('G16', "Bibliografia");
	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "A16:G16");	
		
		///planificaciones
	 	$cons_e="SELECT * FROM contenidosDetalle WHERE idContenidoMain='$id_contenidoMain' ORDER by numero_semana";
		$sqli_e=$conexion_mysqli->query($cons_e)or die("Planificaciones ".$conexion_mysqli->error);
		$num_contenidos=$sqli_e->num_rows;
		if(DEBUG){ echo"$cons_e<br>num contenidos: $num_contenidos<br>";}
		$SUMA_HORAS_SEMANALES=0;
		$cuenta_linea_excel=17;
		if($num_contenidos>0)
		{
			$aux=0;
			
			while($P=$sqli_e->fetch_assoc())
			{
				$id_contenido=$P["id_contenido"];
			
				$numero_semana=$P["numero_semana"];
				$fecha_clase=$P["fecha_clase"];
				$horario_inicio_clase=$P["horario_inicio_clase"];
				$duracion_clase=$P["duracion_clase"];
				
				$contenido=$P["contenido"];
				$tipo_actividad=$P["tipo_actividad"];
				$bibliografia=$P["bibliografia"];
				//-----------------------------------------------//
				
				$SUMA_HORAS_SEMANALES+=$duracion_clase;
				
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".$cuenta_linea_excel, $numero_semana)
						->setCellValue('B'.$cuenta_linea_excel, $fecha_clase)
						->setCellValue('C'.$cuenta_linea_excel, $horario_inicio_clase)
						->setCellValue('D'.$cuenta_linea_excel, $duracion_clase)
						->setCellValue('E'.$cuenta_linea_excel, $contenido)
						->setCellValue('F'.$cuenta_linea_excel, $tipo_actividad)
						->setCellValue('G'.$cuenta_linea_excel, $bibliografia);
				$cuenta_linea_excel++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".$cuenta_linea_excel, "Total")
						->setCellValue('D'.$cuenta_linea_excel, $SUMA_HORAS_SEMANALES);
						
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "A17:G".$cuenta_linea_excel);		
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "A".$cuenta_linea_excel.":G".$cuenta_linea_excel);			
		}
		else
		{
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".$cuenta_linea_excel, "Sin Contenidos Creados");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "A".$cuenta_linea_excel.":G".$cuenta_linea_excel);					
			$cuenta_linea_excel++;
			
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "A18:G".$cuenta_linea_excel);		
		}
		$sqli_e->free();
		//FIN EVALUACIONES
		
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		
		$conexion_mysqli->close();
		
		$nombre_archivo="Contenidos_".date("Ymd_His");
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle("Contenidos");
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		if(DEBUG){}
		else{
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		}
		exit;
}
else{ echo"Sin Datos";}
?>