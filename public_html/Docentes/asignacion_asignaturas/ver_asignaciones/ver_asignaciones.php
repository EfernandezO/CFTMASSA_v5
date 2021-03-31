<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	require('../../../libreria_publica/fpdf/mc_table.php');
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/funciones_sistema.php");
	//------------------------------------------------------//
	
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_funcionario"]));
	$semestre=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["semestre"]));
	$year=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year"]));
	
	$cons_A="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sql_A->fetch_assoc();
		$D_rut=$DA["rut"];
		$D_nombre=$DA["nombre"];
		$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
	$sql_A->free();
	//------------------------------------------------------//
	include("../../../../funciones/VX.php");
	$evento="Revisa Asignacion Docente id_funcionario: $id_funcionario formato pdf";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------------------------//

	$logo="../../../BAses/Images/logoX.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Asignacion de Ramos";
	$zoom=75;
	
	$pdf=new PDF_MC_Table('L');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(276,6,fecha(),$borde*0,1,'R');
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(276,20,$titulo,$borde*0,1,'C');
	//parrafo 1
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(135,6,"Datos del Docente ",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Rut",$borde,0,"L");
	$pdf->Cell(105,6,$D_rut,$borde,1,"L");
	$pdf->Cell(30,6,"Nombre",$borde,0,"L");
	$pdf->Cell(105,6,$D_nombre,$borde,1,"L");
	$pdf->Cell(30,6,"Apellido",$borde,0,"L");
	$pdf->Cell(105,6,$D_apellido,$borde,1,"L");
	
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(276,6,"Lista de Ramos Semestre $semestre - Año $year",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	
	$pdf->Cell(10,6,"-",$borde,0,"C");
	$pdf->Cell(15,6,"Sede",$borde,0,"C");
	$pdf->Cell(80,6,"Carrera",$borde,0,"L");
	$pdf->Cell(10,6,"Jor",$borde,0,"L");
	$pdf->Cell(10,6,"Grp",$borde,0,"C");
	$pdf->Cell(10,6,"Nivel",$borde,0,"C");
	$pdf->Cell(90,6,"Ramo",$borde,0,"L");
	$pdf->Cell(16,6,"$.Hr",$borde,0,"L");
	$pdf->Cell(16,6,"N.Hrs",$borde,0,"L");
	$pdf->Cell(19,6,"Total",$borde,1,"L");

	
		$cons="SELECT toma_ramo_docente.* FROM toma_ramo_docente WHERE toma_ramo_docente.id_funcionario='$id_funcionario' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year'";
		if(DEBUG){ echo"--->$cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_ramos_tomados=$sql->num_rows;
		
		if($num_ramos_tomados>0)
		{
			$aux=0;
			$SUMA_TOTAL=0;
			$SUMA_HORAS=0;
			while($R=$sql->fetch_assoc())
			{
				$aux++;
				$pdf->SetFont('Arial','',$letra_2);
				$R_numero_horas=$R["numero_horas"];
				$R_codigo=$R["cod_asignatura"];
				$R_fecha_generacion=fecha_format($R["fecha_generacion"]);
				$R_id_carrera=$R["id_carrera"];
				$R_sede=$R["sede"];
				$R_valor_hora=$R["valor_hora"];
				$R_total=$R["total"];
				$R_jornada=$R["jornada"];
				$R_grupo=$R["grupo"];
				
				$SUMA_TOTAL+=$R_total;
				$SUMA_HORAS+=$R_numero_horas;

				list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($R_id_carrera, $R_codigo);
				$R_carrera=NOMBRE_CARRERA($R_id_carrera);	
				
				if(DEBUG){ echo"$R_codigo - $R_ramo<br>";}
				
				$pdf->SetWidths(array(10,15,80,10,10,10,90,16,16,19));
				$pdf->Row(array($aux,$R_sede,utf8_decode($R_carrera),$R_jornada, $R_grupo, $R_nivel, utf8_decode($R_ramo) ,number_format($R_valor_hora,0,",","."), $R_numero_horas, number_format($R_total,0,",",".")));

			}
			$pdf->Cell(25,5,"TOTAL",$borde,0,"L");
			$pdf->Cell(200,5,"",$borde,0,"R");
			$pdf->Cell(16,5,"",$borde,0,"R");
			$pdf->Cell(16,5,$SUMA_HORAS,$borde,0,"R");
			$pdf->Cell(19,5,number_format($SUMA_TOTAL,0,",","."),$borde,1,"R");
			
			$pdf->SetY(175);
			
			
			$y_actual=$pdf->GetY();
			$pdf->SetXY(50,$y_actual);
			$pdf->MultiCell(50,6,"____________________ Firma Docente",$borde*0,"C");
			
			$pdf->SetXY(200,$y_actual);
			$pdf->MultiCell(45,6,"____________________ Jefe Carrera",$borde*0,"C");

		}
		else
		{
			if(DEBUG){ echo"Sin Ramos Tomados En el $semestre Semestre - $year<br>";}
			$pdf->Cell(190,6,"Sin Ramos Tomados En el $semestre Semestre - $year",$borde,1,"L");
		}
		
	$sql->free();
	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
}
else
{}
?>