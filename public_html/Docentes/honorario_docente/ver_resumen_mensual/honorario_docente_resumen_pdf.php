<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$mes=base64_decode($_GET["mes"]);
	//$year=base64_decode($_GET["year"]);
	$year_generacion=base64_decode($_GET["year_generacion"]);
	
	include("../../../libreria_publica/fpdf/fpdf.php");
	include("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	include("../../../../funciones/VX.php");
	
	$logo="../../../BAses/Images/logoX.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Resumen Honorario Docente";
	$zoom=75;
	
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
	
	$pdf=new FPDF();
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(195,6,"Impresion.: ".fecha(),$borde*0,1,'R');
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(195,20,$titulo,$borde*0,1,'C');
	//parrafo 1
	$pdf->Ln();
	
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(190,6,"$sede Periodo ".$ARRAY_MESES[$mes]." - $year_generacion",$borde,1,"C");
			
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(10,6,"N",$borde,0,"L");
			$pdf->Cell(23,6,"Rut",$borde,0,"L");
			$pdf->Cell(45,6,"Nombre",$borde,0,"L");
			$pdf->Cell(45,6,"Apellido",$borde,0,"L");
			$pdf->Cell(27,6,"Contabilidad",$borde,0,"L");
			$pdf->Cell(20,6,"Estado",$borde,0,"L");
			$pdf->Cell(20,6,"Total",$borde,1,"L");
	
	$cons_H="SELECT honorario_docente.* FROM honorario_docente INNER JOIN personal ON honorario_docente.id_funcionario=personal.id WHERE honorario_docente.mes_generacion='$mes' AND honorario_docente.year_generacion='$year_generacion' AND honorario_docente.sede='$sede' ORDER by personal.apellido_P, personal.apellido_M";
	$sqli_H=$conexion_mysqli->query($cons_H)or die("Honorario Docente ".$conexion_mysqli->error);
	$num_registros=$sqli_H->num_rows;
	
	$SUMA_TOTAL_HONORARIO=0;
	if($num_registros>0)
	{
		//------------------------------------------------------------------//
		$evento="Revisa Resumen Honorario $sede periodo [$mes -$year_generacion]";
		REGISTRA_EVENTO($evento);
		//-----------------------------------------------------------------//
		$contador=0;
		$pdf->SetFont('Arial','',10);
		while($H=$sqli_H->fetch_assoc())
		{
			$contador++;
			$H_id=$H["id_honorario"];
			$H_sede=$H["sede"];
			$H_mes=$H["mes_generacion"];
			$H_year=$H["year_generacion"];
			$H_id_funcionario=$H["id_funcionario"];
			$H_total=$H["total"];
			$H_estado=$H["estado"];
			$H_generado_contabilidad=$H["generado_contabilidad"];
			$H_fecha_generacion=$H["fecha_generacion"];
			$H_cod_user=$H["cod_user"];
			if(empty($H_generado_contabilidad)){ $H_generado_contabilidad="pendiente";}
			
			$SUMA_TOTAL_HONORARIO+=$H_total;
			//------------------------------------------------------//
			$cons_A="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
			$sql_A=$conexion_mysqli->query($cons_A);
			$DA=$sql_A->fetch_assoc();
				$D_rut=$DA["rut"];
				$D_nombre=$DA["nombre"];
				$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
			$sql_A->free();
			//------------------------------------------------------//
			
			$pdf->Cell(10,5,$contador,$borde,0,"C");
			$pdf->Cell(23,5,$D_rut,$borde,0,"L");
			$pdf->Cell(45,5,$D_nombre,$borde,0,"L");
			$pdf->Cell(45,5,$D_apellido,$borde,0,"L");
			$pdf->Cell(27,5,$H_generado_contabilidad,$borde,0,"C");
			$pdf->Cell(20,5,$H_estado,$borde,0,"C");
			
			$pdf->Cell(20,5,"$".number_format($H_total,0,",","."),$borde,1,"R");
		}
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(170,5,"TOTAL",$borde,0,"L");
		$pdf->Cell(20,5,"$".number_format($SUMA_TOTAL_HONORARIO,0,",","."),$borde,1,"R");
		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Cell(190,6,"Honorarios Generados el ".fecha_format($H_fecha_generacion).", por el usuario cod[$H_cod_user]",$borde*0,1,"R");
	}
	else
	{ $pdf->Cell(190,6,"Sin Registros...:(",$borde,1,"C");}
	$sqli_H->free();	

	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
}
else
{}
?>