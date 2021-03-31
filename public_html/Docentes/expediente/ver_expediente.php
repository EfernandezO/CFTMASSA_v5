<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_GET)
{
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	
	require('../../libreria_publica/fpdf/mc_table.php');
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	//------------------------------------------------------//
	$cons_A="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A);
	$DA=$sql_A->fetch_assoc();
		$D_rut=$DA["rut"];
		$D_nombre=$DA["nombre"];
		$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
		$D_fecha_nacimiento=$DA["fecha_nacimiento"];
		$D_email=$DA["email"];
		$D_fono=$DA["fono"];
		$D_direccion=$DA["direccion"];
		$D_ciudad=$DA["ciudad"];
		$D_sexo=$DA["sexo"];
	$sql_A->free();	
	//------------------------------------------------------//
	
	
	
	$logo="../../BAses/Images/logo_largo.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Expediente Funcionario";
	$zoom=75;
	
	$pdf=new PDF_MC_Table();
	
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);

	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(190,6,fecha(),$borde*0,1,'R');
	$pdf->image($logo,14,10,60,20,'jpg'); //este es el logo
	$pdf->ln();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(190,20,$titulo,$borde*0,1,'C');
	//parrafo 1
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->SetFillColor(10,150,200);
	$pdf->Cell(190,6,"Datos Personales",$borde,1,"L",true);
	$pdf->SetFont('Arial','',$letra_1);
	
	$pdf->Cell(30,6,"ID",$borde,0,"L");
	$pdf->Cell(160,6,$id_funcionario,$borde,1,"L");
	$pdf->Cell(30,6,"Rut",$borde,0,"L");
	$pdf->Cell(160,6,$D_rut,$borde,1,"L");

	
	$pdf->Cell(30,6,"Nombre",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($D_nombre),$borde,1,"L");
	$pdf->Cell(30,6,"Apellido",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($D_apellido),$borde,1,"L");
	
	$pdf->Cell(30,6,"Direccion",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($D_direccion),$borde,1,"L");
	
	$pdf->Cell(30,6,"Ciudad",$borde,0,"L");
	$pdf->Cell(160,6,utf8_decode($D_ciudad),$borde,1,"L");
	
	$pdf->Cell(30,6,"Sexo",$borde,0,"L");
	$pdf->Cell(50,6,$D_sexo,$borde,0,"L");
	$pdf->Cell(40,6,"Fecha Nacimiento",$borde,0,"L");
	$pdf->Cell(70,6,$D_fecha_nacimiento,$borde,1,"L");
	
	$pdf->Ln(10);
	//---------------------------------------------------------------//
	//estudios
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->SetFillColor(195,106,122);
	$pdf->Cell(190,6,"Estudios",$borde,1,"L",true);
	$pdf->SetFont('Arial','',$letra_1);
	$cons_E="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_E=$conexion_mysqli->query($cons_E);
	$num_registros=$sql_E->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		$ARRAY_ARCHIVOS=array();
		while($E=$sql_E->fetch_assoc())
		{
			$aux++;
			
			$E_tipo_estudio=$E["tipo_estudio"];
			$E_nombre_institucion=$E["nombre_institucion"];
			$E_year_inicio=$E["year_inicio"];
			$E_year_fin=$E["year_fin"];
			$E_titulo=$E["titulo"];
			$E_descripcion=$E["descripcion"];
			$E_archivo=$E["archivo"];
			$array_E_fecha_titulo=explode("-",$E["fecha_titulo"]);
			if(isset($array_E_fecha_titulo[2])and isset($array_E_fecha_titulo[1]) and isset($array_E_fecha_titulo[0])){
				$E_fecha_titulo=$array_E_fecha_titulo[2]."-".$array_E_fecha_titulo[1]."-".$array_E_fecha_titulo[0];}
			else{ $E_fecha_titulo="00-00-0000";}	
			
			
			
			if((empty($E_archivo))or($E_archivo=="NULL")){}
			else{$ARRAY_ARCHIVOS[]=$E_archivo;}
			
			$pdf->Cell(10,6,$aux,$borde,0,"L");
			$pdf->Cell(15,6,$E_year_inicio,$borde,0,"L");
			$pdf->Cell(15,6,$E_year_fin,$borde,0,"L");
			$pdf->Cell(40,6,$E_tipo_estudio,$borde,0,"L");
			$pdf->Cell(110,6,$E_nombre_institucion,$borde,1,"L");
			
			$pdf->SetAligns(array("C","L"));
			$pdf->SetWidths(array(10,180));
			$pdf->Row(array("","Titulo: ".utf8_decode($E_titulo)." [$E_fecha_titulo]"));
			
			$pdf->SetAligns(array("C","L"));
			$pdf->SetWidths(array(10,180));
			$pdf->Row(array("","Descripcion: ".utf8_decode($E_descripcion)));
			$pdf->Ln();
			
		}
	}
	else
	{$pdf->Cell(190,6,"Sin Estudios Registrados",$borde,1,"C");}
	$sql_E->free();
	
	//-----------------------------------------------------------------------------------------------------//
	//laborales
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->SetFillColor(213,204,106);
	$pdf->Cell(190,6,"Registros Laborales",$borde,1,"L",true);
	$pdf->SetFont('Arial','',$letra_1);
	$cons_L="SELECT * FROM personal_registro_laborales WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_L=$conexion_mysqli->query($cons_L);
	$num_registros=$sql_L->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($L=$sql_L->fetch_assoc())
		{
			$aux++;
			
			$L_cargo=$L["cargo"];
			$L_empresa=$L["empresa"];
			$L_year_inicio=$L["year_inicio"];
			$L_year_fin=$L["year_fin"];
			$L_descripcion=$L["descripcion"];
	
				
				$pdf->SetAligns(array("L","L","L","L","L"));
				$pdf->SetWidths(array(10,15,15,70,80));
				$pdf->Row(array($aux, $L_year_inicio, $L_year_fin, utf8_decode($L_cargo),utf8_decode($L_empresa)));
				
				$pdf->SetAligns(array("C","L"));
				$pdf->SetWidths(array(10,180));
				$pdf->Row(array("","Descripcion: ".utf8_decode($L_descripcion)));
				$pdf->Ln();
		}
	}
	else
	{$pdf->Cell(190,6,"Sin Registros Laborales",$borde,1,"C");}
	
	$sql_L->free();
	$conexion_mysqli->close();
	//--------------------------------------------------------//	
	
	
	if(count($ARRAY_ARCHIVOS)>0){
		foreach($ARRAY_ARCHIVOS as $n => $aux_archivo)
		{
			$path='../../CONTENEDOR_GLOBAL/docente_estudios/';
			$aux_archivo=$path.$aux_archivo;
			$pdf->AddPage('P','Letter');
			$pdf->image($aux_archivo,10,10,190,280,'jpg');
			
		}
	}
	if(!DEBUG){$pdf->Output();}
}
else
{ if(DEBUG){ echo"Sin Datos...<br>";}}
?>