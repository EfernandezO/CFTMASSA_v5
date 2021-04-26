<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	

if($_POST)
{
	$alumno=utf8_decode(ucwords(strtolower($_POST["alumno"])));
	$numero_caracteres_alumno=strlen($alumno);
	$fecha_emision_titulo=$_POST["fecha_emision_titulo"];
	$carrera=utf8_decode(ucwords(strtolower($_POST["carrera"])));
	$numero_caracteres_carrera=strlen($carrera);
	$fecha_titulacion=$_POST["fecha_titulacion"];
	
	$Y_aux=$_POST["Y"];
	
	if(DEBUG){var_dump($_POST);}
	
	require("../../../funciones/funcion.php");
	include ("../../../librerias/fpdf/fpdf.php");
	////definicion de parametros
	$logo="../../BAses/Images/logo_cft.jpg";
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	
	$borde=0;
	$autor="ACX";
	$titulo="plantilla titulo";
	$zoom=50;
	
	$hoja[0]=216;
	$hoja[1]=346;
	$tipo_letra='Allegro';
	
	//inicializacion de pdf
	$pdf=new FPDF('L','mm',$hoja);
	$pdf->AddFont('Allegro','','ALLEGRO.php');
	$pdf->AddFont('Chopping','','CHOPS.php');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetMargins(0,0,0);
	$pdf->SetAutoPageBreak(false);

	//$pdf->SetFont('Times','B',16);
	//$pdf->SetFont('Allegro','',46);
	//---------------------------------------------------///
	if(($numero_caracteres_alumno>35)and($numero_caracteres_alumno<45))
	{ $pdf->SetFont($tipo_letra,'',40);}
	elseif($numero_caracteres_alumno>45)
	{ $pdf->SetFont($tipo_letra,'',32);}
	else
	{ $pdf->SetFont($tipo_letra,'',46);}
	//--------------------------------------------------------
	
	$pdf->SetXY(75,96+$Y_aux);
	$pdf->Cell(210,6,$alumno,$borde,1,'C');
	
	$pdf->SetFont($tipo_letra,'',20);
	$pdf->SetXY(95,113+$Y_aux);
	$pdf->Cell(70,6,fecha($fecha_emision_titulo, false),$borde,1,'C');
	
	
	if($numero_caracteres_carrera>35)
	{ $pdf->SetFont($tipo_letra,'',32);}
	else
	{ $pdf->SetFont($tipo_letra,'',44);}
	
	$pdf->SetXY(75,137+$Y_aux);
	$pdf->Cell(210,6,$carrera,$borde,1,'C');
	
	$pdf->SetFont($tipo_letra,'',20);
	$pdf->SetXY(100,153+$Y_aux);
	$pdf->Cell(65,6,fecha($fecha_titulacion, false),$borde,1,'C');
	
			
	$pdf->Output();
}
?>