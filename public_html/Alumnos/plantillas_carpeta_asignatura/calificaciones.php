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


	
	require('../../libreria_publica/fpdf/cellpdf.php');
	
	$logo="../../BAses/Images/logo_cft.jpg";
	$borde=0;
	$borde_2=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="CALIFICACIONES";
	$zoom=50;
	
    $pdf=new CellPDF();
	//$pdf=new fpdf();
	
	$pdf->AddPage('L','mm','oficio');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetAutoPageBreak(FALSE,10);
	
	
	$pdf->image($logo,10,10,26,20,'jpg'); //este es el logo
	
	$pdf->SetFont('Arial','B',16);
	
	

	$pdf->Cell(310,15,$titulo,$borde,1,'C');
	//parrafo 1
	
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(160,12,"",$borde,0,'L');
	$pdf->Cell(90,6,"NOTAS PARCIALES",$borde_2,0,'C');
	$pdf->Cell(1,6,"",$borde,0,'L');
	
	
	$pdf->SetFont('Arial','',10);
	$pdf->VCell(12,30,"Promedio \n Parcial",1,0,'C');
	$pdf->VCell(12,30,"Prueba \n Global",1,0,'C');
	
	$pdf->Cell(1,6,"",$borde,0,'L');
	
	$pdf->VCell(12,30,"Promedio \n Final",1,0,'C');
	$pdf->VCell(12,30,"Prueba \n de Repeticion",1,0,'C');
	$pdf->VCell(12,30,"NOTA \n FINAL",1,1,'C');
	
	$pdf->SetXY(170,31);
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,1,'L');
	
	$pdf->SetY(43);
	$pdf->Cell(12,12,"N.",$borde_2,0,'C');
	$pdf->Cell(148,12,"NOMBRES Y APELLIDOS",$borde_2,1,'C');
	//fin cabeceras
	
	for($i=1;$i<=25;$i++)
	{
	
		$pdf->Cell(12,6,$i,$borde_2,0,'C');
		$pdf->Cell(148,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		
		$pdf->Cell(1,6,"",$borde,0,'L');
		
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		
		$pdf->Cell(1,6,"",$borde_2,0,'L');
		
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,1,'L');
	}
	
	
//----------------------------------------------------------------------//

$pdf->AddPage('L','mm','oficio');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetAutoPageBreak(FALSE,10);
	
	
	$pdf->image($logo,10,10,26,20,'jpg'); //este es el logo
	
	$pdf->SetFont('Arial','B',16);
	
	

	$pdf->Cell(310,15,$titulo,$borde,1,'C');
	//parrafo 1
	
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(160,12,"",$borde,0,'L');
	$pdf->Cell(90,6,"NOTAS PARCIALES",$borde_2,0,'C');
	$pdf->Cell(1,6,"",$borde,0,'L');
	
	
	$pdf->SetFont('Arial','',10);
	$pdf->VCell(12,30,"Promedio \n Parcial",1,0,'C');
	$pdf->VCell(12,30,"Prueba \n Global",1,0,'C');
	
	$pdf->Cell(1,6,"",$borde,0,'L');
	
	$pdf->VCell(12,30,"Promedio \n Final",1,0,'C');
	$pdf->VCell(12,30,"Prueba \n de Repeticion",1,0,'C');
	$pdf->VCell(12,30,"NOTA \n FINAL",1,1,'C');
	
	$pdf->SetXY(170,31);
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,0,'L');
	$pdf->Cell(6,24,"",$borde_2,1,'L');
	
	$pdf->SetY(43);
	$pdf->Cell(12,12,"N.",$borde_2,0,'C');
	$pdf->Cell(148,12,"NOMBRES Y APELLIDOS",$borde_2,1,'C');
	//fin cabeceras
	
	for($j=$i;$j<=50;$j++)
	{
	
		$pdf->Cell(12,6,$j,$borde_2,0,'C');
		$pdf->Cell(148,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		$pdf->Cell(6,6,"",$borde_2,0,'L');
		
		$pdf->Cell(1,6,"",$borde,0,'L');
		
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		
		$pdf->Cell(1,6,"",$borde_2,0,'L');
		
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,0,'L');
		$pdf->Cell(12,6,"",$borde_2,1,'L');
	}
			
$pdf->Output();

?>