<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_Asignatura_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
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
  $mostrar_solo_alumnos_con_matricula=true;
  if(isset($_GET['tipo_documento']))
  {$tipo_documento=$_GET['tipo_documento'];}
  else{$tipo_documento="";}
  
  if(empty($tipo_documento)){$tipo_documento="normal";}
  
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
	$cons_MAIN="SELECT DISTINCT(toma_ramos.id_alumno), yearIngresoCarrera FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE alumno.sede='$sede' AND toma_ramos.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' ORDER by alumno. apellido_P, alumno.apellido_M";
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"<br>--->$cons_MAIN<br>N. $num_registros<br>";}
	
	
	

	if($num_registros>0)
	{
		//////////////////////////////////////////////////////////////
		//datos carrera
		$aux_nombre_carrera=NOMBRE_CARRERA($id_carrera);
		//---------------------------------------------------------------------
		//datos asignatura
		list($nombre_asignatura, $nivel_asignacion)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
		//-------------------------------------------------------------	
		//recorro alumnos
		$borde=1;
	$letra_1=10;
	$letra_2=12;
	$autor="ACX";
	$titulo="Listado Alumnos Asignatura:".utf8_decode($nombre_asignatura);
	$descripcion=utf8_decode($aux_nombre_carrera)." - Jornada $jornada";
	$descripcion_more="Nivel ".$nivel." - Grupo $grupo";
	$zoom=50;
		require("../../libreria_publica/fpdf/fpdf.php");
		$pdf=new FPDF('P','mm','Letter');
		$pdf->AddPage();
		$pdf->SetAuthor($autor);
		$pdf->SetTitle($titulo);
		$pdf->SetDisplayMode($zoom);
		//titulo
		$pdf->SetFont('Arial','B',$letra_2);
		$pdf->Cell(195,6,$titulo,0,1,'C');	
		$pdf->Cell(195,6,$descripcion,0,1,'C');	
		$pdf->Cell(195,6,$descripcion_more,0,1,'C');	
		$pdf->Cell(195,6,"$semestre Semestre - $year",0,1,'C');	
		$pdf->Cell(195,6,$sede,0,1,'C');	
		$pdf->Ln();
		switch($tipo_documento)
			{
				case"normal":
					////////cabecera
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(6,6,"Nº",$borde,0,'C');	
					$pdf->Cell(22,6,"Rut",$borde,0,'L');	
					$pdf->Cell(52,6,"Nombre",$borde,0,'L');	
					$pdf->Cell(44,6,"Apellido Paterno",$borde,0,'L');
					$pdf->Cell(44,6,"Apellido Materno",$borde,0,'L');
					$pdf->Cell(12,6,"Estado",$borde,0,'C');
					$pdf->Cell(15,6,"Ingreso",$borde,1,'C');
					$pdf->SetFont('Arial','',$letra_1);
					break;
				case"asistencia":
					////////cabecera
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(6,6,"Nº",$borde,0,'C');	
					$pdf->Cell(22,6,"Rut",$borde,0,'L');	
					$pdf->Cell(64,6,"Nombre",$borde,0,'L');	
					$pdf->Cell(63,6,"Apellidos",$borde,0,'L');
					$pdf->Cell(40,6,"Firma",$borde,1,'C');
					$pdf->SetFont('Arial','',$letra_1);
					break;	
				default:
						////////cabecera
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(6,6,"Nº",$borde,0,'C');	
					$pdf->Cell(22,6,"Rut",$borde,0,'L');	
					$pdf->Cell(52,6,"Nombre",$borde,0,'L');	
					$pdf->Cell(44,6,"Apellido Paterno",$borde,0,'L');
					$pdf->Cell(44,6,"Apellido Materno",$borde,0,'L');
					$pdf->Cell(12,6,"Estado",$borde,0,'C');
					$pdf->Cell(15,6,"Ingreso",$borde,1,'C');
					$pdf->SetFont('Arial','',$letra_1);
					break;	
			}	
			/////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Ve Informe(alumnosXcurso_asignatura)->id_carrera ".$id_carrera." Sede".$sede."- Jornada".$jornada."COd_asignatura: $cod_asignatura - Periodo[$semestre-$year]";
		  REGISTRA_EVENTO($evento);	
		$aux=0;	
		while($IA=$sqli->fetch_row())
		{
			$id_alumno=$IA[0];
			$yearIngresoCarrera=$IA[1];
			
			$cons_A="SELECT rut, nombre, apellido_P, apellido_M FROM alumno WHERE id='$id_alumno' LIMIT 1";
			$sqli_a=$conexion_mysqli->query($cons_A);
				$A=$sqli_a->fetch_assoc();
				$A_rut=$A["rut"];
				$A_nombre=$A["nombre"];
				$A_apellido_P=$A["apellido_P"];
				$A_apellido_M=$A["apellido_M"];
			$sqli_a->free();
			if(DEBUG){ echo"--->ID alumno: $id_alumno $A_rut $A_nombre $A_apellido_P $A_apellido_M<br>";}
			
			//--------------------------------------------------------------------------------------//
			//verificacion de matricula
			$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, true, false, $semestre, false, $year);
			
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
			if(($A_situacion=="V")or($A_situacion=="EG")){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			//------------------------------------------------------------------------------------------//
			
			if($mostrar_alumno and $mostrar_alumno_2)
			{
				switch($tipo_documento)
				{
					case"normal":
						$aux++;
						$pdf->Cell(6,6,$aux,$borde,0,'C');	
						$pdf->Cell(22,6,$A_rut,$borde,0,'L');	
						$pdf->Cell(52,6,utf8_decode(ucwords(strtolower($A_nombre))),$borde,0,'L');	
						$pdf->Cell(44,6,utf8_decode(ucwords(strtolower($A_apellido_P))),$borde,0,'L');
						$pdf->Cell(44,6,utf8_decode(ucwords(strtolower($A_apellido_M))),$borde,0,'L');
						$pdf->Cell(12,6,$A_situacion,$borde,0,'C');
						$pdf->Cell(15,6,$yearIngresoCarrera,$borde,1,'L');
						break;
					case"asistencia":
						$aux++;
						$pdf->Cell(6,6,$aux,$borde,0,'C');	
						$pdf->Cell(22,6,$A_rut,$borde,0,'L');	
						$pdf->Cell(64,6,utf8_decode(ucwords(strtolower($A_nombre))),$borde,0,'L');	
						$pdf->Cell(63,6,utf8_decode(ucwords(strtolower($A_apellido_P." ".$A_apellido_M))),$borde,0,'L');
						$pdf->Cell(40,6,"",$borde,1,'C');
						break;	
				}
				
			}
		}
		$pdf->MultiCell(195,6,$aux." Alumnos Encontrados al ".date("d/m/Y")." A las ".date("H:i:s"),$borde,'R');
		if(!DEBUG){ $pdf->Output();}
	}
	else
	{
		echo"sin Registros<br>";
	}
	
	
$sqli->free();
$conexion_mysqli->close();
  
}
else
{header("location: alumno_carrera_asignatura_1.php");}
?>