<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$mostrarTotal=false;
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	$id_honorario=base64_decode($_GET["id_honorario"]);
	$id_honorario_docente_pago=base64_decode($_GET["id_honorario_docente_pago"]);
	
	require('../../../../libreria_publica/fpdf/mc_table.php');
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	require("../../../../../funciones/funciones_sistema.php");
	//------------------------------------------------------//
	$cons_A="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A);
	$DA=$sql_A->fetch_assoc();
		$D_rut=$DA["rut"];
		$D_nombre=$DA["nombre"];
		$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
	$sql_A->free();
	//------------------------------------------------------//
	
	$logo="../../../../BAses/Images/logo_cft.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Comprobante Pago Docente";
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
	$pdf->Cell(135,6,"Datos del Docente ",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Rut",$borde,0,"L");
	$pdf->Cell(105,6,$D_rut,$borde,1,"L");
	$pdf->Cell(30,6,"Nombre",$borde,0,"L");
	$pdf->Cell(105,6,$D_nombre,$borde,1,"L");
	$pdf->Cell(30,6,"Apellido",$borde,0,"L");
	$pdf->Cell(105,6,$D_apellido,$borde,1,"L");
	
	
	$cons_H="SELECT * FROM honorario_docente WHERE id_honorario='$id_honorario' LIMIT 1";
	$sqli_H=$conexion_mysqli->query($cons_H);
	$H=$sqli_H->fetch_assoc();
		$H_sede=$H["sede"];
		$H_mes=$H["mes_generacion"];
		$H_year=$H["year"];
		$H_year_generacion=$H["year_generacion"];
		$H_total=$H["total"];
		$H_estado=$H["estado"];
		$H_fecha_estado=$H["fecha_estado"];
	$sqli_H->free();	
	//busco pagos previo al honorario
	if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
	$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario' AND id<='$id_honorario_docente_pago'";
	if(DEBUG){echo"-->$consPP<br>";}
	$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
	$PP=$sqliPP->fetch_row();
	$pagosPrevios=$PP[0];
	if(empty($pagosPrevios)){$pagosPrevios=0;}
	$sqliPP->free();
	if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
	
	//deuda actual x cuota honorario
	$deudaActual=($H_total-$pagosPrevios);
	if(DEBUG){echo"Deuda Actual cuota: $deudaActual<br>";}
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
	$pdf->Cell(135,6,"Datos Honorarios",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Sede",$borde,0,"L");
	$pdf->Cell(105,6,$H_sede,$borde,1,"L");
	$pdf->Cell(30,6,"Periodo",$borde,0,"L");
	$pdf->Cell(105,6,$ARRAY_MESES[$H_mes]."-".$H_year_generacion,$borde,1,"L");
	$pdf->Cell(30,6,"Total",$borde,0,"L");
	$pdf->Cell(105,6,"$".number_format($H_total,0,",","."),$borde,1,"L");
	$pdf->Cell(30,6,"Deuda Actual",$borde,0,"L");
	$pdf->Cell(105,6,"$".number_format($deudaActual,0,",","."),$borde,1,"L");
	
	
	
	$cons_P="SELECT * FROM honorario_docente_pagos WHERE id_honorario='$id_honorario' AND id='$id_honorario_docente_pago' ORDER by id desc LIMIT 1";
	$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
	$P=$sqli_P->fetch_assoc();
		$P_valor=$P["valor"];
		$P_forma_pago=$P["forma_pago"];
		$P_fecha_pago=$P["fecha_pago"];
		$P_id_cheque=$P["id_cheque"];
		$P_cod_user=$P["cod_user"];
		$P_archivo=$P["archivo"];
	$sqli_P->free();
	
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
	

	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(135,6,"Datos Pago",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Forma Pago",$borde,0,"L");
	$pdf->Cell(105,6,$informacion_pago,$borde,1,"L");
	$pdf->Cell(30,6,"Fecha Pago",$borde,0,"L");
	$pdf->Cell(105,6,$P_fecha_pago,$borde,1,"L");
	$pdf->Cell(30,6,"Total pagado",$borde,0,"L");
	$pdf->Cell(105,6,"$".number_format($P_valor,0,",","."),$borde,1,"L");
		
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(190,5,"Detalle",$borde,1,"L");
	
	$cons_HD="SELECT * FROM honorario_docente_detalle WHERE id_honorario='$id_honorario'";
	$sqli_HD=$conexion_mysqli->query($cons_HD);
	$num_registros=$sqli_HD->num_rows;
	
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(65,6,"Carrera",$borde,0,"L");
	$pdf->Cell(75,6,"Ramo",$borde,0,"L");
	$pdf->Cell(15,6,"Jor-Grup",$borde,0,"C");
	$pdf->Cell(15,6,"Cuota",$borde,0,"C");
	$pdf->Cell(20,6,"Valor Base",$borde,1,"R");
	
	
	$aux_total=0;
	if($num_registros>0)
	{
		while($HD=$sqli_HD->fetch_assoc())
		{
			$HD_id_carrera=$HD["id_carrera"];
			$HD_cod_asignatura=$HD["cod_asignatura"];
			$HD_jornada=$HD["jornada"];
			$HD_grupo=$HD["grupo"];
			$HD_cuota=$HD["cuota"];
			$HD_total_base=$HD["total_base"];
			$HD_cargo=$HD["cargo"];
			$HD_abono=$HD["abono"];
			$HD_glosa_cargo=$HD["glosa_cargo"];
			$HD_glosa_abono=$HD["glosa_abono"];
			$HD_semestre=$HD["semestre"];
			$HD_year=$HD["year"];
			$HD_total_a_pagar=$HD["total_a_pagar"];
			
			
			
			//si se alcanza la ultima cuota mostrar final
			if($HD_cuota==$AS_numero_cuotas){$mostrarTotal=true;}
			//-----------------------------//
			$aux_total+=$HD_total_a_pagar;
			//----------------------------//
			$cons_AS="SELECT numero_cuotas FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND id_carrera='$HD_id_carrera' AND grupo='$HD_grupo' AND jornada='$HD_jornada' AND cod_asignatura='$HD_cod_asignatura' AND semestre='$HD_semestre' AND year='$HD_year' LIMIT 1";
			$sqli_AS=$conexion_mysqli->query($cons_AS)or die($conexion_mysqli->error);
				$AS=$sqli_AS->fetch_assoc();
					$AS_numero_cuotas=$AS["numero_cuotas"];
				$sqli_AS->free();	
			//---------------------------------------------------------------------------------------//
			list($HD_ramo, $HD_nivel_ramo)=NOMBRE_ASIGNACION($HD_id_carrera, $HD_cod_asignatura);
			$HD_carrera=utf8_decode(NOMBRE_CARRERA($HD_id_carrera));
			
			
			$pdf->SetWidths(array(65,75,15,15,20));
			$pdf->SetAligns(array("L", "L", "C", "C", "R"));
			$pdf->Row(array($HD_carrera, utf8_decode($HD_ramo), $HD_jornada."-".$HD_grupo, $HD_cuota."/".$AS_numero_cuotas,"$ ". number_format($HD_total_a_pagar,0,",",".")));
			
			
			if($HD_cargo>0)
			{
				$pdf->SetX(20);
				$pdf->Cell(25,5,"Cargos",$borde,0,"L");
				$pdf->Cell(135,5,$HD_glosa_cargo,$borde,0,"L");
				$pdf->Cell(20,5,number_format($HD_cargo,0,",","."),$borde,1,"R");
			}
			if($HD_abono>0)
			{
				$pdf->SetX(20);
				$pdf->Cell(25,5,"Abonos",$borde,0,"L");
				$pdf->Cell(135,5,$HD_glosa_abono,$borde,0,"L");
				$pdf->Cell(20,5,number_format($HD_abono,0,",","."),$borde,1,"R");
			}
			
			
		}
		
		$aux_valor_bruto=($aux_total/0.885);
		$aux_valor_impuesto=($aux_valor_bruto-$aux_total);
		
		
		///muestra resumen final del documento
		
		
		if($mostrarTotal){
			$pdf->ln();
			//bruto
			$pdf->Cell(170,5,"Honorarios Brutos (datos para confeccion de Boleta de Honorarios)",$borde,0,"L");
			$pdf->Cell(20,5,"$ ".number_format($aux_valor_bruto,0,",","."),$borde,1,"R");
			//impuesto
			$pdf->Cell(170,5,"11,5% Impuesto",$borde,0,"L");
			$pdf->Cell(20,5,"$ ".number_format($aux_valor_impuesto,0,",","."),$borde,1,"R");
			//liquido
			$pdf->Cell(170,5,"Total a Pagar",$borde,0,"L");
			$pdf->Cell(20,5,"$ ".number_format($aux_total,0,",","."),$borde,1,"R");
		}
		//------------------------------------------------------------------------------------------//
		//liquido
			$pdf->Cell(170,5,"Total a Pagar",$borde,0,"L");
			$pdf->Cell(20,5,"$ ".number_format($aux_total,0,",","."),$borde,1,"R");
		
	}
	else
	{
		$pdf->Cell(195,5,"Sin Registros",$borde,1,"R");
	}
	
	$sqli_HD->free();	
	
		
			$pdf->SetY(260);
			$y_actual=$pdf->GetY();
			$pdf->SetXY(30,$y_actual);
			$pdf->MultiCell(50,6,"___________________________ Firma Docente",$borde*0,"C");
			
			$pdf->SetY(260);
			$y_actual=$pdf->GetY();
			$pdf->SetXY(130,$y_actual);
			$pdf->MultiCell(50,6,"___________________________ Firma Finanzas",$borde*0,"C");
			
			

		

	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
}
else
{}
?>