<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("ALUMNO->intranet");
	$O->PERMITIR_ACCESO_USUARIO();
	$O->anti2LoggAlumno();
//--------------FIN CLASS_okalis---------------//

if($_GET)
{
	require("../../../funciones/class_ALUMNO.php");
	require("../../../funciones/funciones_sistema.php");
	
	$id_alumno=base64_decode($_GET["id_alumno"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$yearIngresoCarrera=base64_decode($_GET["yearIngresoCarrera"]);
	$semestre=base64_decode($_GET["s"]);
	$year=base64_decode($_GET["y"]);
	
	$ALUMNO=new ALUMNO($id_alumno);
	
	$alumno=$ALUMNO->getNombre()." ".$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
	$carrera=NOMBRE_CARRERA($id_carrera);
	$ingreso=$yearIngresoCarrera;
	$nivel=$ALUMNO->getNivelAcademicoActual($id_carrera, $yearIngresoCarrera);
	$jornada=$ALUMNO->getJornadaActual();
	$rut=$ALUMNO->getRut();
	$sede=$ALUMNO->getSedeActual();
	
	$logo="../../BAses/Images/logoX.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Toma de Ramos";
	$zoom=75;
	include("../../libreria_publica/fpdf/fpdf.php");
	include("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	$pdf=new FPDF();
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,6,$sede.", ".fecha(),$borde*0,1,'R');
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(190,20,$titulo,$borde*0,1,'C');
	//parrafo 1
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(190,6,"Datos del Alumno ",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(30,6,"Rut",$borde,0,"L");
	$pdf->Cell(160,6,$rut,$borde,1,"L");
	$pdf->Cell(30,6,"Nombre",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($alumno),$borde,1,"L");
	$pdf->Cell(30,6,"Carrera",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($carrera),$borde,1,"L");
	$pdf->Cell(30,6,"Ingreso",$borde,0,"L");
	$pdf->Cell(31,6,$ingreso,$borde,0,"L");
	$pdf->Cell(31,6,"Nivel",$borde,0,"L");
	$pdf->Cell(31,6,$nivel,$borde,0,"L");
	$pdf->Cell(31,6,"Jornada",$borde,0,"L");
	$pdf->Cell(36,6,$jornada,$borde,1,"L");
	
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(190,6,"Lista de Ramos Semestre $semestre - Año $year",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_1);
	
	$pdf->Cell(15,6,"-",$borde,0,"C");
	$pdf->Cell(15,6,"Cod",$borde,0,"C");
	$pdf->Cell(15,6,"Nivel",$borde,0,"C");
	$pdf->Cell(105,6,"Ramo",$borde,0,"L");
	$pdf->Cell(40,6,"Fecha",$borde,1,"L");

	
		$cons="SELECT toma_ramos.* FROM toma_ramos WHERE toma_ramos.id_alumno='$id_alumno' AND toma_ramos.id_carrera='$id_carrera' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year'";
		if(DEBUG){ echo"--->$cons<br>";}
		$sql=mysql_query($cons)or die(mysql_error());
		$num_ramos_tomados=mysql_num_rows($sql);
		
		if($num_ramos_tomados>0)
		{
			$aux=0;
			while($R=mysql_fetch_assoc($sql))
			{
				$aux++;
				
				$R_codigo=$R["cod_asignatura"];
				$R_fecha_generacion=fecha_format($R["fecha_generacion"]);
				$cons_ramo="SELECT nivel, ramo FROM mallas WHERE id_carrera='$id_carrera' AND cod='$R_codigo' LIMIT 1";
				$sql_ramo=mysql_query($cons_ramo)or die(mysql_error());
					$D=mysql_fetch_assoc($sql_ramo);
					$R_ramo=$D["ramo"];
					$R_nivel=$D["nivel"];
				mysql_free_result($sql_ramo);	
				
				if(DEBUG){ echo"$R_codigo - $R_ramo<br>";}
				$pdf->Cell(15,6,$aux,$borde,0,"C");
				$pdf->Cell(15,6,$R_codigo,$borde,0,"C");
				$pdf->Cell(15,6,$R_nivel,$borde,0,"C");
				$pdf->Cell(105,6,utf8_decode($R_ramo),$borde,0,"L");
				$pdf->Cell(40,6,$R_fecha_generacion,$borde,1,"L");

				
			}
			
			$pdf->SetY(230);
			
			
			$y_actual=$pdf->GetY();
			$pdf->MultiCell(50,6,"____________________ Firma Alumno",$borde*0,"C");
			
			$pdf->SetXY(150,$y_actual);
			$pdf->MultiCell(50,6,"____________________ Jefe Carrera",$borde*0,"C");

		}
		else
		{
			if(DEBUG){ echo"Sin Ramos Tomados En el $semestre Semestre - $year<br>";}
			$pdf->Cell(190,6,"Sin Ramos Tomados En el $semestre Semestre - $year",$borde,1,"L");
		}
		
	mysql_free_result($sql);
	mysql_close($conexion);
	if(!DEBUG){$pdf->Output();}
	
}
else
{
	if(DEBUG){ echo"No hay Alumno seleccionado<br>";}
}
?>
