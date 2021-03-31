<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio7.php");?>
<?php
if((isset($_SESSION["auxrut"]))and($_SESSION["auxcarrera"])and($_SESSION["auxsede"]))
{
$ACTIVA_ERROR=false;//Muestra o no mensaje segun estado de situacion de Alumno
$firma=$_SESSION["auxfirma"];
$rut=$_SESSION["auxrut"];
$presentado=ucwords(strtolower($_SESSION["auxpresentado"]));
$carrera=$_SESSION["auxcarrera"];
$nivel=$_SESSION["auxnivel"];
$sede=$_SESSION["auxsede"];

$mostrar_mensaje=false;
	
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
	include("../../../librerias/fpdf/fpdf.php");


	//--DATOS DE ALUMNO--//
	$cons_alum="SELECT * FROM alumno where rut = '$rut' and carrera='$carrera' and sede='$sede'";
	$sql_alum=mysql_query($cons_alum)or die(mysql_error());
	$ROW=mysql_fetch_assoc($sql_alum);
    // $enid=$row["id"];
    $apellido=$ROW["apellido"];
    $nombre=$ROW["nombre"];
	$alumno=ucwords(strtolower($nombre." ".$apellido));
	//echo"$cons_alum---> $alumno<br>";
    $rut=$ROW["rut"];
    $direccion=$ROW["direccion"];  
    $ciudad=$ROW["ciudad"];
	$situacion=$ROW["situacion"];
	mysql_free_result($sql_alum);
	if($situacion!="V")
	{
		$mostrar_mensaje=true;
	}
	//-----------DATOS CARRERA------------------//
	$cons_car="SELECT * FROM certificados where carrera = '$carrera' and sede ='$sede'";
	$sql_car=mysql_query($cons_car);
	$DC = mysql_fetch_assoc($sql_car);
    $decreto=$DC["decreto"];
    $sedea=$DC["sede"];
	mysql_free_result($sql_car);
	//-----------------------------------------//
	 
	$fecha_actual=fecha();
	$y_firma=250;
	$borde=0;
	$letra_1=12;
	$letra_2=10;
	$fecha=fecha();
	$autor="ACX";
	$titulo="CERTIFICADO ALUMNO REGULAR";
	$zoom=75;
	$pdf=new FPDF();
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	//*************
	
	//$pdf->image($logo,10,10,30,30,'jpg'); //este es el logo
	//titulo
	$mensaje="*ALUMNO MOROSO*";
	$encabezado=$sede." ".$fecha;
	//--parrafos--//
	$parrafo1=$firma.', Director del Centro de Formación Técnica, Massachusetts, Certifica: Que, el(la) Señor(ita): '.$alumno.' es alumno(a) regular de la carrera de '.$carrera.", ".$nivel;
	
	$parrafo2='Que su funcionamiento como "Centro de Formación Técnica" fue aprobado por Decreto Exento N° 29 de 02 de febrero de 1983, inscrito en el Registro correspondiente bajo el N° 77.';
	
	$parrafo3='Que, por '.$decreto.', se aprobaron Planes y Programas de Estudios de la mencionada Carrera.';
	
	$parrafo4='Se extiende el presente certificado a solicitud del(la) interesado(a) para ser presentado a '.$presentado;
	//---------INICIO ESCRITURA---------//
	$pdf->SetFont('Arial','I',12);
	$pdf->Cell(195,6,$encabezado,$borde,1,'R');

	if(($mostrar_mensaje)and($ACTIVA_ERROR))
	{
		$pdf->SetTextColor(255,0,0);
		$pdf->SetFont('Arial','I',18);
		$pdf->Cell(195,50,$mensaje,$borde,1,'C');
		$pdf->SetTextColor(0,0,0);
	}
	$pdf->SetFont('Arial','U',16);
	$pdf->Cell(195,50,$titulo,$borde*0,1,'C');
	//***************************************************
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(195,6,$parrafo1,$borde,1,'C');
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo2,$borde,1,'C');
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo3,$borde,1,'C');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->MultiCell(195,6,$parrafo4,$borde,1,'C');
	$pdf->Ln();
	
	////-------------FIRMA-----------------------//
	
	$pdf->SetY($y_firma);
	$pdf->Cell(195,6,"____________________________",$borde,1,'R');
	$pdf->Cell(180,6,"Director Academico",$borde,1,'R');
	$pdf->Output();
}
else
{
	header("location: ../menualumnos.php");
}
?>