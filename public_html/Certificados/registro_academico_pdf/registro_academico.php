<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->registro_academico_v1");
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
	
	$A_nombre=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
	$A_apellido=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	////////////////////////
	$cons_B="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql_B=$conexion_mysqli->query($cons_B);
	$DX=$sql_B->fetch_assoc();
		$A_liceo=$DX["liceo"];
		$A_liceo_egreso=$DX["liceo_egreso"];
		$array_A_fecha_nacimiento=explode("-",$DX["fnac"]);
		$A_fecha_nacimiento=$array_A_fecha_nacimiento[2]."-".$array_A_fecha_nacimiento[1]."-".$array_A_fecha_nacimiento[0];
		$A_rut=$DX["rut"];
		$A_direccion=$DX["direccion"];
		$A_ciudad=$DX["ciudad"];
		$A_year_ingreso=$DX["ingreso"];
		$A_year_egreso=$DX["year_egreso"];
	$sql_B->free();
	//////////////////////////
	
	////definicion de parametros
	$logo="../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	$borde=0;
	$letra_1=12;
	$autor="ACX";
	$titulo="REGISTRO ACADÉMICO DEL ALUMNO";
	$zoom=75;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	//inicializacion de pdf
	$pdf=new FPDF('P','mm',$hoja_oficio);
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);


	///logo
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	$pdf->SetFont('Arial','B',16);
	$pdf->Ln();
	$pdf->Cell(195,35,"",$borde,1,"C");
	
	$pdf->Cell(195,6,$titulo,$borde,1,'C');
	
	$pdf->Cell(195,28,"",$borde,1,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(195,10,"CENTRO DE FORMACION TECNICA MASSACHUSETTS",$borde,1,'L');
	$pdf->Cell(30,10,"CARRERA:",$borde,0,'L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(165,10,utf8_decode($carrera_alumno),$borde,1,'L');
	
	$pdf->Cell(195,20,"",$borde,1,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(45,10,"Nombre: ",1,0,'L');
	$pdf->Cell(150,10,utf8_decode($A_nombre)." ".$A_apellido,1,1,'L');
	
	$pdf->Cell(45,10,"Fecha Nacimiento",1,0,'L');
	$pdf->Cell(150,10,$A_fecha_nacimiento,1,1,'L');
	
	$pdf->Cell(45,10,"C.Identidad",1,0,'L');
	$pdf->Cell(150,10,$A_rut,1,1,'L');
	
	$pdf->Cell(45,10,"Direccion Residencia",1,0,'L');
	$pdf->Cell(150,10,utf8_decode($A_direccion).", ".$A_ciudad,1,1,'L');
	
	$pdf->Cell(45,10,"Liceo Egreso",1,0,'L');
	$pdf->Cell(150,10,$A_liceo,1,1,'L');
	
	$pdf->Cell(195,30,"",$borde,1,'C');
	$pdf->Cell(45,10,"FECHA INGRESO",1,0,'L');
	$pdf->Cell(150,10,$A_year_ingreso,1,1,'L');
	$pdf->Cell(45,10,"FECHA EGRESO",1,0,'L');
	$pdf->Cell(150,10,$A_year_egreso,1,1,'L');
	$pdf->Cell(45,10,"FECHA TITULACION",1,0,'L');
	$pdf->Cell(150,10,"",1,1,'L');
	
	$pdf->Cell(195,30,"",$borde,1,'C');
	$pdf->Cell(195,10,"OBSERVACIONES ",1,1,'L');
	$pdf->Cell(195,10,"",1,1,'L');
	$pdf->Cell(195,10,"",1,1,'L');
	
		 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Emision REGISTRO ACADEMCO DEL ALUMNO a alumno ID(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
	$conexion_mysqli->close();
	if(!DEBUG){$pdf->Output();}
}
else
{
	header("location : index.php");
}

?>