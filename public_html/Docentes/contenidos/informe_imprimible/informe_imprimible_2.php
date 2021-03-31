<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->informeImprimible");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funciones_sistema.php");
	include("../../../../funciones/funcion.php");
	require('../../../libreria_publica/fpdf/mc_table.php');
if($_GET)
{
	$id_contenidoMain=base64_decode($_GET["id_contenidoMain"]);
	
	$consMAIN="SELECT * FROM contenidosMain WHERE idContenidoMain='$id_contenidoMain'";
	if(DEBUG){ echo"-->$consMAIN<br>";}
	$sqliMain=$conexion_mysqli->query($consMAIN)or die("1111:".$conexion_mysqli->error);
	$DMain=$sqliMain->fetch_assoc();
	//$id_planificacionMain=$DMain["idPlanificacionMain"];
	$numeroSemanas=$DMain["numero_semanas"];
	$id_carrera=$DMain["id_carrera"];
	$cod_asignatura=$DMain["cod_asignatura"];
	$sede=$DMain["sede"];
	$semestre=$DMain["semestre"];
	$year=$DMain["year"];
	$jornada=$DMain["jornada"];
	$grupo_curso=$DMain["grupo"];
	$id_funcionario=$DMain["id_funcionario"];
	
	if(empty($id_planificacionMain)){$id_planificacionMain=0;}
$sqliMain->free();
	
	////----------------------------------------------------------------///
	
	
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	$nombre_funcionario=NOMBRE_PERSONAL($id_funcionario);
	
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
	
	////definicion de parametros
			$logo="../../../BAses/Images/logo_cft.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=15;//separacion entre parrafos
			
			
			$borde=1;
			
			$letra_1=14;
			$autor="ACX";
			$sub_titulo=" $nombre_carrera - $nombre_asignatura Jornada: $jornada Grupo: $grupo_curso ($semestre / $year)";
			$zoom=75;	
			//inicializacion de pdf
			$hoja_oficio[0]=217;
			$hoja_oficio[1]=330;
			//$pdf=new FPDF('L','mm',$hoja_oficio);
			$pdf=new PDF_MC_Table('L','mm',$hoja_oficio);
			
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($sub_titulo);
			$pdf->SetDisplayMode($zoom);
			
			
				//---------INICIO ESCRITURA---------//
		///logo
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		$pdf->Ln(5);
		//titulo
		$pdf->SetFont('Times','',12);
		//$pdf->MultiCell(310,6,$sede.", ".fecha().".-",$borde*0,'R');
		$Y_actual=$pdf->GetY();
	
		$pdf->SetFont('Times','',24);
		$pdf->MultiCell(310,12,"Contenidos de Clases",$borde*0,'C');
		$pdf->Ln();
		
		$pdf->SetFont('Times','',12);
		$pdf->Cell(30,6,"Sede",$borde,0,"L");
		$pdf->Cell(130,6,$sede,$borde,1,"L-");
		$pdf->Cell(30,6,"Carrera",$borde,0,"L");
		$pdf->Cell(130,6,$id_carrera." ".utf8_decode($nombre_carrera),$borde,1,"L");
		
		$pdf->Cell(30,6,"Jornada",$borde,0,"L");
		$pdf->Cell(10,6,$jornada,$borde,0,"L");
		$pdf->Cell(30,6,"Nivel",$borde,0,"L");
		$pdf->Cell(90,6,$nivel_asignatura,$borde,1,"L-");
		
		$pdf->Cell(30,6,"Asignatura",$borde,0,"L");
		$pdf->Cell(130,6,$cod_asignatura." ".utf8_decode($nombre_asignatura),$borde,1,"L");
		$pdf->Cell(30,6,"Docente",$borde,0,"L");
		$pdf->Cell(130,6,utf8_decode($nombre_funcionario),$borde,1,"L");
		
		$pdf->Cell(30,6,"Periodo",$borde,0,"L");
		$pdf->Cell(130,6,"$semestre Semestre - $year",$borde,1,"L");
		$pdf->Cell(30,6,"Hrs Programa",$borde,0,"L");
		$pdf->Cell(130,6,$TOTAL_HORAS_PROGRAMA,$borde,1,"L");
		$pdf->ln();
		//-----------------------------------------------------------------//
		
		$pdf->SetAligns(array("C","C","C","C","C","C","C"));
		$pdf->SetWidths(array(22,33,85,80,30,30,30));
		$pdf->Row(array("N. Semana","Fecha Clase","Horario Inicio clase","Duracion Clase", "Contenido", "Tipo Actividad", "Bibliografia"));
		
		
		///planificaciones
	 	$cons_e="SELECT * FROM contenidosDetalle WHERE idContenidoMain='$id_contenidoMain' ORDER by numero_semana, fecha_clase, horario_inicio_clase";
		$sqli_e=$conexion_mysqli->query($cons_e)or die("Planificaciones ".$conexion_mysqli->error);
		$num_planificaciones=$sqli_e->num_rows;
		if(DEBUG){ echo"$cons_e<br>num planificaciones: $num_planificaciones<br>";}
		$SUMA_HORAS_SEMANALES=0;
		if($num_planificaciones>0)
		{
			$aux=0;
			while($P=$sqli_e->fetch_assoc())
			{
				$id_contenido=$P["id_contenido"];
				
				$numero_semana=$P["numero_semana"];
				$duracion_clase=$P["duracion_clase"];
				
				$SUMA_HORAS_SEMANALES+=$duracion_clase;
				
				$horario_inicio_clase=$P["horario_inicio_clase"];
				$fecha_clase=$P["fecha_clase"];
				$contenido=$P["contenido"];
				$bibliografia=$P["bibliografia"];
				$tipo_actividad=$P["tipo_actividad"];
				//-----------------------------------------------//
				
				$pdf->SetAligns(array("C","C","L","L","C","C","C"));
				$pdf->SetWidths(array(22,33,85,80,30,30,30));
				$pdf->Row(array($numero_semana,$fecha_clase,$horario_inicio_clase,$duracion_clase, utf8_decode($contenido), $tipo_actividad, utf8_decode($bibliografia)));
			}
			//$pdf->Cell(22,6,"Total",$borde,0,"C");
			//$pdf->Cell(33,6,$SUMA_HORAS_SEMANALES,$borde,1,"C");
		}
		else
		{
			$pdf->Cell(15,7,"Sin Contenidos Creadas",1,1,"C");
		}
		$sqli_e->free();
		//FIN EVALUACIONES
		
		$conexion_mysqli->close();
		
		
		$nombre_archivo=utf8_decode($nombre_asignatura);
		$pdf->Output($nombre_archivo, "I");

}
else{ echo"Sin Datos";}
?>