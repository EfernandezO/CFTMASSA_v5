<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

require('../../../libreria_publica/fpdf/fpdf.php');

////////////////////////////////////////////
class FPDFX extends FPDF
{
//Pie de página
	function Footer()
	{
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

///////////////////////////////////////////
include("../../../../funciones/funcion.php");
		$nombre=utf8_decode($_SESSION["CONTRATO_OLD"]["nombre"]);
		$apellido_P=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_P"]);
		$apellido_M=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_M"]);
		$apellido_old=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_old"]);
		
		$apellido_new=$apellido_P." ".$apellido_M;
		
		if($apellido_new!=" ")
		{
			$aux_apellidoX=$apellido_new;
		}
		else
		{
			$aux_apellidoX=$apellido_old;
		}
		
		$alumno="$nombre $aux_apellidoX";
$alumno=ucwords(strtolower($alumno));
$direccion_alumno=$_SESSION["CONTRATO_OLD"]["direccion"];

$rut_alumno=$_SESSION["CONTRATO_OLD"]["rut"];
$sostenedor=$_SESSION["CONTRATO_OLD"]["sostenedor"];

$sede_alumno=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
////-----Arreglo de Variables

$ciudad_dir_alumno=$_SESSION["CONTRATO_OLD"]["ciudad"];
$ciudad_dir_apo=$_SESSION["CONTRATO_OLD"]["ciudad_apo"];
switch($sostenedor)
{
	case"alumno":
		$titular_credito_nombre=$alumno;
		$titular_credito_rut=$rut_alumno;
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
	case"apoderado":
		$titular_credito_nombre=$_SESSION["CONTRATO_OLD"]["nombreC_apo"];
		$titular_credito_rut=$_SESSION["CONTRATO_OLD"]["rut_apo"];
		$titular_credito_direccion=$_SESSION["CONTRATO_OLD"]["direccion_apo"].", ".$ciudad_dir_apo;
		break;
	case"otro":
		$titular_credito_nombre=$_SESSION["FINANZAS"]["sostenedor_nombre"];
		$titular_credito_rut=$_SESSION["FINANZAS"]["sostenedor_rut"];
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
}
///////////////saco de lista de pendientes al contrato academico//////
//////////////////////
$_SESSION["CONTRATO_OLD"]["sede_alumno"];
$fecha_actual_palabra=fecha();
$direccion_sede["Talca"]="en 3 Sur N° 1066, Talca";
$direccion_sede["Linares"]="O'Higgins N°313, Linares";
///////////////////////////////////////////
$hoja_oficio[0]=217;
$hoja_oficio[1]=330;
$ancho_max_celda=195;
$logo="../../../BAses/Images/logo_cft.jpg";
$mostrar_logo=true;
$zoom=75;
$borde=0;
$pdf=new FPDFX('P','mm','letter');
$pdf->AliasNbPages();
//var_export($_SESSION["FINANZAS"]);
$title=utf8_decode("MANDATO DE SUSCRIPCIÓN DE PAGARÉ");

$intro="";

$cuerpo_txt="Con el objeto de facilitar el cobro de las cantidades que resulten adeudadas, el alumno confiere al CFT MASSACHUSETTS , en adelante e indistintamente, el \"Mandatario\", un mandato especial, para que actuando separadamente en nombre y representación del Mandante por medio apoderados autorizados del Mandatario, proceda a reconocer deudas y/o suscribir a la orden del CFT MASSACHUSETTS con facultad expresa de auto contratar y sin ánimo de novar, uno o más pagarés por la cantidad correspondiente a las sumas que el alumno adeudare al CFT MASSACHUSETTS originadas por el pago de arancel, incluido capital, reajustes, intereses normales o penales, en gastos u otros pagos que el CFT MASSACHUSETTS hubiere hecho por cuenta del Alumno, siguiendo las instrucciones dadas en el acápite siguiente y con las facultades que ahí se indican. El CFT MASSACHUSETTS acepta este mandato que se le ha conferido. Las partes acuerdan elevarlo a condición indispensable para la contratación del servicio de que da cuenta el presente mandato, sin perjuicio del envío de los avisos de cobranza que se hará llegar al Alumno. La actuación personal del mandante reconociendo deudas o suscribiendo uno o más pagarés de aquellos a los que se refiere esta cláusula, no implicará la revocación ni el término del mandato a que se refiere el presente instrumento. El alumno autoriza al CFT MASSACHUSETTS a delegar el presente mandato. 
El CFT MASSACHUSETTS queda especialmente autorizado para incorporar en el o los pagarés que se suscriban de conformidad con lo dispuesto en la cláusula anterior, todas las menciones necesarias para su validez como título ejecutivo y hacer autorizar la firma de los apoderados ante Notario u otro ministro de fe competente, liberándolo de la obligación de protesto. En particular, tratándose de las enunciaciones relativas a la cantidad, cláusula de aceleración, fecha de vencimiento, fecha de emisión, tasa de interés, lugar de pago e indivisibilidad, el Alumno imparte al CFT MASSACHUSETTS las siguientes instrucciones para incorporarlas en los pagarés: i) Cantidad: será aquella que resulte de la liquidación que practique el CFT MASSACHUSETTS por el monto adeudado por el Alumno originada en uno o más servicios que éste le hubiere otorgado, incluido capital, reajustes, intereses, intereses normales o penales, en impuesto, en gastos u otros pagos que el Banco hubiere hecho por cuenta del Cliente; ii) fecha de emisión: cualquier fecha desde el día en que el CFT MASSACHUSETTS inicie la prestación del servicio de que da cuenta el presente contrato; iv) fecha de vencimiento: el día siguiente a la fecha de emisión u otro cualquiera posterior; v) tasa de interés: será la tasa máxima convencional para operaciones en moneda nacional no reajustables que rija a la fecha de suscripción del pagaré; vi) lugar de pago: la oficina del CFT MASSACHUSETTS; y vii) pactar la indivisibilidad de la obligación de que da cuenta el o los pagarés.";
///////////////////////////
$pdf->AddPage();
$pdf->SetTitle($title);
$pdf->SetDisplayMode($zoom);
$pdf->SetMargins(10,10,0);
$pdf->SetAuthor('CFT Massachusetts');
//////////////
    
    $pdf->image($logo,14,5,30,24,'jpg'); //este es el logo
	//titulo
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell($ancho_max_celda,20,$title,$borde,1,"C");
	//intro
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell($ancho_max_celda,6,$intro,$borde);
	
	//cuerpo
	
	$cuerpo_txt= utf8_decode($cuerpo_txt);
    $pdf->MultiCell($ancho_max_celda,6,$cuerpo_txt,$borde);

    $ciudad_alumno=$_SESSION["CONTRATO_OLD"]["ciudad"];
	$sede_alumno=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
	
	
	$ultimo_parrafo="";
	$pdf->MultiCell(190,6,utf8_decode($ultimo_parrafo),$borde);

//$pdf->Line(108,0,108,310);
//$pdf->Line(108/2,0,108/2,310);
//$pdf->Line(108*1.5,0,108*1.5,310);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Ln();
$aux_Y=$pdf->GetY();
$pdf->SetXY(33,$aux_Y);
$pdf->MultiCell(50,5,'_______________________ Alumno Rut: '.$rut_alumno,$borde,'C');
$pdf->SetXY(130,$aux_Y);
$pdf->MultiCell(50,5,'_______________________ CFT Massachusetts',$borde,'C');
$pdf->Output();
?>