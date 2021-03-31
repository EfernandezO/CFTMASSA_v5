<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("plantillas_carpeta_asignatura_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	
	require('../../libreria_publica/fpdf/fpdf.php');
	
	$logo="../../BAses/Images/logo_cft.jpg";
	$borde=0;
	$borde_2=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="HOJA DE MATERIAS";
	$zoom=50;
	
	$pdf=new fpdf();
	
	$pdf->AddPage('L','mm','oficio');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetAutoPageBreak(FALSE,10);
	
	
	$pdf->image($logo,10,10,26,20,'jpg'); //este es el logo
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(310,27,$titulo,$borde,1,'C');
	//parrafo 1
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(310,12,"      NOMBRE PROFESOR:________________________________________________________________",$borde_2,1,'L');
	
	$pdf->Cell(30,12,"DIA",$borde_2,0,'C');
	$pdf->Cell(30,12,"MES",$borde_2,0,'C');
	$pdf->Cell(160,12,"MATERIA",$borde_2,0,'C');
	$pdf->Cell(40,12,"FIRMA",$borde_2,0,'C');
	
	$pdf->Cell(50,6,"HORAS",$borde_2,1,'C');
	
	$pdf->Cell(260,12,"",$borde,0,'C');
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(25,6,"REALIZADAS",$borde_2,0,'C');
	$pdf->Cell(25,6,"ACUMULADAS",$borde_2,1,'C');
	
	//fin cabeceras
	$pdf->SetFont('Arial','',10);
	for($i=1;$i<=12;$i++)
	{
		$pdf->Cell(30,12,"",$borde_2,0,'C');
		$pdf->Cell(30,12,"",$borde_2,0,'C');
		$pdf->Cell(160,12,"",$borde_2,0,'C');
		$pdf->Cell(40,12,"",$borde_2,0,'C');
		$pdf->Cell(25,12,"",$borde_2,0,'C');
		$pdf->Cell(25,12,"",$borde_2,1,'C');
		
	}
	

$pdf->Output();
?>	