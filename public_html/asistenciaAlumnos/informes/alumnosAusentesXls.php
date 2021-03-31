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
require("../../../funciones/class_ALUMNO.php");
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
            ->setCellValue('A5', "Alerta alumnos Ausencia repetida en 2 clases seguidas")
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
				->setCellValue('F15', "Fecha Clase")
				->setCellValue('G15', "Horario Clase")
				->setCellValue('H15', "Modalidad Clase");


	
	$cuentaLineaExcel=16;
	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "F15:H15");	
	$aux=0;
	foreach($ASISTENCIA_ALUMNOS->ALUMNOS_AUSENTES_X_CLASES("2") as $auxIdAlumno => $arrayClases){
		$aux++;
		$ALUMNO=new ALUMNO($auxIdAlumno);
		if(count($arrayClases)>0){
			foreach($arrayClases as $m => $auxIdClase){
				
				$ASISTENCIA_ALUMNOS->setIdClase($auxIdClase);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A".$cuentaLineaExcel, $aux)
							->setCellValue('B'.$cuentaLineaExcel, $ALUMNO->getRut())
							->setCellValue('C'.$cuentaLineaExcel, $ALUMNO->getNombre())
							->setCellValue('D'.$cuentaLineaExcel, $ALUMNO->getApellido_P())
							->setCellValue('E'.$cuentaLineaExcel, $ALUMNO->getApellido_M())
							->setCellValue('F'.$cuentaLineaExcel, $ASISTENCIA_ALUMNOS->getClaseFecha())
							->setCellValue('G'.$cuentaLineaExcel, $ASISTENCIA_ALUMNOS->getClasehorario())
							->setCellValue('H'.$cuentaLineaExcel, $ASISTENCIA_ALUMNOS->getClaseModalidad());
				$cuentaLineaExcel++;		
			}
		}
		
	}
	
		
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