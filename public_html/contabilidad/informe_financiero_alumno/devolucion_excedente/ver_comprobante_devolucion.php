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
if($_GET)
{
	$id_pago=base64_decode($_GET["id_pago"]);
	
	if((is_numeric($id_pago))and($id_pago>0))
	{ $continuar=true;}
	else{ $continuar=false;}
	
	if($continuar)
	{
		require('../../../libreria_publica/fpdf/mc_table.php');
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funcion.php");
		require("../../../../funciones/funciones_sistema.php");
		
		
		//------------------------------------------------------//
		$cons_1="SELECT * FROM pagos WHERE idpago='$id_pago' LIMIT 1";
		$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
		$P=$sqli_1->fetch_assoc();
			$P_id_alumno=$P["id_alumno"];
			$P_id_cheque=$P["id_cheque"];
			$P_valor=$P["valor"];
			$P_sede=$P["sede"];
			$P_fecha_pago=$P["fechapago"];
			$P_forma_pago=$P["forma_pago"];
			$P_aux_num_documento=$P["aux_num_documento"];
		$sqli_1->free();
		
		
		//------------------------------------------------------//
		$cons_A="SELECT * FROM alumno WHERE id='$P_id_alumno' LIMIT 1";
		$sql_A=$conexion_mysqli->query($cons_A);
		$DA=$sql_A->fetch_assoc();
			$A_rut=$DA["rut"];
			$A_nombre=$DA["nombre"];
			$A_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
		$sql_A->free();
		//------------------------------------------------------//
		
		require("../../../../funciones/VX.php");
		$evento="Devolucion de Excedente Alumno id: $P_id_alumno valor $P_valor";
		REGISTRA_EVENTO($evento);
		
		$logo="../../../BAses/Images/logoX.jpg";
		$borde=1;
		$letra_1=12;
		$letra_2=10;
		$autor="ACX";
		$titulo="Comprobante Devolucion de Excedente";
		$zoom=75;
		
		$pdf=new PDF_MC_Table();
		$pdf->AddPage('P','Letter');
		$pdf->SetAuthor($autor);
		$pdf->SetTitle($titulo);
		$pdf->SetDisplayMode($zoom);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(195,6,fecha(),$borde*0,1,'R');
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		
		
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(195,20,$titulo,$borde*0,1,'C');
		//parrafo 1
		$pdf->Ln(8);
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(135,6,"Datos del Alumno ",$borde,1,"L");
		$pdf->SetFont('Arial','',$letra_1);
		$pdf->Cell(30,6,"Rut",$borde,0,"L");
		$pdf->Cell(105,6,$A_rut,$borde,1,"L");
		$pdf->Cell(30,6,"Nombre",$borde,0,"L");
		$pdf->Cell(105,6,$A_nombre,$borde,1,"L");
		$pdf->Cell(30,6,"Apellido",$borde,0,"L");
		$pdf->Cell(105,6,$A_apellido,$borde,1,"L");
		
		
		
		
		$ARRAY_MESES=array(1=>"Enero",
							2=>"Febrero",
							3=>"Marzo",
							4=>"Abril",
							5=>"Mayo",
							6=>"Junio",
							7=>"Julio",
							8=>"Agosto",
							9=>"Septiembre",
							10=>"Octubre",
							11=>"Noviembre",
							12=>"Diciembre");
		
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(135,6,"Datos del Pago",$borde,1,"L");
		$pdf->SetFont('Arial','',$letra_1);
		$pdf->Cell(30,6,"Sede",$borde,0,"L");
		$pdf->Cell(105,6,$P_sede,$borde,1,"L");
		
		$pdf->Cell(30,6,"Total a Pagar",$borde,0,"L");
		$pdf->Cell(105,6,"$".number_format($P_valor,0,",","."),$borde,1,"L");
		
		
		
		$informacion_pago=$P_forma_pago;
		
		//cheque
		if($P_id_cheque>0)
		{
			$cons_CH="SELECT * FROM registro_cheques WHERE id='$P_id_cheque' LIMIT 1";
			$sqli_ch=$conexion_mysqli->query($cons_CH);
			$CH=$sqli_ch->fetch_assoc();
				$CH_numero=$CH["numero"];
				$CH_banco=$CH["banco"];
			$sqli_ch->free();	
			$informacion_pago.=" [Numero: $CH_numero Banco: $CH_banco]";
		}
		
		$pdf->SetFont('Arial','',$letra_1);
		$pdf->Cell(30,6,"Forma Pago",$borde,0,"L");
		$pdf->Cell(105,6,$informacion_pago,$borde,1,"L");
		$pdf->Cell(30,6,"Fecha Pago",$borde,0,"L");
		$pdf->Cell(105,6,$P_fecha_pago,$borde,1,"L");
			
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(190,5,"Detalle",$borde,1,"L");
		$pdf->SetFont('Arial','',$letra_1);
		$pdf->Cell(190,5,"Devolucion de Excedente de $ ".$P_valor.", Segun Contrato Folio: $P_aux_num_documento",$borde,1,"L");
		
		
			
				$pdf->SetY(240);
				$y_actual=$pdf->GetY();
				$pdf->SetXY(30,$y_actual);
				$pdf->MultiCell(50,6,"____________________Firma Alumno",$borde*0,"C");
				
				$pdf->SetY(240);
				$y_actual=$pdf->GetY();
				$pdf->SetXY(130,$y_actual);
				$pdf->MultiCell(50,6,"____________________Firma Finanzas",$borde*0,"C");
				
				
	
			
	
		mysql_close($conexion);
		$conexion_mysqli->close();
		if(!DEBUG){$pdf->Output();}
	}
}
else
{}
?>