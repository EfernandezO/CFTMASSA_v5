<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->certificado_de_matricula_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["AUX_CERTIFICADO"]))
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../libreria_publica/fpdf/fpdf.php");
	////definicion de parametros
	$logo="../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	if(isset($_GET["ver_logo"]))
	{
		$ver_logox=strip_tags(strtolower($_GET["ver_logo"]));
		if($ver_logox=="si")
		{$ver_logo=true;}
		else
		{$ver_logo=false;}
	}
	else
	{ $ver_logo=false;}
	
	
	$borde=0;
	$fecha=fecha();
	$letra_1=12;
	$autor="ACX";
	$titulo="CERTIFICADO DE MATRICULA";
	$zoom=75;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	
	//inicializacion de pdf
	$pdf=new FPDF('P','mm','letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	///arreglo de txt
	$vigencia=$_SESSION["AUX_CERTIFICADO"]["vigencia_contrato"];
	switch($vigencia)
	{
		case"anual":
			$msj_vigencia="año ".$_SESSION["AUX_CERTIFICADO"]["ano_contrato"];
			break;
		case"semestral":
			if($_SESSION["AUX_CERTIFICADO"]["semestre_contrato"]==1)
			{ $semestre_label="Primer";}
			else
			{ $semestre_label="Segundo";}
			$msj_vigencia=$semestre_label." Semestre del año "." ".$_SESSION["AUX_CERTIFICADO"]["ano_contrato"];
			break;	
	}
	//var_export($_SESSION["AUX_CERTIFICADO"]);
	
	$parrafo_1=str_replace("-",", ",utf8_decode($_SESSION["AUX_CERTIFICADO"]["firma"]))." Centro Formación Técnica Massachusetts, certifica:";
	
	$parrafo_2="Que, el(la) señor (ita) ".utf8_decode($_SESSION["AUX_CERTIFICADO"]["alumno"])." Rut.: ".$_SESSION["AUX_CERTIFICADO"]["rut_alumno"].", se ha matriculado para el $msj_vigencia en la carrera de ".utf8_decode($_SESSION["AUX_CERTIFICADO"]["carrera_alumno"]);
	
	$parrafo_3='Que, su Funcionamiento como "CENTRO DE FORMACION TECNICA" fue aprobado por Decreto Exento N. 29 del 02 de febrero de 1983, inscrito en el Registro correspondiente bajo el N° 77';
	
	$parrafo_4="Que, por ".utf8_decode($_SESSION["AUX_CERTIFICADO"]["decreto_carrera"]).", se aprobaron Planes y Programas de estudio de la mencionada carrera";
	
	$parrafo_5='Se extiende el presente Certificado a solicitud del o (la) interesado (a) para ser presentado en "'.utf8_decode($_SESSION["AUX_CERTIFICADO"]["presentado_a"]).'".';
	
	///escritura de datos
	///logo
	if($ver_logo)
	{$pdf->image($logo,14,5,30,24,'jpg');} //este es el logo
	
	//titulo
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(195,60,$titulo,$borde,1,'C');
	//parrafo 1
	$pdf->SetFont('Arial','',$letra_1);
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y);
	//$pdf->SetX(20);
	$pdf->MultiCell(195,6,"   ".$parrafo_1,$borde);
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
	
	///fecha
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y*2);
	$pdf->Cell(195,6,$_SESSION["AUX_CERTIFICADO"]["sede_alumno"].", $fecha",$borde,"C");
	
	///area firma
	$Y_actual=$pdf->GetY();
	$pdf->SetY($Y_actual + $salto_Y + 50);
	
	$pdf->SetX(100);
	
	$DAUX=explode("-",utf8_decode($_SESSION["AUX_CERTIFICADO"]["firma"]));
	$aux_nombre=$DAUX[0];
	$aux_cargo=$DAUX[1];
	
	$pdf->MultiCell(90,6,"$aux_nombre\n ".$aux_cargo."\n C.F.T Massachusetts",$borde,"C");

	
	//fin
	///registro en tabla registro_certificados
	$id_certificadoX=REGISTRAR_CERTIFICADO($_SESSION["AUX_CERTIFICADO"]["rut_alumno"], $_SESSION["AUX_CERTIFICADO"]["carrera_alumno"], $_SESSION["AUX_CERTIFICADO"]["sede_alumno"]);
	
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$tipo_certificado="certificado de matricula";
	$cons="SELECT COUNT(id) FROM registro_certificados WHERE rut_alumno='$rut_alumno' AND carrera_alumno='$carrera_alumno' AND tipo_certificado='$tipo_certificado'";
	$sql=$conexion_mysqli->query($cons);
	$C=$sql->fetch_row();
	$numero_certificados=$C[0];
	if(empty($numero_certificados))
	{ $numero_certificados=0;}
	$sql->free();
	
	$codigo_certificado=$id_certificadoX."_".$numero_certificados;
	
	$x_actual=$pdf->GetX();
	$y_actual=$pdf->GetY();
	$pdf->Text($x_actual,$y_actual,"Cod.".$codigo_certificado);
	////////////////////////////////////////////////
	$conexion_mysqli->close();
		 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Emision Certificado Matricula a alumno id_alumno: ".$_SESSION["AUX_CERTIFICADO"]["id_alumno"];
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		 
	$pdf->Output();
}
else
{
	header("location : index.php");
}
/////////////////////////////////
function REGISTRAR_CERTIFICADO($rut, $carrera, $sede)
{
	require("../../../funciones/conexion_v2.php");
	$fecha_hora=date("Y-m-d H:i:s");
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$tipo_certificado="certificado de matricula";
	/////////////////////////////////////////////
	$campos="rut_alumno, carrera_alumno, tipo_certificado, fecha_hora, id_user, sede";
	$valores="'$rut', '$carrera', '$tipo_certificado', '$fecha_hora', '$id_user_activo', '$sede'";
	
	$cons_IN="INSERT INTO registro_certificados($campos) VALUES($valores)";
	$conexion_mysqli->query($cons_IN)or die("Registra certificado".$conexion_mysqli->error);
	$id_certificado=$conexion_mysqli->insert_id;
	
	$conexion_mysqli->close();
	return($id_certificado);
	//echo"----------->$cons_IN<br>";
}
?>