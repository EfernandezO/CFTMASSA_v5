<?php
//--------------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	/////////////////////////
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../libreria_publica/fpdf/fpdf.php");

	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	////////////////////////
	  //alumno antecedentes
	  
	  $cons_A="SELECT * FROM alumno_antecedentes WHERE id_alumno='$id_alumno' LIMIT 1";
	  if(DEBUG){ echo"$cons_A<br>";}
	  $sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	  $DA=$sql_A->fetch_assoc();
	  
	  	$A_licencia_media=$DA["licencia_media"];
		$A_certificado_nacimiento=$DA["certificado_nacimiento"];
		$A_foto_carnet=$DA["foto_carnet"];
		$A_pase_escolar=$DA["pase_escolar"];
		$A_certificado_residencia=$DA["certificado_residencia"];
		
		if($A_licencia_media==1){ $A_licencia_media_condicion='Ok';}
		else{ $A_licencia_media_condicion="Pendiente";}
		if($A_certificado_nacimiento==1){ $A_certificado_nacimiento_condicion='Ok';}
		else{ $A_certificado_nacimiento_condicion="Pendiente";}
		if($A_foto_carnet==1){ $A_foto_carnet_condicion='Ok';}
		else{ $A_foto_carnet_condicion="Pendiente";}
		if($A_pase_escolar==1){ $A_pase_escolar_condicion='Si';}
		else{ $A_pase_escolar_condicion="No";}
		if($A_certificado_residencia==1){ $A_certificado_residencia_condicion='Ok';}
		else{ $A_certificado_residencia_condicion="Pendiente";}
		
	$sql_A->free();
	////////////////////////
	$cons_B="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$DX=$sql_B->fetch_assoc();
		$A_liceo=$DX["liceo"];
		$A_liceo_egreso=$DX["liceo_egreso"];
	$sql_B->free();	
	//////////////////////////
	
	////definicion de parametros
	$logo="../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	$year_limite=date("Y");
	$year_limite=2021;
	$fecha_limite="30 de Marzo del 2021";
	
	$borde=0;
	$letra_1=12;
	$autor="ACX";
	$titulo="RECEPCION DE DOCUMENTOS";
	$zoom=75;
	
	//inicializacion de pdf
	$pdf=new FPDF('P','mm','letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);


		$parrafo_1="       Don(ña). ".$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"].", Run.:".$_SESSION["SELECTOR_ALUMNO"]["rut"].", declara ser alumno egresado de la enseñanza media del Liceo: ".$A_liceo." el año ".$A_liceo_egreso.".";
		$parrafo_2="El Actualmente presenta la siguiente Documentación:";
		$parrafo_3="En Caso de tener documentacion Pendiente, se Compromete a traerla antes del $fecha_limite";
		$parrafo_4="El no cumplimiento le otorga derecho al C.F.T. Massachusetts de hacer exigible los compromisos económicos contraídos por el Primer Semestre del año $year_limite.";
		$parrafo_5="Libero de cualquier responsabilidad al C.F.T. Massachusetts si no cumplo con la exigencia de ser alumno egresado de la Enseñanza Media.";
	///escritura de datos
	///logo
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(195,6,$sede_alumno.", ".$fecha_actual_palabra,$borde,1,"R");
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(195,30,$titulo,$borde,1,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo_1,$borde,1,'L');
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo_2,$borde,1,'L');
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,6,"N.",1,0,'C');
	$pdf->Cell(50,6,"Condicion",1,0,'C');
	$pdf->Cell(115,6,"Documento",1,1,'C');
	$pdf->SetFont('Arial','',12);
	///////1
	$pdf->Cell(30,6,"1",1,0,'C');
	$pdf->Cell(50,6,$A_licencia_media_condicion,1,0,'C');
	$pdf->Cell(115,6,"Licencia Enseñanza Media",1,1,'C');
	///////2
	$pdf->Cell(30,6,"2",1,0,'C');
	$pdf->Cell(50,6,$A_certificado_nacimiento_condicion,$borde,0,'C');
	$pdf->Cell(115,6,"Certificado de Nacimiento",1,1,'C');
	///////3
	$pdf->Cell(30,6,"3",1,0,'C');
	$pdf->Cell(50,6,$A_foto_carnet_condicion,1,0,'C');
	$pdf->Cell(115,6,"Foto Carnet",1,1,'C');
	///////4
	$pdf->Cell(30,6,"4",1,0,'C');
	$pdf->Cell(50,6,$A_certificado_residencia_condicion,1,0,'C');
	$pdf->Cell(115,6,"Certificado Residencia",1,1,'C');
	///////5
	$pdf->Cell(30,6,"5",1,0,'C');
	$pdf->Cell(50,6,$A_pase_escolar_condicion,1,0,'C');
	$pdf->Cell(115,6,"Solicito Pase Escolar",1,1,'C');
	
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo_3,$borde,'L');
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo_4,$borde,'L');
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo_5,$borde,'L');
	
	$pdf->SetXY(150,230);
	$pdf->Cell(50,6,"__________________",$borde,1,'C');
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(150);
	$pdf->Cell(50,4,$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"],$borde,1,'C');
	$pdf->SetX(150);
	$pdf->Cell(50,4,"ALUMNO",$borde,1,'C');
	
		 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Emision Ficha Recepcion Documentos a alumno ID(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
}
else
{
	header("location : index.php");
}

?>