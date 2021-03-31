<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");?>
<?php
//var_dump($_POST);
//////////////////////////
define("DEBUG",false);
$sede=$_POST["fsede"];
$carrera=$_POST["carrera"];
$año_ingreso=$_POST["ano_ingreso"];
$jornada=$_POST["jornada"];
$situacion=$_POST["estado"];
$grupo=$_POST["grupo"];
$nivel=$_POST["nivel"];
$cuenta_sin_contrato=0;
////////////////////////////---> Datos actuales de Semestre y año
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual<8)/////porque los contratos semestrales vencen en agosto
{ $semestre_actual=1;}
else
{ $semestre_actual=2;}

/////////////////////////////


if($sede=="")
{$sede="Talca";}
$condicion=" alumno.sede='$sede' AND alumno.carrera='$carrera'";


if($año_ingreso!="Todos")
{
	$condicion.=" AND alumno.ingreso='$año_ingreso'";
}
if($jornada!="T")
{
	$condicion.=" AND alumno.jornada='$jornada'";
}
if($situacion!="A")
{
	$condicion.=" AND alumno.situacion='$situacion'";
}
if($grupo!="Todos")
{
	$condicion.=" AND alumno.grupo='$grupo'";
}
if($nivel!="Todos")
{
	$condicion.="AND alumno.nivel='$nivel'";
}
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
include("../../../funciones/conexion.php");

	$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";
	
if(DEBUG)
{echo"--> $cons <br><br>";}

$sql=mysql_query($cons)or die(mysql_error());
$num_reg=mysql_num_rows($sql);
///////////////////////////////////
include ("../../../librerias/fpdf/fpdf.php");
	$borde=1;
	$letra_1=10;
	$letra_2=12;
	$autor="ACX";
	$titulo="Listado Alumnos";
	$descripcion="$carrera - Año $año_ingreso - Jornada $jornada";
	$descripcion_more="Nivel $nivel - Grupo $grupo";
	$zoom=75;
	$msj_sin_reg="No hay resultados en esta Busqueda";
	
	$pdf=new FPDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	//titulo
	$pdf->SetFont('Arial','B',$letra_2);
	
	$pdf->Cell(195,6,$titulo,0,1,'C');	
	$pdf->Cell(195,6,$descripcion,0,1,'C');	
	$pdf->Cell(195,6,$descripcion_more,0,1,'C');	
	$pdf->Cell(195,6,$sede,0,1,'C');	
	$pdf->Ln();
	////////cabecera
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(195,6,"Alumnos SIN Contratos Generados",1,1,'C');	
	$pdf->Cell(6,6,"Nº",$borde,0,'C');	
	$pdf->Cell(22,6,"Rut",$borde,0,'L');	
	$pdf->Cell(65,6,"Nombre",$borde,0,'L');	
	$pdf->Cell(70,6,"Apellido",$borde,0,'L');
	$pdf->Cell(17,6,"Estado",$borde,0,'C');
	$pdf->Cell(15,6,"Ingreso",$borde,1,'C');
	$pdf->SetFont('Arial','',$letra_1);
if($num_reg>0)
{
	$aux=1;
	 /////Registro ingreso///
		 include("../../../funciones/VX.php");
		 $evento="Ve Informe(alumnosXcurs sin contrato)->".$carrera."-".$año_ingreso."-".$sede."-".$jornada."-".$situacion;
		 REGISTRA_EVENTO($evento);
	///////////////////////
	while($A=mysql_fetch_assoc($sql))
	{
		$id_alumno=$A["id"];
		$rut=$A["rut"];
		$nombre=$A["nombre"];
		$apellido=$A["apellido"];
		$year_ingreso=$A["ingreso"];
		/////------------ACTUALIZACION----------------/////
		$apellido_P=$A["apellido_P"];
		$apellido_M=$A["apellido_M"];
		$apellido_aux=$apellido_P." ".$apellido_M;
		$nivel_alumno=$A["nivel"];
		$grupo_curso=$A["grupo"];
		$jornada=$A["jornada"];
		
		if($apellido_aux==" ")
		{
			$apellido_label=$apellido;
		}
		else
		{
			$apellido_label=$apellido_aux;
		}
		
		//////----------------------------//////
		$situacion=$A["situacion"];
		
		$cons_SC="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion='ok' ORDER by id desc";
		if(DEBUG)
		{ echo"$cons_SC<br>";}
		$sql_SC=mysql_query($cons_SC)or die("contratos ".mysql_error);
		$datos_C=mysql_fetch_assoc($sql_SC);
		
		$id_contrato=$datos_C["id"];
		$semestre_contrato=$datos_C["semestre"];
		$year_contrato=$datos_C["ano"];
		$vigencia=$datos_C["vigencia"];
		
		mysql_free_result($sql_SC);
		////veo si tiene algun contaro generado
		if(empty($id_contrato))
		{$tiene_contratos_generados=false;}
		else
		{$tiene_contratos_generados=true;}
		///si tiene algun contrato generado veo si esta vigente
		if($tiene_contratos_generados)
		{
		switch($vigencia)
			{
				case"semestral":
					if(($semestre_contrato==$semestre_actual)and($year_contrato==$year_actual))
					{ $contrato_vigente=true;}
					else
					{ $contrato_vigente=false;}
					break;
				case"anual":
					if($year_contrato==$year_actual)
					{ $contrato_vigente=true;}
					else
					{ $contrato_vigente=false;}
					break;	
			}
		
		}
		if(DEBUG)
		{ echo"$aux - $id_alumno - $rut - $nombre - $apellido_label - $situacion - $nivel_alumno - $grupo_curso - $jornada - $year_ingreso | $id_contrato - $semestre_contrato - $year_contrato - $vigencia -contrato_generado= ($tiene_contratos_generados)|contrato vigente -> * $contrato_vigente*<br>";
		
			if((!$tiene_contratos_generados)or(!$contrato_vigente))
			{ echo"<b>----> NO tiene CONTRATO</b><br><br>";}
			else
			{ echo"Tiene contrato<br><br>";}
		}	
		else
		{
			if((!$tiene_contratos_generados)or(!$contrato_vigente))
			{
				$pdf->Cell(6,6,$aux,$borde,0,'C');	
				$pdf->Cell(22,6,$rut,$borde,0,'L');	
				$pdf->Cell(65,6,ucwords(strtolower($nombre)),$borde,0,'L');	
				$pdf->Cell(70,6,ucwords(strtolower($apellido_label)),$borde,0,'L');
				$pdf->Cell(17,6,$situacion,$borde,0,'C');
				$pdf->Cell(15,6,$year_ingreso,$borde,1,'C');
				$cuenta_sin_contrato++;
				$aux++;
			}
		}
		
	}
}
else
{
		$pdf->Cell(195,6,$msj_sin_reg,$borde,1,'L');	
}
//fin documento
$pdf->MultiCell(195,6,$cuenta_sin_contrato." Alumnos Sin Contrato Generado ",$borde,'R');
	mysql_close($conexion);
	if(!DEBUG)
	{ $pdf->Output();}
?>