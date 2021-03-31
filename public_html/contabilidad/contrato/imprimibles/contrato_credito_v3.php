<?php
require("../../../SC/seguridad.php");
require("../../../SC/privilegio2.php");
require('../../../../librerias/fpdf/fpdf.php');
define(SIZE_LETRA_titulo,10);
define(SIZE_LETRA,8);
define(SIZE_LETRA_pie,8);
class PDF extends FPDF
{
//Columna actual
var $col=0;
//Ordenada de comienzo de la columna
var $y0;

function Header()
{
    //Cabacera
    global $title;

    $this->SetFont('Arial','B',15);
    $w=$this->GetStringWidth($title)+6;
    $this->SetX((210-$w)/2);
   // $this->SetDrawColor(0,80,180);
   // $this->SetFillColor(230,230,0);
    //$this->SetTextColor(220,50,50);
    //$this->SetLineWidth(1);
    $this->Cell($w,9,$title,0,1,'C');
    $this->Ln(10);
    //Guardar ordenada
    $this->y0=$this->GetY();
}

function Footer()
{
    //Pie de página
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10, utf8_decode('Página ').$this->PageNo()." de 2",0,0,'C');
}

function SetCol($col)
{
    //Establecer la posición de una columna dada
    $this->col=$col;
    $x=10+$col*99;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    //Método que acepta o no el salto automático de página
    if($this->col<1)
    {
        //Ir a la siguiente columna
        $this->SetCol($this->col+1);
        //Establecer la ordenada al principio
        $this->SetY($this->y0);
        //Seguir en esta página
        return false;
    }
    else
    {
        //Volver a la primera columna
        $this->SetCol(0);
        //Salto de página
        return true;
    }
}

function ChapterTitle($num,$label)
{
    //Título
    $this->SetFont('Arial','',SIZE_LETRA_titulo);
   // $this->SetFillColor(200,220,255);
    $this->MultiCell(190,6,utf8_decode($label),0,1,'J',false);
    $this->Ln(4);
    //Guardar ordenada
    $this->y0=$this->GetY();
}

function ChapterBody($fichier)
{
    //Abrir fichero de texto
	if(file_exists($fichier))
	{
   		 $f=fopen($fichier,'r');
   		 $txt=fread($f,filesize($fichier));
  		  fclose($f);
	}
	else
	{
		$txt=$fichier;
	}	  
    //Fuente
    $this->SetFont('Arial','',SIZE_LETRA);
    //Imprimir texto en una columna de 6 cm de ancho
	$txt= utf8_decode($txt);
    $this->MultiCell(98,5,$txt);
   	$ciudad_alumno=$_SESSION["FINANZAS"]["ciudad_alu"];
	$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
	if($sede_alumno!=$ciudad_alumno)
	{
		$ciudades_contrato="en las ciudades de ".$sede_alumno." y/o ".$ciudad_alumno;
	}
	else
	{
		$ciudades_contrato="en la ciudad de ".$sede_alumno;
	}
	$ultimo_parrafo="DECIMO SEPTIMO: Para todos los efectos del presente instrumento las partes fijan domicilio convencional ".$ciudades_contrato." indistintamente. y se someten a la competencia y jurisdiccion de los Tribunales de Justicia, domicilios que tambien serán habiles para las diligencias de protestos en caso de practicarse, siendo facultad de CFT MASSACHUSETTS LTDA. optar por el domicilio en el cual se hará efectiva la acción judicial.";
	$this->MultiCell(98,5,utf8_decode($ultimo_parrafo));
	//Cita en itálica
	
	$this->Ln();
	$this->Ln();
	$this->Ln();
	$aux_X=$this->GetX();
	$aux_Y=$this->GetY();
    $this->SetFont('','I',SIZE_LETRA_pie);
	
  $this->MultiCell(40,5,'_______________________ Alumno',0,'C');
$this->SetFont('','I');
$this->SetXY($aux_X+50, $aux_Y);
    $this->MultiCell(40,5,'_______________________ CFT Massachusetts LTDA',0,'C');
    $this->SetCol(0);
}

function PrintChapter($num,$title,$file)
{
    //Añadir capítulo
	$logo="../../../BAses/Images/logoX.jpg";
    $this->AddPage();
	$this->image($logo,14,5,30,24,'jpg'); //este es el logo
    $this->ChapterTitle($num,$title);
    $this->ChapterBody($file);
}
}

///////////////////////////////////////////
include("../../../../funciones/funcion.php");
$alumno=$_SESSION["FINANZAS"]["nombre_alu"]." ".$_SESSION["FINANZAS"]["apellido_alu"];
$alumno=ucwords(strtolower($alumno));
$direccion_alumno=$_SESSION["FINANZAS"]["direccion_alu"];
$año=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]));
$rut_alumno=$_SESSION["FINANZAS"]["rut_alumno"];
$sostenedor=$_SESSION["FINANZAS"]["sostenedor"];
////-----Arreglo de Variables

$ciudad_dir_alumno=$_SESSION["FINANZAS"]["ciudad_alu"];
$ciudad_dir_apo=$_SESSION["FINANZAS"]["ciudad_apo"];
switch($sostenedor)
{
	case"alumno":
		$titular_credito_nombre=$alumno;
		$titular_credito_rut=$rut_alumno;
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
	case"apoderado":
		$titular_credito_nombre=$_SESSION["FINANZAS"]["nombreC_apo"];
		$titular_credito_rut=$_SESSION["FINANZAS"]["rut_apo"];
		$titular_credito_direccion=$_SESSION["FINANZAS"]["direccion_apo"].", ".$ciudad_dir_apo;
		break;
	case"otro":
		$titular_credito_nombre=$_SESSION["FINANZAS"]["sostenedor_nombre"];
		$titular_credito_rut=$_SESSION["FINANZAS"]["sostenedor_rut"];
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
}
///////////////saco de lista de pendientes al contrato academico//////
	$_SESSION["FINANZAS"]["impresion"]["contrato_credito"]=true;
	/////////////////
/////
$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
$fecha_actual_palabra=fecha();
$direccion_sede["Talca"]="en 3 Sur N° 1066, Talca";
$direccion_sede["Linares"]="O'Higgins N°313, Linares";
///////////////////////////////////////////
$hoja_oficio[0]=217;
$hoja_oficio[1]=330;

$mostrar_logo=true;
$zoom=75;
$pdf=new PDF('P','mm',$hoja_oficio);
//var_export($_SESSION["FINANZAS"]);
$title='CONTRATO DE APERTURA DE CREDITO';
$intro="En ".$sede_alumno." de Chile, con fecha ".$fecha_actual_palabra." entre CENTRO DE FORMACIÓN TÉCNICA MASSACHUSETTS LIMITADA, en adelante CFT MASSACHUSETTS LTDA., o CFT, persona juridica, domiciliada ".$direccion_sede[$sede_alumno].", por una parte, y don(ña) ".$titular_credito_nombre." en adelante el alumno o el titular domiciliado en ".$titular_credito_direccion." con cédula nacional de identidad Nº ".$titular_credito_rut." por la otra. han acordado celebrar el siguiente Contrato de Apertura de Crédito. el cual regira las relaciones entre los alumnos y CFT MASSACHUSSETTS LTDA durante el periodo que se mantenga vigente dicho Contrató, en las condiciones que a continuación se indican.";
///////////////////////////
$pdf->SetTitle($title);
$pdf->SetDisplayMode($zoom);
$pdf->SetMargins(10,10,0);
$pdf->SetAuthor('CFT Massachusetts');

$pdf->PrintChapter(1,$intro,'texto_contrato.txt');
$pdf->Output();
//////////////
?>