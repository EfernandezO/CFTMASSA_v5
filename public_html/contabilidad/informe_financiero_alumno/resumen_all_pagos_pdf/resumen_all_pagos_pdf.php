<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_GET))
{
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	require('../../../libreria_publica/fpdf/mc_table.php');
	
	////definicion de parametros
	$logo="../../../BAses/Images/logo_cft.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	$idAlumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$idCarreraAlumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];

	
	
	$borde=1;
	$borde1=0;
	$fecha=fecha();
	$letra_1=12;
	$letra_2=8;
	$autor="ACX";
	$titulo="PAGOS REALIZADOS";
	$zoom=75;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	
	//inicializacion de pdf
	$pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->image($logo,14,1,30,24,'jpg'); //este es el logo
	
	//titulo
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(195,15,$fecha_actual_palabra,$borde1,1,"R");
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(195,30,$titulo,$borde1,1,'C');
	//parrafo 1
	$pdf->SetFont('Arial','',$letra_1);
	///datos alumno
	$pdf->Cell(30,6,"Rut",$borde1,0,'L');
	$pdf->Cell(165,6,$_SESSION["SELECTOR_ALUMNO"]["rut"],$borde1,1,'L');
	
	$pdf->Cell(30,6,"Alumno",$borde1,0,'L');
	$pdf->Cell(165,6,$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"],$borde1,1,'L');
	
	

	
	
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	//busco todos los id con ese rut

	$pdf->Cell(30,6,"Carrera",$borde1,0,'L');
	$pdf->Cell(165,6,$idCarreraAlumno.' - '.$yearIngresoCarrera,$borde1,1,'L');
	
		
		/////////////////
		//pagos
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_2);
		$pdf->Cell(195,6,"Detalle",1,1,'C');
		$pdf->Ln();
		$pdf->Cell(10,6,"N",$borde,0,'L');
		$pdf->Cell(15,6,"ID",$borde,0,'L');
		$pdf->Cell(25,6,"Fecha",$borde,0,'L');
		$pdf->Cell(25,6,"Valor",$borde,0,'L');
		$pdf->Cell(20,6,"Pago",$borde,0,'L');
		$pdf->Cell(30,6,"Concepto",$borde,0,'L');
		$pdf->Cell(70,6,"Glosa",$borde,1,'L');
		
		$pdf->SetFont('Arial','',$letra_2);
		$cons_P="SELECT * FROM pagos WHERE id_alumno='$idAlumno' ORDER by fechapago DESC";
		if(DEBUG){ echo"--->$cons_P<br>";}
		$sql_P=$conexion_mysqli->query($cons_P)or die("PAGOS: ".$conexion_mysqli->error);
		$num_pagos=$sql_P->num_rows;
		if($num_pagos>0)
		{
			$contador=0;
			while($P=$sql_P->fetch_assoc())
			{
				$contador++;
				
				$P_id=$P["idpago"];
				$P_fechapago=$P["fechapago"];
				$P_valor=$P["valor"];
				$P_glosa=$P["glosa"];
				$P_forma_pago=$P["forma_pago"];
				$P_fechaV_cheque=$P["fechaV_cheque"];
				$P_por_concepto=$P["por_concepto"];
				if($P_forma_pago=="cheque"){ $P_forma_pago_label=$P_forma_pago."(".$P_fechaV_cheque.")";}
				else{ $P_forma_pago_label=$P_forma_pago;}
				$pdf->SetWidths(array(10,15,25,25,20,30,70));
				$pdf->SetAligns(array("L","L","C", "R","C","C","R"));
				$pdf->Row(array($contador, $P_id, $P_fechapago, "$".number_format($P_valor), $P_forma_pago_label, $P_por_concepto, $P_glosa));
			}
		}
		else
		{
			$pdf->Cell(195,6,"Sin Pagos Registrados...",$borde,1,'C');
		}
		$sql_P->free();
		 /////Registro ingreso///
		 include("../../../../funciones/VX.php");
		 $evento="Emision Certificado PAGOS a alumno ID(".$_SESSION["SELECTOR_ALUMNO"]["id"].")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
	$conexion_mysqli->close();
	$pdf->Output();
}
else
{
	header("location: ../index.php");
}