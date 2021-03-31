<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Toma_de_ramos_v1->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


	require("../../../funciones/class_ALUMNO.php");
	require("../../../funciones/funciones_sistema.php");
	
	include("../../libreria_publica/fpdf/fpdf.php");
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	
	$continuar=false;

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$ALUMNO=new ALUMNO($id_alumno);
	$yearIngresoCarrera=base64_decode($_GET["yearIngresoCarrera"]);
	
	$nivelAcademico=$ALUMNO->getNivelAcademicoActual($id_carrera, $yearIngresoCarrera);
	
	
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$ingreso=$_SESSION["SELECTOR_ALUMNO"]["ingreso"];
	$nivel=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$jornada=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$continuar=true;
}
else{
	$id_alumno=base64_decode($_GET["id_alumno"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$yearIngresoCarrera=base64_decode($_GET["yearIngresoCarrera"]);
	$semestre=base64_decode($_GET["s"]);
	$year=base64_decode($_GET["y"]);
	
	$ALUMNO=new ALUMNO($id_alumno);
	
	$alumno=$ALUMNO->getNombre()." ".$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
	$carrera=NOMBRE_CARRERA($id_carrera);
	$ingreso=$yearIngresoCarrera;
	$nivelAcademico=$ALUMNO->getNivelAcademicoActual($id_carrera, $yearIngresoCarrera);
	$jornada=$ALUMNO->getJornadaActual();
	$rut=$ALUMNO->getRut();
	$sede=$ALUMNO->getSedeActual();
	$continuar=true;
}
	
if($continuar){
	$logo="../../BAses/Images/logo_cft.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Toma de Ramos";
	$zoom=75;

	$pdf=new FPDF();
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,6,"Impreso: ".$sede.", ".fecha(),$borde*0,1,'R');
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
	$pdf->Cell(35,6,utf8_decode("Nivel Académico"),$borde,0,"L");
	$pdf->Cell(28,6,$nivelAcademico,$borde,0,"L");
	$pdf->Cell(31,6,"Jornada",$borde,0,"L");
	$pdf->Cell(35,6,$jornada,$borde,1,"L");
	
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(195,6,"Lista de Ramos Semestre $semestre - ".utf8_decode("Año")." $year",$borde,1,"L");
	$pdf->SetFont('Arial','',$letra_2);
	
	$pdf->Cell(8,6,"-",$borde,0,"C");
	$pdf->Cell(10,6,"Cod",$borde,0,"C");
	$pdf->Cell(12,6,"Nivel",$borde,0,"C");
	$pdf->Cell(15,6,"Jornada",$borde,0,"C");
	$pdf->Cell(114,6,"Ramo",$borde,0,"L");
	$pdf->Cell(14,6,"I-I*",$borde,0,"C");
	$pdf->Cell(22,6,"Fecha",$borde,1,"R");

	
		$cons="SELECT toma_ramos.* FROM toma_ramos WHERE toma_ramos.id_alumno='$id_alumno' AND toma_ramos.id_carrera='$id_carrera' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND yearIngresoCarrera='$yearIngresoCarrera'";
		if(DEBUG){ echo"--->$cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_ramos_tomados=$sql->num_rows;
		
		if($num_ramos_tomados>0)
		{
			$aux=0;
			while($R=$sql->fetch_assoc())
			{
				$aux++;
				
				$R_codigo=$R["cod_asignatura"];
				$R_fecha_generacion=fecha_format($R["fecha_generacion"]);
				$R_jornada=$R["jornada"];
				$R_tipoUser=$R["tipoUser"];
				
				switch($R_tipoUser){
					case"alumno":
						$R_tipoUserlabel="A";
						break;
					default:
						$R_tipoUserlabel="C";	
				}
				
				
				//numero veces ramo rendido
				$consNV="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod_asignatura='$R_codigo'";
				$sqliNV=$conexion_mysqli->query($consNV)or die($conexion_mysqli->error);
				$DNV=$sqliNV->fetch_row();
				$numeroVecesRendidoRamo=$DNV[0];
				if(empty($numeroVecesRendidoRamo)){$numeroVecesRendidoRamo=0;}
				$sqliNV->free();
				
				list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($id_carrera, $R_codigo);
				
				if(DEBUG){ echo"$R_codigo - $R_ramo<br>";}
				$pdf->Cell(8,6,$aux,$borde,0,"C");
				$pdf->Cell(10,6,$R_codigo,$borde,0,"C");
				$pdf->Cell(12,6,$R_nivel,$borde,0,"C");
				$pdf->Cell(15,6,$R_jornada,$borde,0,"C");
				$pdf->Cell(114,6,utf8_decode($R_ramo),$borde,0,"L");
				$pdf->Cell(14,6,$numeroVecesRendidoRamo." ".$R_tipoUserlabel,$borde,0,"C");
				$pdf->Cell(22,6,$R_fecha_generacion,$borde,1,"R");

				
			}
			$pdf->Write(5,"* Numero intento y tipo usuario que realiza inscripcion");
			$pdf->SetFont('Arial','',$letra_1);
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
		
	$sql->free();
	
	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
	
}
else
{
	echo"No hay Alumno seleccionado<br>";
}
?>
