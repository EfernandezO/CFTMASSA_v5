<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo_curso"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	//------------------------------------//
		require("../../../../funciones/VX.php");
		$evento="Lista de Alumnos  pdf en Notas Parciales V3 id_carrera:$id_carrera cod_asignatura: $cod_asignatura sede: $sede jornada: $jornada grupo: $grupo_curso [$semestre - $year]";
		REGISTRA_EVENTO($evento);
		//----------------------------------//
		
	if(isset($_GET["id_alumno"]))
	{
		$id_alumno_destacado=base64_decode($_GET["id_alumno"]);
		
		if(is_numeric($id_alumno_destacado))
		{ $destacar_alumno=true;}
		else
		{ $destacar_alumno=false;}
	}
	else
	{ $destacar_alumno=false;}
	////----------------------------------------------------------------///
	
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funciones_sistema.php");
	include("../../../libreria_publica/fpdf/fpdf.php");
	
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	
	////definicion de parametros
	$logo="../../../BAses/Images/logo_cft.jpg";
	$fecha_actual_palabra=date("d/m/Y");
	$fecha_actual=date("d-m-Y");
	$salto_Y=15;//separacion entre parrafos
	
	
	$borde=0;
	
	$letra_1=14;
	$autor="ACX";
	$sub_titulo=utf8_decode($nombre_carrera)." - ".utf8_decode($nombre_asignatura)." Jornada: $jornada Grupo: $grupo_curso ($semestre / $year)";
	$zoom=50;	
	//inicializacion de pdf
	$pdf=new FPDF('P','mm','letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($sub_titulo);
	$pdf->SetDisplayMode($zoom);
			
				//---------INICIO ESCRITURA---------//
		///logo
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(195,6,$sede.", ".date("d/m/Y").".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','',24);
		$pdf->MultiCell(195,12,"Lista de Alumnos",$borde,'C');
		$pdf->SetFont('Times','',16);
		$pdf->MultiCell(195,10,$sub_titulo,$borde,'C');
		$pdf->SetFont('Times','B',14);
		//parrafo 1
		
		
		$pdf->Cell(10,7,"N",1,0,"C");
		$pdf->Cell(30,7,"Rut",1,0,"C");
		$pdf->Cell(40,7,"Apellido P",1,0,"C");
		$pdf->Cell(40,7,"Apellido M",1,0,"C");
		$pdf->Cell(45,7,"Nombre",1,0,"C");
		$pdf->Cell(30,7,"",1,1,"C");
		
		
		
			$cons_A="SELECT toma_ramos.*, alumno.* FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno = alumno.id WHERE toma_ramos.id_carrera='$id_carrera' AND alumno.sede='$sede' AND alumno.jornada='$jornada' AND alumno.grupo='$grupo_curso' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.id_carrera='$id_carrera' ORDER by alumno.apellido_P, alumno.apellido_M";
	$sql_A=$conexion_mysqli->query($cons_A)or die("Alumnos ".$conexion_mysqli->error);
	$num_alumnos=$sql_A->num_rows;
	if(DEBUG){ echo"$cons_A<br>NUM alumnos: $num_alumnos<br>";}
	
	if($num_alumnos>0)
	{
		$cuenta_alumnos=0;
		while($A=$sql_A->fetch_assoc())
		{
			$pdf->SetFont('Times','',12);
			$cuenta_alumnos++;
			
			$A_id=$A["id"];
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			
			
			
					$pdf->Cell(10,7,$cuenta_alumnos,1,0,"C");
					$pdf->Cell(30,7,$A_rut,1,0,"C");
					$pdf->Cell(40,7,utf8_decode($A_apellido_P),1,0,"L");
					$pdf->Cell(40,7,utf8_decode($A_apellido_M),1,0,"L");
					$pdf->Cell(45,7,utf8_decode($A_nombre),1,0,"L");
					$pdf->Cell(30,7,"",1,1,"L");
					
		}
	}
	else
	{ $pdf->Cell(15,7,"Sin Registros...",1,1,"C");}
		
		$conexion_mysqli->close();
		@mysql_close($conexion);
		$pdf->Output();
	
	
}
else{ echo"Sin Datos";}
?>