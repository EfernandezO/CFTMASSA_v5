<?php
include("../../../SC/seguridad.php");
include("../../../SC/privilegio2.php");
////////////////
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	include ("../../../../librerias/fpdf/fpdf.php");
$$mostrar_logo=false;	
if($_GET)
{
	$logo=$_GET["logo"];
	switch ($logo)
	{
		case "si":
			$mostrar_logo=true;
			break;
		case "no":
			$mostrar_logo=false;
			break;
	}
}

	$mostrar_logo=true;
	$logo="../../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$y_firmas=250;
	$borde=0;
	$borde_p=0;
	$letra_1=10;
	$letra_2=10;
	$letra_pie=8;
	$fecha=fecha();
	$autor="ACX";
	$titulo="CONTRATO DE PRESTACION DE SERVICIOS";
	$zoom=75;
	$largo_folio=5;///agrega "0" antes del folio hasta dejarlo del largo
	
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	$pdf=new FPDF('P','mm',$hoja_oficio);
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	
	//arreglo variables
	//var_export($_SESSION["CONTRATO_OLD"]);
		$lugar_contrato=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
		$sede_alumno=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
		if($sede_alumno=="Talca")
		{
			$direccion_cft="3 Sur Nª 1068";
		}
		else
		{
			$direccion_cft="O'Higgins Nª 313";
		}	
		//nombre alumno
		$alumno=$_SESSION["CONTRATO_OLD"]["nombre"]." ".$_SESSION["CONTRATO_OLD"]["apellido_P"]." ".$_SESSION["CONTRATO_OLD"]["apellido_M"];
		$alumno=ucwords(strtolower($alumno));
		$direccion_alumno=$_SESSION["CONTRATO_OLD"]["direccion"];
		$valor_arancel=$_SESSION["CONTRATO_OLD"]["arancel"];
		$valor_matricula=$_SESSION["CONTRATO_OLD"]["matricula_a_pagar"];
		$opcion_matricula=$_SESSION["CONTRATO_OLD"]["opcion_pago_mat"];
		$semestre=$_SESSION["CONTRATO_OLD"]["semestre"];
		$año=end(explode("-",$_SESSION["CONTRATO_OLD"]["fecha_inicio"]));
		$year_estudio=$_SESSION["CONTRATO_OLD"]["year_estudio"];
		
		//$estacion_retiro=$_SESSION["FINANZAS"]["estacion_retiro"];
		
		$carrera=$_SESSION["CONTRATO_OLD"]["carrera"];
		$nivel=$_SESSION["CONTRATO_OLD"]["nivel"];
		$jornada=$_SESSION["CONTRATO_OLD"]["jornada"];
		$fecha_fin_contrato=$_SESSION["CONTRATO_OLD"]["fecha_fin"];
		$rut_alumno=$_SESSION["CONTRATO_OLD"]["rut"];
		
		$vigencia=$_SESSION["CONTRATO_OLD"]["vigencia"];
		$sostenedor=$_SESSION["CONTRATO_OLD"]["sostenedor"];
		$ciudad_alumno=$_SESSION["CONTRATO_OLD"]["ciudad"];
		if($sostenedor=="otro")
		{
			$nombre_sostenedor=$_SESSION["FINANZAS"]["sostenedor_nombre"];
		}
		$sede_alumno=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
		$id_alumnoX=$_SESSION["CONTRATO_OLD"]["id_alumno"];
		//////////////////////////////////////////////////////////////////////////
		$folio_contrato=FOLIO($id_alumnoX, $sede_alumno, $semestre, $year_estudio);
		$actual_largo_folio=strlen($folio_contrato);
		$diferencia=($largo_folio-$actual_largo_folio);
		if($actual_largo_folio<$largo_folio)
		{
			for($zx=0;$zx<$diferencia;$zx++)
			{
				$prex.="0";
			}
		}
		$folio_contrato=$prex.$folio_contrato;
		//////////////////////////////////////////////////////////////////////////
		
		
		if($jornada=="D")
		{
			$jornada_label="Diurna";
		}
		else
		{
			$jornada_label="Vespertina";
		}
		if($opcion_matricula=="NO")
		{
			$valor_matricula=0;
		}
		
		
		//////////////////////
		if($_SESSION["CONTRATO_OLD"]["cantidad_beca"]>0)
		{
			$descuento=$_SESSION["CONTRATO_OLD"]["cantidad_beca"];
			$valor_arancel=round($valor_arancel-$descuento);
		}
		
		if($porcentaje_beca>0)
		{
			$descuento=($valor_arancel*$porcentaje_beca)/100;
			$valor_arancel=round($valor_arancel-$descuento);
		}
		//////////////////////
		$semestre_año=$semestre." Semestre del año ".$año;
	//variable x parrafo parrafo
	$parrafo_1="En ".$lugar_contrato." entre Juan Carlos Figueroa U., RUT: 6.015.058-3, Representante Legal del Centro de Formacion Tecnica Massachusetts. RUT.:89.921.100-6 con domicilio ".$direccion_cft." en de esta ciudad y Don(ña) ".$alumno." con domicilio en ".$direccion_alumno.", ".$ciudad_alumno." en adelante el Alumno, conviene el siguiente contrato de Prestacion de Servicios Educacionales:";
	//*************
	$parrafo_2="El C.F.T Massachusetts, se obliga a impartir la carrera que el alumno opta de acuerdo a los Planes aprobados. Ademas ofrecera todo el uso del equipamiento Academico, según necesidad de la carrera.";
	
	$parrafo_3="Don(ña) ".$alumno." se obliga a asistir a clases regularmente y a cumplir las norrmas establecidas en el Reglamento Académico.";
	
	$parrafo_4="Don(ña) ".$alumno." pagará un arancel de $".number_format($valor_arancel,0,",",".")." por el ".$semestre_año." y una matricula anual de $ ".number_format($valor_matricula,0,",",".")." cuyos vencimientos serán fijados libremente por el alumno, respetando los plazos generales fijados por el C.F.T. Massachusetts Ltda.";
	
	$parrafo_5a="a)	El alumno para hacer efectivo su retiro deberá informarlo por escrito en los departamentos de secretaría académica y finanzas.";
	
	$parrafo_5b="b)	Administrativamente, el alumno tendrá la obligación de cancelar la totalidad del valor del semestre en curso a menos que:";
		$parrafo_5b1="1. Se comunique antes del inicio de clases.";
		$parrafo_5b2="3. Sea en caso  de fuerza mayor como: Traslado de ciudad, enfermedad o fallecimiento del sostenedor, debidamente justificados con los documentos pertinentes.";
		$parrafo_5c="c)En cualquier caso no estipulado en los puntos anteriores, el alumno podra exponer su problema, por escrito al Consejo Superior de la Institucion, el que decidira la situacion en particular.";
		
		$parrafo_6="El no pago del documento en la fecha dara derecho a la institucion a seguir las acciones legales que correspondan, y a suspender las prestaciones motivo de este contrato, siendo de responsabilidad del alumno los daños academicos que por este motivo se ocasione. Ademas, el alumno autoriza a enviar los antecedentes de su deuda vencida a los servicios de informacion comercial para conocimiento publico.";
		
		$parrafo_7="El Alumno queda matriculado en la Carrera: ".$carrera." Nivel: ".$nivel;
		$parrafo_8="Es decision del C.F.T cambios de jornada de un curso cuando lo estime conveniente (solo para jornada) Jornada: ". $jornada_label;
		
		$parrafo_9="El Presente contrato durará hasta el ".$fecha_fin_contrato;
		$parrafo_10="Los valores del proceso de titulacion no estan contemplados en este contrato.";
		if($mostrar_logo)
		{
			$pdf->image($logo,14,1,30,24,'jpg'); //este es el logo
		}	
	//titulo
	$pdf->SetY(0);
	$pdf->SetFont('Arial','U',14);
	$pdf->Cell(195,6,$titulo,$borde,1,'C');
	
	//***************************************************
	//
	$pdf->SetFont('Arial','',$letra_1);
	//folio
	$prefijo_folio=substr($sede_alumno,0,1);
	$pdf->Cell(195,6+13,"FOLIO Nº.: ".$prefijo_folio." ".$folio_contrato,$borde,1,'R');
	///primer parrafo
	$pdf->MultiCell(195,6,$parrafo_1,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"PRIMERO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_2,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"SEGUNDO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_3,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"TERCERO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_4,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"CUARTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(23,6,"del Retiro",$borde,1,'L');	
	
	$pdf->MultiCell(195,6,$parrafo_5a,$borde,1,'L');
	
	$pdf->MultiCell(195,6,$parrafo_5b,$borde,1,'L');
	$pdf->Cell(10,6,"",$borde,0,'L');
	$pdf->MultiCell(185,6,$parrafo_5b1,$borde,1,'L');
	$pdf->Cell(10,6,"",$borde,0,'L');
	$pdf->MultiCell(185,6,$parrafo_5b2,$borde,1,'L');
	$pdf->Cell(10,6,"",$borde,0,'L');
	$pdf->MultiCell(185,6,$parrafo_5b3,$borde,1,'L');
	
	$pdf->MultiCell(195,6,$parrafo_5c,$borde,1,'L');
	$pdf->MultiCell(195,6,$parrafo_5d,$borde,1,'L');
	
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"QUINTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_6,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"SEXTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_7,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"SEPTIMO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_8,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"OCTAVO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_9,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,6,"NOVENO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,6,$parrafo_10,$borde,1,'L');
	
	$pdf->Ln();
	$pdf->Cell(98,4,"_________________________",$borde,0,'C');	
	$pdf->Cell(98,4,"_________________________",$borde,1,'C');	
	
	$pdf->SetFont('Arial','',$letra_pie);
	$pdf->Cell(98,3,"Fima Alumno o Apoderado",$borde,0,'C');	
	$pdf->Cell(98,3,"",$borde,1,'C');
		
	$pdf->Cell(98,3,"Rut.:".$rut_alumno,$borde,0,'C');	
	$pdf->Cell(98,3,"",$borde,1,'C');	
	
		//cuotas/
	$linea_credito_cantidad=$_SESSION["CONTRATO_OLD"]["linea_credito_paga"];
	if($linea_credito_cantidad>0)
	{	
		$pdf->Ln();
		$pdf->Cell(195,6,"*Vencimiento de Mensualidades - $vigencia*",$borde_p, 0,"C");
		//cabecera
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_1);
		$x_desplazamiento=35;
		$pdf->SetX($x_desplazamiento);
		$pdf->Cell(47,6,"Cuotas",$borde_p, 0,"C");
		$pdf->Cell(47,6,"Fecha Vencimiento",$borde_p, 0,"C");
		$pdf->Cell(47,6,"Valor",$borde_p, 1,"C");
		$pdf->SetFont('Arial','',$letra_1);
		//datos
		
		$cantidad_cuotas=$_SESSION["CONTRATO_OLD"]["numero_cuotas"];
		$aux=0;
		$linea_credito_cantidad=$_SESSION["CONTRATO_OLD"]["linea_credito_paga"];
		$valor_cuota=round($linea_credito_cantidad/$cantidad_cuotas);
		$valor_cuota_label=number_format($valor_cuota,0,",",".");
		
		/////////fecha de cuota///////////
		$id_alumno=$_SESSION["CONTRATO_OLD"]["id_alumno"];
		$sede_alumno=$_SESSION["CONTRATO_OLD"]["sede_alumno"];
		$semestre=$_SESSION["CONTRATO_OLD"]["semestre"];
		$year_estudio=$_SESSION["CONTRATO_OLD"]["year_estudio"];
		
		$cons_cuota="SELECT MIN(fechavenc) FROM letras WHERE idalumn='$id_alumno' and sede='$sede_alumno' AND semestre='$semestre' AND ano='$year_estudio'";
		//echo"$cons_cuota<br>";
		$sql_cuota=mysql_query($cons_cuota)or die(mysql_error());
		$DLX=mysql_fetch_row($sql_cuota);
		$fecha_inicio_cuota=$DLX[0];
		
		$array_fecha=explode("-",$fecha_inicio_cuota);
		
		////////////////////////////
		//echo"----> $fecha_inicio_cuota<br>";
		$dia_vence=$array_fecha[2];
		$mes_ini=$array_fecha[1];
		$year=$array_fecha[0];
		
	//	var_export($_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]);
		$mes=$mes_ini;
		for($c=1;$c<=$cantidad_cuotas;$c++)
		{
			if(($mes<10)and(strlen($mes)<2))
			{
				$mes_label="0".$mes;
			}
			else
			{$mes_label=$mes;}
			if($dia_vence<10)
			{
				$dia_vence_label="0".$dia_vence;
			}
			else
			{$dia_vence_label=$dia_vence;}
			if(($mes==2)and($dia_vence>28))
			{
				$aux_vencimiento="28-02-$year";
			}
			else
			{
				$aux_vencimiento="$dia_vence_label-$mes_label-$year";
			}	
			$label_cuota=($aux+1)."/".$cantidad_cuotas;
			$pdf->SetX($x_desplazamiento);
			$pdf->Cell(47,6,$label_cuota,$borde_p, 0,"C");
			$pdf->Cell(47,6,$aux_vencimiento,$borde_p, 0,"C");
			$pdf->Cell(47,6,"$".$valor_cuota_label,$borde_p, 1,"C");
			$aux++;
			
			/////
			$mes++;
			if($mes>12)
			{
				$mes=1;
				$year++;
			}
		}
	}	
	
	$pdf->Ln();
	//info de boleta
	//var_export($array_boleta);
	//texto para final matricula
	$txt_matricula="Matricula";
	switch($opcion_matricula)
	{
		case"L_CREDITO":
			//var_export($_SESSION["FINANZAS"]);
			$txt_matricula.=" Fecha Vencimiento Mensualidad (Matricula).: ".fecha_format($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"])." Valor.: $ ".number_format($valor_matricula,0,",",".");
			break;
			
		case"CONTADO":
			$txt_matricula.=" (Contado) ID Boleta.: ".$_SESSION["CONTRATO_OLD"]["id_boleta"]."(".$_SESSION["CONTRATO_OLD"]["folio_boleta"].") Fecha.: ".$fecha_actual." Valor.: $ ".number_format($valor_matricula,0,",",".");
			break;
		case"CHEQUE":
			$txt_matricula.=" (Cheque) ID Boleta.:".$_SESSION["CONTRATO_OLD"]["id_boleta"]."(".$_SESSION["CONTRATO_OLD"]["folio_boleta"].") Fecha.: ".$fecha_actual." Valor.: $ ".number_format($valor_matricula,0,",",".");	
			break;	
		case"NO":
			$txt_matricula.=" No Paga Matricula";
			break;	
	}
	
	$pdf->MultiCell(195,6,$txt_matricula,$borde_p, 1,"C");
	//--------------------------------------
	//sostenedor
	switch($sostenedor)
	{
		case"otro":
			$msj_sostenedor="Sostenedor.: ".ucwords($nombre_sostenedor);
			break;
		case"alumno":
			$msj_sostenedor="Sostenedor.: Alumno";
			break;
		case"apoderado":
			$msj_sostenedor="Sostenedor.: Apoderado";		
			break;
	}
	$pdf->MultiCell(195,6,$msj_sostenedor,$borde_p, 1,"C");
	
	
	//fecha
	//$pdf->Cell(195,6,$fecha_actual,$borde_p,0,'C');

	
	
	//fin documento
	mysql_close($conexion);
	$pdf->Output();

function FOLIO($id_alumno, $sede, $semestre, $año)
{
	$tabla_contrato="contratos2";
	$consF2="SELECT id FROM $tabla_contrato WHERE id_alumno='$id_alumno' AND sede='$sede' AND ano='$año' AND semestre='$semestre'";
	$sql2=mysql_query($consF2)or die("Buscando Folio ".mysql_error());
	$D2=mysql_fetch_row($sql2);
	$id_contrato=$D2[0];
	mysql_free_result($sql2);
	return($id_contrato);
}
?>	