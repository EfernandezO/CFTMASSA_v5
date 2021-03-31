<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ var_dump($_GET);}
if($_GET)
{
	if(isset($_GET["id_carrera"]))
	{
		$id_carrera=$_GET["id_carrera"];
		if(is_numeric($id_carrera)){ $continuar_1=true;}
		else{ $continuar_1=false;}
	}
	else
	{ $continuar_1=false;}
	
	if(isset($_GET["cod_asignatura"]))
	{
		$cod_asignatura=$_GET["cod_asignatura"];
		if(is_numeric($cod_asignatura)){ $continuar_2=true;}
		else{ $continuar_2=false;}
	}
	else
	{ $continuar_2=false;}
	
	
	
	$sede=$_GET["sede"];
	
	if($continuar_1 and $continuar_2)
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if($continuar)
	{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		require('../../../libreria_publica/fpdf/mc_table.php');
		
		$cons="SELECT * FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' ORDER BY numero_unidad, id_programa";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_reg=$sqli->num_rows;
		$logo="../../../BAses/Images/logo_largo.jpg";
			$borde=0;
			$borde2=0;
			$letra_1=12;
			$letra_2=10;
			$autor="ACX";
			$titulo="PROGRAMA DE ESTUDIO";
			$zoom=75;
			
			$pdf=new PDF_MC_Table();
			
			$pdf->AddPage('P','Letter');
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
		
			$nombre_carrera=NOMBRE_CARRERA($id_carrera);
			list($nombre_asignacion, $nivel_asignacion)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
			
			
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(190,6,"www.cftmass.cl",$borde*0,1,'R');
			$pdf->image($logo,14,10,60,20,'jpg'); //este es el logo
			$pdf->ln();
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(190,20,$titulo,$borde*0,1,'C');
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(50,6,"Carrera:",$borde,0,'L');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(140,6,utf8_decode($nombre_carrera),$borde,1,'L');
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(50,6,"Asignatura:",$borde,0,'L');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(140,6,utf8_decode($nombre_asignacion),$borde,1,'L');
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(50,6,"Nivel:",$borde,0,'L');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(140,6,utf8_decode($nivel_asignacion),$borde,1,'L');
			
			
		if($num_reg>0){
			$aux=0;
			$numero_unidad_old="";
			$suma_horas_programa=0;
			while($PE=$sqli->fetch_assoc()){
				$aux++;
				$PE_numero_unidad=$PE["numero_unidad"];
				$PE_cantidad_horas=$PE["cantidad_horas"];
				$PE_contenido=$PE["contenido"];
				
				if($PE_numero_unidad!=$numero_unidad_old){$pdf->Ln(); $pdf->SetFont('Arial','B',12);
				$pdf->Cell(40,6,"Unidad $PE_numero_unidad [".number_format($PE_cantidad_horas,0)." hrs.]",$borde,1,"L"); $aux=1; $suma_horas_programa+=$PE_cantidad_horas;}
				
				$pdf->SetFont('Arial','',12);
				$pdf->SetAligns(array("C","L"));
				$pdf->SetWidths(array(10,180));
				$pdf->Row(array($aux, utf8_decode($PE_contenido)));
				$numero_unidad_old=$PE_numero_unidad;
			}
			$pdf->Ln();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(100,6,"TOTAL HORAS DEL PROGRAMA",$borde2,0,'L');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(90,6,number_format($suma_horas_programa,0)." hrs.",$borde2,1,'L');
			
		}
		else{
		}
		
		
		@mysql_close($conexion);
		$conexion_mysqli->close();
		if(!DEBUG){$pdf->Output();}}
}
?>