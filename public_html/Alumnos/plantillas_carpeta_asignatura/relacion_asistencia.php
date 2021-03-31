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
	$titulo="RELACION DE ASISTENCIA DE LA ASIGNATURA";
	$zoom=50;
	
	$pdf=new fpdf();
	
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
	$pdf->Cell(30,6,"",$borde,0,'L');
	$pdf->Cell(120,6,"CURSO:____________________________________________________",$borde,0,'L');
	$pdf->Cell(120,6,"PROFESOR:_________________________________________________",$borde,0,'L');
	$pdf->Cell(40,6,"P = PRESENTE",$borde,1,'L');
	
	$pdf->Cell(30,6,"",$borde,0,'L');
	$pdf->Cell(120,6,utf8_decode("AÑO:_______________________________________________________"),$borde,0,'L');
	$pdf->Cell(120,6,"MES:_______________________________________________________",$borde,0,'L');
	$pdf->Cell(40,6,"A = AUSENTE",$borde,1,'L');
	
	$pdf->Cell(270,6,"",$borde,0,'L');
	$pdf->Cell(40,6,"/ = 1 HRS ASISTIDA",$borde,1,'L');
	
	
	$pdf->Cell(12,12,utf8_decode("N°"),$borde_2,0,'C');
	$pdf->Cell(142,12,"NOBRES Y APELLIDOS",$borde_2,0,'C');
	$pdf->Cell(156,12,"DIAS",$borde_2,1,'C');
	
	//fin cabeceras
	
	for($i=1;$i<=25;$i++)
	{
		$pdf->Cell(12,6,$i,$borde_2,0,'C');
		$pdf->Cell(142,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,1,'C');
		
	}
	
//fin pagina 1

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
	$pdf->Cell(30,6,"",$borde,0,'L');
	$pdf->Cell(120,6,"CURSO:____________________________________________________",$borde,0,'L');
	$pdf->Cell(120,6,"PROFESOR:_________________________________________________",$borde,0,'L');
	$pdf->Cell(40,6,"P = PRESENTE",$borde,1,'L');
	
	$pdf->Cell(30,6,"",$borde,0,'L');
	$pdf->Cell(120,6,utf8_decode("AÑO:_______________________________________________________"),$borde,0,'L');
	$pdf->Cell(120,6,"MES:_______________________________________________________",$borde,0,'L');
	$pdf->Cell(40,6,"A = AUSENTE",$borde,1,'L');
	
	$pdf->Cell(270,6,"",$borde,0,'L');
	$pdf->Cell(40,6,"/ = 1 HRS ASISTIDA",$borde,1,'L');
	
	
	$pdf->Cell(12,12,utf8_decode("N°"),$borde_2,0,'C');
	$pdf->Cell(142,12,"NOBRES Y APELLIDOS",$borde_2,0,'C');
	$pdf->Cell(156,12,"DIAS",$borde_2,1,'C');
	
	//fin cabeceras
	
	for($j=$i;$j<=50;$j++)
	{
		$pdf->Cell(12,6,$j,$borde_2,0,'C');
		$pdf->Cell(142,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,0,'C');
		$pdf->Cell(6,6,"",$borde_2,1,'C');
		
		
	}
	
	
$pdf->Output();
?>	