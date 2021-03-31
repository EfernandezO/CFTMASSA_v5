<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	/////////////////////////
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require("../../libreria_publica/fpdf/fpdf.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	////////////////////////
	
	////definicion de parametros
	$logo="../../BAses/Images/logo_largo.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos

	
	$YEAR=date("Y");
	$YEAR=2020;
	$borde=1;
	$letra_1=12;
	$autor="ACX";
	$titulo="Requerimientos de Ingreso Para Alumnos TENS";
	$zoom=75;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	
	//inicializacion de pdf
	$pdf=new FPDF('P','mm',$hoja_oficio);
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);

$parrafo_1="MINSAL exige que todos los alumnos que estudian carreras relacionadas con el Área de Salud y realizan prácticas en Campos Clínicos, como medida de protección deben estar vacunados.";
$parrafo_2="Vacuna Contra la Hepatitis B Recombinante. Recomvax B está formado por partículas altamente purificadas no infecciosas de antígeno de superficie de la hepatitis B (HBsAg) absorbidos en sales de aluminio como adyuvante y preservadas con timerosal. Es una vacuna de ADN recombinante contra la hepatitis B derivada del HBsAg, producida por una tecnología de ADN recombinante aplicadas sobre células de levadura (Sccharomyces cerevisiae). La vacuna cumple con las exigencias de la O.M.S. para las vacunas recombinantes contra la hepatitis B. En su elaboración no se utilizan sustancias de origen humano.";
$parrafo_3="Cada 1 ml de vacuna contiene: HBsAg purificado 20 ug. Gel hidróxido de aluminio (como aluminio) 0,5 mg. Timerosal 0,01 p/v %. Excipientes c.s.";
$parrafo_4="El régimen de inmunización consiste en 3 dosis de vacuna administradas en el siguiente calendario:";
	///escritura de datos
	///logo
	$pdf->image($logo,10,10,60,15,'jpg'); //este es el logo
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(200,6,$sede_alumno.", ".$fecha_actual_palabra,0,1,"R");
	$pdf->SetFont('Arial','B',16);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(200,30,$titulo,0,1,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(200,6,"1.- UNIFORME",0,1,'L');

	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(200,10,"DAMAS",0,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(45,6,"Uniforme Clinico",$borde,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(155,6,"2 piezas chaqueta/pantalon, con logo y confeccionado en tela Basic(antipilling e inarrugable)",$borde,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(45,6,"Polera",$borde,0,'L');
	$pdf->Cell(155,6,"Pique 60% algodon, mod. semi entallado con logo",$borde,1,'L');
	$pdf->Cell(45,6,"Polar",$borde,0,'L');
	$pdf->Cell(155,6,"unif. mod. Escote Zic - saque Tela antipilling con logo",$borde,1,'L');
	$pdf->Cell(45,6,"Zapatos/zapatillas",$borde,0,'L');
	$pdf->Cell(155,6,"de color blanco sin adornos.",$borde,1,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(75,6,"VALOR REFERENCIAL UNIFORME",$borde,0,'L');
	$pdf->Cell(125,6,"$64.000",$borde,1,'R');
	
	//$pdf->Cell(75,6,"TOCA",$borde,0,'L');
	//$pdf->Cell(125,6,"$3.000",$borde,1,'R');
	$pdf->Ln();
	$pdf->Cell(75,6,"TOTAL",$borde,0,'L');
	$pdf->Cell(125,6,"$67.000",$borde,1,'R');
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(200,10,"VARON",0,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(45,6,"Uniforme Clinico",$borde,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(155,6,"2 piezas chaqueta/pantalon, con logo y confeccionado en tela Basic(antipilling e inarrugable)",$borde,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(45,6,"Polera",$borde,0,'L');
	$pdf->Cell(155,6,"Pique 60% algodon, mod. semi entallado con logo",$borde,1,'L');
	$pdf->Cell(45,6,"Polar",$borde,0,'L');
	$pdf->Cell(155,6,"unif. mod. Escote Zic - saque Tela antipilling con logo",$borde,1,'L');
	$pdf->Cell(45,6,"Zapatos/zapatillas",$borde,0,'L');
	$pdf->Cell(155,6,"de color blanco sin adornos.",$borde,1,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(75,6,"VALOR REFERENCIAL UNIFORME",$borde,0,'L');
	$pdf->Cell(125,6,"$64.000",$borde,1,'R');
	
	$pdf->Cell(75,6,"TOTAL",$borde,0,'L');
	$pdf->Cell(125,6,"$64.000",$borde,1,'R');
	$pdf->Ln();
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(200,6,"*Valores Referenciales año $YEAR*",1,1,'C');
	$pdf->Ln();
	$pdf->MultiCell(200,6,"2.- VACUNA ANTIHEPATITIS B",0,1,'L');
	$pdf->Ln();
	$pdf->MultiCell(200,6,$parrafo_1,0,1,'L');
	$pdf->AddPage();
	
	$pdf->Cell(200,6,"FICHA TECNICA",0,1,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(200,6,"RECOMVAX-B",0,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(200,6,$parrafo_2,0,1,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(200,6,"COMPOSICION",0,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(200,6,$parrafo_3,0,1,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(200,6,"POSOLOGIA Y ADMINISTRACION",0,1,'L');
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(200,6,$parrafo_4,0,1,'L');
	$pdf->Ln();
	$pdf->Cell(45,6,"Primera Dosis",1,0,'L');
	$pdf->Cell(155,6,"En la Fecha Elegida",1,1,'L');
	$pdf->Cell(45,6,"Segunda Dosis",1,0,'L');
	$pdf->Cell(155,6,"Un mes después de la Primera dosis",1,1,'L');
	$pdf->Cell(45,6,"Tercera Dosis",1,0,'L');
	$pdf->Cell(155,6,"Seis meses después de la primera dosis",1,1,'L');
	$pdf->Ln();
	switch($sede_alumno)
	{
		case"Talca":
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(200,6,"CADA DOSIS TIENE UN VALOR DE $7.900.- (VALOR APROXIMADO AÑO $YEAR)",0,1,'C');
			$pdf->Cell(200,6,"Servicio Bio Salud Ltda",0,1,'C');
			$pdf->Cell(200,6,"Vacunatorio Mediclown",0,1,'C');
			$pdf->Cell(200,6,"Clinica UCM Talca",0,1,'C');
			$pdf->Ln();
			break;
	}
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(200,6,"3.- SALUD COMPATIBLE CON DESEMPEÑO DE LA CARRERA, A SABER",0,1,'L');
	$pdf->Ln();
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Si tiene Hipoacusia debe usar audífono y certificado con especialista.",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Alcoholismo tratado y certificado por especialista.",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Adicción a drogas tratado y certificado por especialista.",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Disfemia (tartamudez) o trastorno de la comunicación, evaluación fonoaudiológica.",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Enfermedades crónicas en tratamiento y controladas por Especialista (Ej: Diabetes, Hipertensión Arterial, Epilepsia, otras)",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Salud Mental incompatible con desempeño en la formación de la carrera y futuro profesional (Ej: Esquizofrenia, Manías, Depresión, otras)",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Tatuajes: Debe saber que si estos son visibles al vestir el uniforme, esta sujeto a que el usuario de salud rechace su atención lo que será de su responsabilidad.",0,1,'L');
	$pdf->Ln();
	$pdf->MultiCell(200,6,"4.- HORARIO REFERENCIA",0,1,'L');
	$pdf->Ln();
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Diurno:  Lunes a Viernes de 08:30 a 19:45 hrs. Practicas Curriculares en la semana.",0,1,'L');
	$pdf->Cell(5,6,"-",0,0,'L');
	$pdf->MultiCell(195,6,"Vespertino: Lunes a Viernes de 19:45 a 24:00 hrs + sabados. Practicas Curriculares en la semana y/o Sabados y Domingos.",0,1,'L');
	$pdf->Ln();
	
	$pdf->MultiCell(200,6,"Habiendo leido el texto y teniendo claridad de lo expresado en el yo ".utf8_decode($_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"])." RUN.:".$_SESSION["SELECTOR_ALUMNO"]["rut"].", firmo a conformidad.",0,1,'C');
	
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY(150,290);
	$pdf->Cell(50,6,"______________________",0,1,'C');
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetX(150);
	$pdf->Cell(50,4,utf8_decode($_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"]),0,1,'C');
	$pdf->SetX(150);
	$pdf->Cell(50,4,"ALUMNO",0,1,'C');
	
		 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Emision Ficha requerimientos TENS alumno ID(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////

	mysql_close($conexion);	 
	if(!DEBUG){$pdf->Output();}
}
else
{
	header("location : index.php");
}

?>