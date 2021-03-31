<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$hay_registro=false;

if($_GET)
{
	$id_comprobante_egreso=base64_decode($_GET["id_comprobante_egreso"]);
	$id_comprobante_egreso=$_GET["id_comprobante_egreso"];
	$year_actual=date("Y");
	
	require('../../../../libreria_publica/fpdf/mc_table.php');
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	require("../../../../../funciones/funciones_sistema.php");
	//------------------------------------------------------//
	
	
	
	
	$cons_A="SELECT * FROM comprobante_egreso WHERE id_comprobante='$id_comprobante_egreso' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$num_reg=$sql_A->num_rows;
	if($num_reg>0){$hay_registro=true;}
	$CE=$sql_A->fetch_assoc();
		$CE_id_proveedor=$CE["id_proveedor"];
		$CE_tipo_proveedor=$CE["tipo_proveedor"];
		$CE_sede=$CE["sede"];
		$CE_valor=$CE["valor"];
		$CE_glosa=$CE["glosa"];
		$CE_cod_user=$CE["cod_user"];
		$CE_fecha_generacion=$CE["fecha_generacion"];
		$CE_fecha=$CE["fecha"];
		$CE_formaPago=$CE["formaPago"];
		$nombre_cod_user=NOMBRE_PERSONAL($CE_cod_user);
	$sql_A->free();
	//------------------------------------------------------//
	
	if(DEBUG){ echo"Hay registro: $hay_registro id_comprobanteEgreso: $id_comprobante_egreso <br>Tipo Proveedor: $CE_tipo_proveedor<br>";}
	if($hay_registro)
	{
		$cons_C="SELECT COUNT(id_comprobante) FROM comprobante_egreso WHERE fecha<='$CE_fecha' AND id_comprobante<='$id_comprobante_egreso' AND YEAR(fecha)=$year_actual";
		$sql_C=$conexion_mysqli->query($cons_C)or die("Cuenta".$conexion_mysqli->error);
			$CEC=$sql_C->fetch_row();
			$conteoYear=$CEC[0];
		$sql_C->free();	
		
		switch($CE_tipo_proveedor)
		{
			case"proveedor":
				$cons_x="SELECT * FROM proveedores WHERE id_proveedor='$CE_id_proveedor' LIMIT 1";
				$sqli_x=$conexion_mysqli->query($cons_x);
					$DP=$sqli_x->fetch_assoc();
						$rut_proveedor=$DP["rut"];
						$razon_social=$DP["razon_social"];
				$sqli_x->free();		
				break;
			case"personal":
				$cons_x="SELECT * FROM personal WHERE id='$CE_id_proveedor' LIMIT 1";
				$sqli_x=$conexion_mysqli->query($cons_x);
					$DPE=$sqli_x->fetch_assoc();
						$rut_personal=$DPE["rut"];
						$nombre_personal=$DPE["nombre"];
						$apellido_paterno=$DPE["apellido_P"];
						$apellido_materno=$DPE["apellido_M"];
						
						$rut_proveedor=$rut_personal;
						$razon_social=$nombre_personal." ".$apellido_paterno." ".$apellido_materno;
				$sqli_x->free();		
				break;	
		}
		$info_qr="aaa";
		$QR="../../../../libreria_publica/phpqrcode/imagen_QR.php?qr_info=".$info_qr;
		$QR="https://cftmassachusetts.cl/~cftmassa/libreria_publica/phpqrcode/imagen_QR.php?qr_info=rere";
		
		$logo="../../../../BAses/Images/logo_cft.jpg";
		$borde=1;
		$letra_1=12;
		$letra_2=10;
		$autor="ACX";
		$titulo="Comprobante Egreso N.".$id_comprobante_egreso;
		$zoom=40;
		
		$pdf=new PDF_MC_Table();
		$pdf->AddPage('P','letter');
		$pdf->SetAuthor($autor);
		$pdf->SetTitle($titulo);
		$pdf->SetDisplayMode($zoom);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(195,6, $CE_fecha, $borde*0,1,'R');
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		
		//$pdf->image($QR,14,10,30,24,'png'); //este es el logo
		
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(195,10,$titulo,$borde*0,1,'C');
		$pdf->Cell(195,10,"$conteoYear / $year_actual",$borde*0,1,'C');
		//parrafo 1
		$pdf->Ln(20);
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(135,6,"Datos del Proveedor ",$borde,1,"L");
		$pdf->SetFont('Arial','',$letra_1);
		$pdf->Cell(30,6,"Rut",$borde,0,"L");
		$pdf->Cell(105,6,$rut_proveedor, $borde,1,"L");
		$pdf->Cell(30,6,"Nombre",$borde,0,"L");
		$pdf->Cell(105,6,$razon_social,$borde,1,"L");
		
		$pdf->Ln();
		
		$pdf->Cell(30,6,"Sede",$borde,0,"L");
		$pdf->Cell(105,6,$CE_sede,$borde,1,"L");
		
		$pdf->Cell(30,6,"Fecha",$borde,0,"L");
		$pdf->Cell(105,6,$CE_fecha,$borde,1,"L");
		
		$pdf->Cell(30,6,"Total",$borde,0,"L");
		$pdf->Cell(105,6,"$".number_format($CE_valor,0,",",".")." [".$CE_formaPago."]",$borde,1,"L");
	
			
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(190,5,"Glosa",$borde,1,"L");
		$pdf->SetFont('Arial','',$letra_1);
				
		$pdf->SetWidths(array(190));

		$pdf->SetAligns(array("L"));
		$pdf->Row(array($CE_glosa), 0);
				
		$pdf->Rect(10,109,190,90);		
			
			
		$pdf->SetFont('Arial','',$letra_2);	
		$pdf->SetY(220);
		$y_actual=$pdf->GetY();
		$pdf->SetXY(30,$y_actual);
		$pdf->MultiCell(55,3,"___________________________ Recibi Conforme",$borde*0,"C");
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(30);
		$pdf->MultiCell(55,3,"Rut: $rut_proveedor",$borde*0,"C");
		
		$pdf->SetFont('Arial','',$letra_2);	
		$pdf->SetY(220);
		$y_actual=$pdf->GetY();
		$pdf->SetXY(130,$y_actual);
		$pdf->MultiCell(55,6,"___________________________ Firma Finanzas",$borde*0,"C");
				
				
	
			
	
		$conexion_mysqli->close();
		if(!DEBUG){$pdf->Output();}
	}//fin si hay registro
	else{
		echo "No Hay Registro de este Numero de Comprobante... :(";
	}
}
else
{}
?>