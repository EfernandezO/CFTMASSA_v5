<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_notas_semestrales_X_toma_ramo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ error_reporting(E_ALL); ini_set("display_errors", 1);}
if($_GET)
{
  if(DEBUG){ var_dump($_GET);}
  $sede=base64_decode($_GET['sede']);
  $id_carrera=base64_decode($_GET['id_carrera']); 
  $nivel=base64_decode($_GET['nive']);
  $jornada=base64_decode($_GET['jornada']);
  $grupo=base64_decode($_GET['grupo']);
  $cod_asignatura=base64_decode($_GET['cod_asignatura']);
  $year=base64_decode($_GET['year']);
  $semestre=base64_decode($_GET['semestre']);
  
  if(isset($_GET['tipo_documento']))
  {$tipo_documento=$_GET['tipo_documento'];}
  else{$tipo_documento="";}
  
  if(empty($tipo_documento)){$tipo_documento="normal";}
  
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

	$cons_MAIN="SELECT DISTINCT(toma_ramos.id_alumno) FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE alumno.sede='$sede' AND toma_ramos.id_carrera='$id_carrera' AND alumno.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' ORDER by alumno. apellido_P, alumno.apellido_M";
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"--->$cons_MAIN<br>N. $num_registros<br>";}
	
	
	

	if($num_registros>0)
	{
		$aux_nombre_funcionario="";
		$cons_D="SELECT id_funcionario FROM toma_ramo_docente WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND grupo='$grupo'";
		if(DEBUG){ echo"--->$cons_D<br>";}
		$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
		while($DF=$sqli_D->fetch_assoc())
		{
			$aux_id_funcionario=$DF["id_funcionario"];
			$aux_nombre_funcionario.=" - ".NOMBRE_PERSONAL($aux_id_funcionario);
			if(DEBUG){ echo"id_funcionario: $aux_id_funcionario<br>";}
		}
		///
		$sqli_D->free();
		///////////////////////////////////////////////////////////
		//datos carrera
			$aux_nombre_carrera=NOMBRE_CARRERA($id_carrera);
		//---------------------------------------------------------------------
		//datos asignatura
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
		//-------------------------------------------------------------	
		//recorro alumnos
		$borde=1;
		$logo="../../BAses/Images/logo_cft.jpg";
	$letra_1=10;
	$letra_2=12;
	$autor="ACX";
	$titulo="Notas Semestrales - ".utf8_decode($nombre_asignatura);
	$descripcion=utf8_decode($aux_nombre_carrera)." - Jornada $jornada";
	$descripcion_more="Nivel ".$nivel." - Grupo $grupo Periodo [$semestre - $year]";
	$zoom=75;
		require("../../../librerias/fpdf/fpdf.php");
		$pdf=new FPDF('L','mm','Letter');
		$pdf->AddPage();
		$pdf->SetAuthor($autor);
		$pdf->SetTitle($titulo);
		$pdf->SetDisplayMode($zoom);
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		 $pdf->SetFillColor(216,216,216);
		//titulo
		$pdf->SetFont('Arial','B',$letra_2);
		$pdf->Cell(258,6,$titulo,0,1,'C');	
		$pdf->SetFont('Arial','B',$letra_1);
		$pdf->Cell(258,5,$descripcion,0,1,'C');	
		$pdf->Cell(258,5,$descripcion_more,0,1,'C');	
		$pdf->Cell(258,5,$sede,0,1,'C');	
		$pdf->Ln();
		
					$pdf->SetFont('Arial','B',$letra_1);
					
					$pdf->Cell(258,6,"Docente: ".utf8_decode($aux_nombre_funcionario),$borde,1,'L',true);	
					
					$pdf->Cell(6,6,"N",$borde,0,'C',true);	
					$pdf->Cell(22,6,"Rut",$borde,0,'L',true);	
					$pdf->Cell(60,6,"Nombre",$borde,0,'L',true);	
					$pdf->Cell(60,6,"Apellido Paterno",$borde,0,'L',true);
					$pdf->Cell(60,6,"Apellido Materno",$borde,0,'L',true);
					$pdf->Cell(15,6,"Estado",$borde,0,'C',true);
					$pdf->Cell(20,6,"Periodo",$borde,0,'C',true);
					$pdf->Cell(15,6,"Nota",$borde,1,'C',true);
					$pdf->SetFont('Arial','',$letra_1);
				
			/////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Ve Informe_notas_semestrales_X_toma_ramo id_carrera ".$id_carrera." Sede".$sede."- Jornada".$jornada."Cod_asignatura: $cod_asignatura - Periodo[$semestre-$year]";
		  REGISTRA_EVENTO($evento);	
		$aux=0;	
		$alumnos_sin_nota=0;
		$alumnos_aprobados=0;
		$alumnos_reprobados=0;
		while($IA=$sqli->fetch_row())
		{
			$id_alumno=$IA[0];
			$cons_A="SELECT rut, nombre, apellido_P, apellido_M, ingreso, situacion FROM alumno WHERE id='$id_alumno' LIMIT 1";
			$sqli_a=$conexion_mysqli->query($cons_A);
				$A=$sqli_a->fetch_assoc();
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			$A_ingreso=$A["ingreso"];
			$A_situacion=$A["situacion"];
			$sqli_a->free();
			if(DEBUG){ echo"--->ID alumno: $id_alumno $A_rut $A_nombre $A_apellido_P $A_apellido_M<br>";}
			
			//--------------------------------------------------------------------------------------//
			//verificacion de matricula
			$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera, $semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, true, false, $semestre, false, $year);
			if($mostrar_solo_alumnos_con_matricula)
			{
				if($alumno_con_matricula){$mostrar_alumno=true;}
				else{$mostrar_alumno=false;}
			}
			else
			{
				if($A_situacion=="V")
				{ $mostrar_alumno=true;}
				else
				{ $mostrar_alumno=false;}
			}
			//----------------------------------------------------//
			if($A_situacion=="V"){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			//------------------------------------------------------------------------------------------//
			
			if($mostrar_alumno and $mostrar_alumno_2)
			{
				
				$cons_N="SELECT semestre, ano, nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND cod='$cod_asignatura' LIMIT 1";
				if(DEBUG){ echo"-->$cons_N<br><br>";}
				$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
				$N=$sqli_N->fetch_assoc();
					$N_nota=$N["nota"];
					$N_semestre=$N["semestre"];
					$N_year=$N["ano"];
				$sqli_N->free();	
				
				if($N_nota>=4){ $alumnos_aprobados++;}	
				elseif((empty($N_nota))or($N_nota==0)){ $alumnos_sin_nota++;}
				else{ $alumnos_reprobados++;}
						$aux++;
						$pdf->Cell(6,6,$aux,$borde,0,'C');	
						$pdf->Cell(22,6,$A_rut,$borde,0,'L');	
						$pdf->Cell(60,6,utf8_decode(ucwords(strtolower($A_nombre))),$borde,0,'L');	
						$pdf->Cell(60,6,utf8_decode(ucwords(strtolower($A_apellido_P))),$borde,0,'L');
						$pdf->Cell(60,6,utf8_decode(ucwords(strtolower($A_apellido_M))),$borde,0,'L');
						$pdf->Cell(15,6,$A_situacion,$borde,0,'C');
						$pdf->Cell(20,6,$N_semestre."-".$N_year,$borde,0,'C');
						$pdf->Cell(15,6,$N_nota,$borde,1,'R');
					
				
			}
		}
		$porcentaje_aprobacion=(($alumnos_aprobados*100)/$aux);
		$pdf->MultiCell(258,6,$aux." Alumnos Encontrados - [$alumnos_aprobados] Aprobados, [$alumnos_reprobados] Reprodados, [$alumnos_sin_nota] Sin Nota - Porcentaje aprobacion asignatura  [".number_format($porcentaje_aprobacion,1,",",".")." %]",$borde,'R');
		
	}
	else
	{
		echo"sin Registros<br>";
	}
	
	$pdf->Cell(258,6,"Documento impreso el ".date("d-m-Y H:i:s"),0,1,"R");
	
	if(!DEBUG){ $pdf->Output();}
$sqli->free();
$conexion_mysqli->close();
@mysql_close($conexion);
  
}
else
{header("location: alumno_carrera_asignatura_1.php");}
?>