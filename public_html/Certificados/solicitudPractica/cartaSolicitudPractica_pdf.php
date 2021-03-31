<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->cartaSolicitudPracticaV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(DEBUG){var_dump($_POST);}
if($_POST && isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../libreria_publica/fpdf/fpdf.php");
	////definicion de parametros
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=6;//separacion entre parrafos
	
	
	$borde=0;
	$fecha=fecha();
	$letra_1=12;
	$autor="ACX";
	$zoom=75;
	
	//inicializacion de pdf
	$pdf=new FPDF('P','mm','letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle("carta solicitud practica");
	$pdf->SetDisplayMode($zoom);
	///arreglo de txt
	$arrayFirma=explode("-",mysqli_real_escape_string($conexion_mysqli, $_POST["firma"]));
	
	
	$arrayFirma[0]=utf8_decode($arrayFirma[0]);
	$arrayFirma[0]=str_replace("Ñ","ñ",$arrayFirma[0]);
	$arrayFirma[0]=ucwords(strtolower($arrayFirma[0]));
	
	$destinatario=utf8_decode(mysqli_real_escape_string($conexion_mysqli, $_POST["presentado"]));
	$cargoDestinatario=utf8_decode(mysqli_real_escape_string($conexion_mysqli, $_POST["cargo"]));
	$nombreEmpresa=utf8_decode(mysqli_real_escape_string($conexion_mysqli, $_POST["empresa"]));
	
	$nombreAlumno=ucwords(strtolower($_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"]));
	$rutAlumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$numeroHorasPractica=500;
	$sedeAlumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$idAlumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$idCarreraAlumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$ver_logox=strip_tags(strtolower($_POST["ver_logo"]));
	
	if($ver_logox=="si"){$ver_logo=true;}
	else{$ver_logo=false;}
	if(DEBUG){ if($ver_logo){echo"Ver Logo<br>";}else{ echo"No ver Logo<br>";}}
	
	$logo="../../BAses/Images/logo_cft.jpg";
	
	switch($idCarreraAlumno){
		case"18":
			$numeroHorasPractica=400;
			break;
		case"1":
			$numeroHorasPractica=400;
			break;	
		default:
			$numeroHorasPractica=500;	
	}

	
	///busco nombre titulo		

	$cons_C="SELECT nombre_titulo FROM carrera WHERE id='$idCarreraAlumno' LIMIT 1";
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$CC=$sqli_C->fetch_assoc();
		$nombreTitulo=$CC["nombre_titulo"];
	$sqli_C->free();
	$conexion_mysqli->close();
	
	$parrafo_0="De mi consideración:";
	
	$parrafo_1="El Centro de Formación Técnica Massachusetts, tiene como exigencia en su carga curricular, que los alumnos egresados cumplan con una práctica laboral de $numeroHorasPractica horas.";
	
	$parrafo_2="Dicha Práctica, debe efectuarla en una empresa que tenga un rubro o un departamento afín a la especialidad que estudia el alumno.";
	
	$parrafo_3="Por eso tenemos el agrado de presentar a Don(ña) ".utf8_decode($nombreAlumno).", Run.: ".$rutAlumno." alumno(a) de la carrera ".utf8_decode(NOMBRE_CARRERA($idCarreraAlumno)).", el cual cumple con los requisitos para iniciar su práctica, la cual debe ser supervisada por alguna jefatura de su empresa o por un funcionario designado por Uds.";
	
	$parrafo_4="A su vez, la práctica será supervisada por un profesional de nuestro establecimiento el cual cumple la función de evaluar el desempeño del alumno(a) y ser la contraparte entre el CFT y la empresa que acoge al alumno.";

	$parrafo_5="Nuestros alumnos, están sujetos a un seguro escolar, que libera a la empresa de la responsabilidad de accidentes en la práctica o en el desplazamiento entre la casa y el centro de práctica.";

	$parrafo_6="Agradecemos la oportunidad de recibir a don(ña) ".utf8_decode($nombreAlumno)." en vuestra empresa y de ser favorable nuestra solicitud, ruego a Ud. enviar un documento en donde se indique la fecha de inicio y término de la práctica.";
	
	$parrafo_7="Sin otro particular, saluda atte.,";
	
	


	//logo
	if($ver_logo){$pdf->image($logo,14,5,30,24,'jpg');} 
	//fecha
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(195,6,'REF.: SOLICITUD PRACTICA LABORAL.',$borde,1,"R");
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(195,6,$sedeAlumno.', '.$fecha_actual_palabra,$borde,1,"R");
	

	
	$pdf->Ln(25);
	
	
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(195,6,"Señor(a)",$borde,1,"L");
	$pdf->Cell(195,6,$destinatario,$borde,1,"L");
	$pdf->Cell(195,6,$cargoDestinatario,$borde,1,"L");
	$pdf->Cell(195,6,$nombreEmpresa,$borde,1,"L");
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(195,6,"PRESENTE",$borde,1,"L");
	$pdf->Ln(5);
	
	
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(195,6,$parrafo_0,$borde,1,"L");
	
	//parrafo 1
	$pdf->SetFont('Arial','',$letra_1);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	//$pdf->SetX(20);
	$pdf->MultiCell(195,6,$parrafo_1,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);

	$pdf->MultiCell(195,6,$parrafo_2,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	$pdf->MultiCell(195,6,$parrafo_3,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	$pdf->MultiCell(195,6,$parrafo_4,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	$pdf->MultiCell(195,6,$parrafo_5,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	$pdf->MultiCell(195,6,$parrafo_6,$borde);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	$pdf->MultiCell(195,6,$parrafo_7,$borde);
	
	$pdf->Ln(10);
	
	$pdf->SetX(100);
	$pdf->Cell(97,6,$arrayFirma[0],$borde,1,"C");
	$pdf->SetX(100);
	$pdf->Cell(97,6,$arrayFirma[1],$borde,1,"C");
	
	////////////////////////////////////////////////
	
		 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Emision carta solicitud practica a alumno id_alumno: ".$idAlumno;
		 REGISTRA_EVENTO($evento);
		 
		 $descripcion="Emite Carta Solicitud practica Destinatario: $destinatario cargo: $cargoDestinatario Empresa: $nombreEmpresa";
		 #REGISTRO_EVENTO_ALUMNO($idAlumno, "notificacion",$descripcion);
		 ///////////////////////
	$pdf->Output();
}
else
{
	//header("location: cartaSolicitudPractica.php");
	echo "NO POST<br>";
}
/////////////////////////////////
?>