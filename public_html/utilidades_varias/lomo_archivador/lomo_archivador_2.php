<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_POST)
{
	$texto=$_POST["texto"];
	$subtexto=$_POST["subtexto"];
	if(DEBUG){var_dump($_POST);}
	
	require("../../libreria_publica/fpdf/fpdf.php");
	require("../../../funciones/VX.php");
	
	$evento="Genera Lomos Imprimibles";
	REGISTRA_EVENTO($evento);
	////definicion de parametros
	$logo="../../BAses/Images/logo_cft.jpg";
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	
	$borde=1;
	$letra_1=12;
	$autor="ACX";
	$titulo="Archivadores";
	$zoom=75;
	
	//inicializacion de pdf
	$pdf=new FPDF('L','mm','letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetMargins(0,0,0);
	$pdf->SetAutoPageBreak(false);
	///logo
	//
	$size_letra=$_POST["size_letra"];
	$avance_X=0;
	$indice=1;
	foreach($texto as $n=> $valor)
	{
		$texto_label=$valor;
		if(!empty($texto_label))
		{
			$pdf->SetFont('Times','B',$size_letra);
			$pdf->Cell(65,155,"",$borde,0,'C');
			$aux_X=$pdf->GetX();
			$aux_Y=$pdf->GetY();
			$pdf->image($logo,25+$avance_X,$aux_Y+10,35,30,'jpg'); //este es el logo
			
			$pdf->SetXY(15+$avance_X,15);
			$pdf->Cell(55,145,"",$borde,1,'C');
			$pdf->SetXY(15+$avance_X,75);
			$pdf->MultiCell(55,7, utf8_decode($texto_label),$borde,'C');
			
			if(isset($subtexto[$indice]))
			{
				$pdf->Ln();
				foreach($subtexto[$indice] as $nsub =>$valorsub)
				{
					if(DEBUG){ echo"$indicesub -> $valorsub<br>";}
					if(!empty($valorsub))
					{
						$pdf->SetFont('Times','',$size_letra-4);
						$pdf->SetX(15+$avance_X);
						$pdf->Cell(55,7,"-".utf8_decode($valorsub),0,1,"L");
					}
				}
			}
				$pdf->SetXY($aux_X,$aux_Y);
				$avance_X+=65;
				$indice++;
		}
	}
	$pdf->Output();
}
?>