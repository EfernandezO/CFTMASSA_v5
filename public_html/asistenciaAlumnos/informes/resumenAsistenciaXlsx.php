<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("AsistenciaManualAlumno->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
require("../../../funciones/class_ASISTENCIA_ALUMNOS.php");
require_once('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel.php');

if($_GET)
{
	$id_curso=base64_decode($_GET["id_curso"]);
	$ASISTENCIA_ALUMNOS = new ASISTENCIA_ALUMNOS($id_curso);
	//$ASISTENCIA_ALUMNOS->setDebug(true);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($ASISTENCIA_ALUMNOS->getIdCarrera(), 			$ASISTENCIA_ALUMNOS->getCodAsignatura());
	$nombre_carrera=NOMBRE_CARRERA($ASISTENCIA_ALUMNOS->getIdCarrera());

	////----------------------------------------------------------------///
	
	////definicion de parametros
	//inicializacion de pdf
	/** Include PHPExcel */

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Elias Fernandez")
							 ->setLastModifiedBy("Elias Fernandez")
							 ->setTitle("mayor_a_excel")
							 ->setSubject("Traspaso de formatos")
							 ->setCategory("Cambio Formato");
		
		// Add a drawing to the worksheet
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath('../../BAses/Images/logo_cft.jpg');
		$objDrawing->setHeight(65);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());	

		$objPHPExcel->setActiveSheetIndex(0)
           // ->setCellValue("A1", "")
            ->setCellValue('A5', "Resumen de Asistencia")
            ->setCellValue('A6', "Sede")
            ->setCellValue('B6', $ASISTENCIA_ALUMNOS->getSede())
			->setCellValue('A7', "Carrera")
			->setCellValue('B7', $nombre_carrera)
			->setCellValue('A8', "Jornada")
			->setCellValue('B8', $ASISTENCIA_ALUMNOS->getJornada())
			->setCellValue('A9', "Asignatura")
			->setCellValue('B9', $nombre_asignatura)
			->setCellValue('A10', "Periodo")
			->setCellValue('B10', $ASISTENCIA_ALUMNOS->getSemestre()." Semestre - ".$ASISTENCIA_ALUMNOS->getYear());
		
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
		 
	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A5:B10");	
	 	 	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A15", "N.")
				->setCellValue('B15', "Rut")
				->setCellValue('C15', "Nombres")
				->setCellValue('D15', "Apellido P")
				->setCellValue('E15', "Apellido M")
				->setCellValue('F15', "Total hrs. Impartidas")
				->setCellValue('G15', "% Asistencia");

	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "A15:E15");	
	
	$auxCodigoLetra=72;//G
	$auxColumna=chr($auxCodigoLetra);
	$prefijo="";
	
	$ARRAY_ID_CLASES=array();
	foreach($ASISTENCIA_ALUMNOS->getListaClases("ASC") as $n =>$auxIdClase){
		
		array_push($ARRAY_ID_CLASES, $auxIdClase);
		$ASISTENCIA_ALUMNOS->setIdClase($auxIdClase);
		$ASISTENCIA_ALUMNOS->getClasehorario();
		
		$ASISTENCIA_ALUMNOS->getClaseModalidad();
		
		if(DEBUG){echo"$n ->$auxIdClase<br>";}
		$auxColumna=chr($auxCodigoLetra);
		
		if($prefijo!==""){$auxPrefijo=chr($prefijo);}
		else{$auxPrefijo="";}
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($auxPrefijo.$auxColumna."15", $ASISTENCIA_ALUMNOS->getClaseFecha()." \n".$ASISTENCIA_ALUMNOS->getClasehorario()."\n ".$ASISTENCIA_ALUMNOS->getClaseModalidad()."\n ".$ASISTENCIA_ALUMNOS->getClaseDuracion());
			
		$auxCodigoLetra++;
		if($auxCodigoLetra>90){$auxCodigoLetra=65; if($prefijo==""){$prefijo=65;}else{$prefijo++;}}	
	}
	
	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "F15:".$auxColumna."15");	
	
	$cuenta_linea_excel=16;			
	foreach($ASISTENCIA_ALUMNOS->getListaAlumnos() as $n => $AuxALUMNO){
		
		$ASISTENCIA_ALUMNOS->setIdAlumno($AuxALUMNO->getIdAlumno());
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$cuenta_linea_excel, ($n+1))
			->setCellValue('B'.$cuenta_linea_excel, $AuxALUMNO->getRut())
			->setCellValue('C'.$cuenta_linea_excel,$AuxALUMNO->getNombre())
			->setCellValue('D'.$cuenta_linea_excel, $AuxALUMNO->getApellido_P())
			->setCellValue('E'.$cuenta_linea_excel, $AuxALUMNO->getApellido_M())
			->setCellValue('F'.$cuenta_linea_excel, $ASISTENCIA_ALUMNOS->HORAS_TOTAL_CLASES_IMPARTIDAS())
			->setCellValue('G'.$cuenta_linea_excel, number_format($ASISTENCIA_ALUMNOS->ALUMNO_PORCENTAJE_ASISTENCIA_CURSO(),1));
			
			$auxCodigoLetra=72;//G
			$prefijo="";
			foreach($ARRAY_ID_CLASES as $n => $auxIdClase){
				$auxColumna=chr($auxCodigoLetra);
				if($prefijo!==""){$auxPrefijo=chr($prefijo);}
				else{$auxPrefijo="";}
				
				$ASISTENCIA_ALUMNOS->setIdClase($auxIdClase);
				
				$arrayINFO=$ASISTENCIA_ALUMNOS->INFO_ASISTENCIA_ALUMNO();
				
				$auxHorasEnClase=$arrayINFO["num_horas"];
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($auxPrefijo.$auxColumna.$cuenta_linea_excel, $auxHorasEnClase);
				
				$auxCodigoLetra++;
				if($auxCodigoLetra>90){$auxCodigoLetra=65; if($prefijo==""){$prefijo=65;}else{$prefijo++;}}				
			}
		$cuenta_linea_excel++;
		
	}
	
	/*			
	
		
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A".$cuenta_linea_excel, "Total");
						
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "A17:G".$cuenta_linea_excel);		
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "A".$cuenta_linea_excel.":G".$cuenta_linea_excel);			
			
			$cuenta_linea_excel++;
			//-------------------------------------------------------------------------------------//
			
			
		*/	
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	
		$conexion_mysqli->close();

		
		$nombre_archivo="resumenAsistencia";
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle("Resumen Asistencia");
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		if(DEBUG){}
		else{
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="resumenAsistencia.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		}
		exit;
}
else{ echo"Sin Datos";}
?>