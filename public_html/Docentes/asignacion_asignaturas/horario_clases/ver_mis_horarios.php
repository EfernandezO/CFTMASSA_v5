<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_GET)
{	
	require("../../../libreria_publica/fpdf/mc_table.php");
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/funciones_sistema.php");
	
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_funcionario"]));
	$semestre=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["semestre"]));
	$year=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year"]));
	
	//------------------------------------------------------//
	$cons_A="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sql_A->fetch_assoc();
		$D_rut=$DA["rut"];
		$D_nombre=$DA["nombre"];
		$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
	$sql_A->free();
	//------------------------------------------------------//
	
	//------------------------------------------------------//
	include("../../../../funciones/VX.php");
	$evento="Revisa Mis Horarios Docente id_funcionario: $id_funcionario formato pdf";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------------------------//
	
	$logo="../../../BAses/Images/logo_cft.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Horarios de Clases";
	$zoom=75;
	
	$pdf=new PDF_MC_Table('L');
	$pdf->AddPage('L','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	$pdf->SetAutoPageBreak(false, 5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(276,6,fecha(),$borde*0,1,'R');
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	
	
	$pdf->SetFillColor(255,125,125);
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(276,14,$titulo,$borde*0,1,'C');
	
	$pdf->SetFont('Arial','',8);
	$pdf->text(150,57,utf8_decode("*Importante: Cualquier diferencia entre este horario y el real, por favor comunicar con Dirección Académica."));
	//parrafo 1
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(135,6,"Datos del Docente ",$borde,1,"L", true);
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Rut",$borde,0,"L");
	$pdf->Cell(105,6,$D_rut,$borde,1,"L");
	$pdf->Cell(30,6,"Nombre",$borde,0,"L");
	$pdf->Cell(105,6,$D_nombre,$borde,1,"L");
	$pdf->Cell(30,6,"Apellido",$borde,0,"L");
	$pdf->Cell(105,6,$D_apellido,$borde,1,"L");
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->SetFillColor(125,255,125);
	$pdf->Cell(276,6,"Lista de Ramos Semestre $semestre - ".utf8_decode("Año")." $year",$borde,1,"L", true);
	$pdf->SetFont('Arial','',$letra_1);
	
	$pdf->Cell(7,6,"-",$borde,0,"C");
	$pdf->Cell(20,6,"Dia",$borde,0,"C");
	$pdf->Cell(34,6,"Horario",$borde,0,"C");
	$pdf->Cell(15,6,"Sede",$borde,0,"C");
	$pdf->Cell(85,6,"Carrera",$borde,0,"L");
	$pdf->Cell(90,6,"Ramo",$borde,0,"L");
	$pdf->Cell(15,6,"J-G",$borde,0,"C");
	
	$pdf->Cell(10,6,"Sala",$borde,1,"C");

	
		//$cons="SELECT toma_ramo_docente.*, horario_docente.hora_inicio, horario_docente.hora_fin, horario_docente.sala, horario_docente.dia_semana FROM toma_ramo_docente INNER JOIN horario_docente ON toma_ramo_docente.id=horario_docente.id_asignacion WHERE toma_ramo_docente.id_funcionario='$id_funcionario' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER BY horario_docente.dia_semana, horario_docente.hora_inicio";
		
		
		$cons="SELECT toma_ramo_docente.*, horario_docente.hora_inicio, horario_docente.hora_fin, horario_docente.sala, horario_docente.dia_semana FROM horario_docente RIGHT JOIN toma_ramo_docente ON horario_docente.id_asignacion=toma_ramo_docente.id WHERE toma_ramo_docente.id_funcionario='$id_funcionario' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER BY horario_docente.dia_semana, horario_docente.hora_inicio";
		
		
		if(DEBUG){ echo"--->$cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_ramos_tomados=$sql->num_rows;
		if(DEBUG){ echo"numero registros: $num_ramos_tomados<br>";}
		if($num_ramos_tomados>0)
		{
			$aux=0;
			$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
			while($R=$sql->fetch_assoc())
			{
				
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
				
				$H_hora_inicio=$R["hora_inicio"];
				$H_hora_fin=$R["hora_fin"];
				$H_sala=$R["sala"];
				$H_dia_semana=$R["dia_semana"];
				
				
				list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($R_id_carrera, $R_codigo);
				$R_carrera=NOMBRE_CARRERA($R_id_carrera);
				
				if(isset($aux,$array_dia[$H_dia_semana])){ $dia_semana_label=$array_dia[$H_dia_semana];}
				else{$dia_semana_label="";}
				
				if(DEBUG){ echo"$R_codigo - $R_ramo $H_hora_inicio - $H_hora_fin sala:$H_sala<br>";}
				if($R_nivel>0)
				{
					$aux++;
					$pdf->SetWidths(array(7,20,17,17,15,85,90,15,10));
					$pdf->SetAligns(array("C", "C","C", "C", "C", "L", "L", "C", "C", "C", "C"));
					$pdf->Row(array($aux, $dia_semana_label, $H_hora_inicio, $H_hora_fin, $R_sede, utf8_decode($R_carrera), utf8_decode($R_ramo) ,$R_jornada."-".$R_grupo, $H_sala));
				}

			}
			
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