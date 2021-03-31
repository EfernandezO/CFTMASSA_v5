<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
////////////////
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	include("../../../../librerias/fpdf/fpdf.php");
$mostrar_logo=false;	
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
	
	$alto_celda=5;
	$borde=0;
	$borde_p=0;
	$letra_1=10;
	$letra_2=10;
	$letra_3=12;//comentario beca
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
	$pdf->SetAutoPageBreak(true, 5);
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
		
		$nombre=utf8_decode($_SESSION["CONTRATO_OLD"]["nombre"]);
		$apellido_P=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_P"]);
		$apellido_M=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_M"]);
		$apellido_old=utf8_decode($_SESSION["CONTRATO_OLD"]["apellido_old"]);
		
		//var_export($_SESSION["CONTRATO_OLD"]);
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
		$direccion_alumno=utf8_decode($_SESSION["CONTRATO_OLD"]["direccion"]);
		$valor_arancel=$_SESSION["CONTRATO_OLD"]["arancel"];
		
		$aux_valor_arancel=$valor_arancel;
		
		$valor_matricula=$_SESSION["CONTRATO_OLD"]["matricula_a_pagar"];
		$opcion_matricula=$_SESSION["CONTRATO_OLD"]["opcion_pago_mat"];
		$semestre=$_SESSION["CONTRATO_OLD"]["semestre"];
		$year_estudio=$_SESSION["CONTRATO_OLD"]["year_estudio"];
		$año=$year_estudio;
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
			$prex="";
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
			$descuento+=$_SESSION["CONTRATO_OLD"]["saldo_a_favor"];//agregado
			$valor_arancel=round($valor_arancel-$descuento);
		}
		
		
		if($porcentaje_beca>0)
		{
			$descuento=($valor_arancel*$porcentaje_beca)/100;
			$valor_arancel=round($valor_arancel-$descuento);
		}
		
		if($valor_arancel<0)//agregado en caso que quede saldo negativo por saldo a favor
		{ $valor_arancel=0;}
		//////////////////////
		
		
		///////////////////
		switch($vigencia)
		{
			case"anual":
				$msj_vigencia="(anual)";
				$semestre_año=" año ".$año;
				break;
			case"semestral":
				$msj_vigencia="";
				$semestre_año=$semestre." Semestre del año ".$año;
				break;	
		}
	//variable x parrafo parrafo
	$parrafo_1="\t\t\t\t\t\t\t\tEn ".$lugar_contrato." a ".$fecha_actual_palabra.", entre Juan Carlos Figueroa U., RUT: 6.015.058-3, Representante Legal del Centro de Formacion Tecnica Massachusetts. RUT.:89.921.100-6 con domicilio en ".$direccion_cft." de esta ciudad y Don(ña) ".$alumno." con domicilio en ".$direccion_alumno.", ".$ciudad_alumno." en adelante el Alumno, conviene el siguiente contrato de Prestacion de Servicios Educacionales:";
	//*************
	$parrafo_2="El C.F.T Massachusetts, se obliga a impartir la carrera que el alumno opta de acuerdo a los Planes aprobados. Ademas ofrecera todo el uso del equipamiento Academico, según necesidad de la carrera.";
	
	$parrafo_3="Don(ña) ".$alumno." se obliga a asistir a clases regularmente y a cumplir las norrmas establecidas en el Reglamento Académico.";
	
	$parrafo_4="Don(ña) ".$alumno." pagará un arancel $msj_vigencia de $".number_format($aux_valor_arancel,0,",",".")." por el ".$semestre_año." y una matricula anual de $ ".number_format($valor_matricula,0,",",".")." cuyos vencimientos serán fijados libremente por el alumno, respetando los plazos generales fijados por el C.F.T. Massachusetts Ltda.";
	
	$parrafo_5a="a)	El alumno para hacer efectivo su retiro deberá informarlo por escrito en los departamentos de secretaría académica y finanzas.";
	
	$parrafo_5b="b)	Administrativamente, el alumno tendrá la obligación de cancelar la totalidad del valor del semestre en curso a menos que:";
		$parrafo_5b1="1. Se comunique antes del inicio de clases.";
		$parrafo_5b2="2. Sea en caso  de fuerza mayor como: Traslado de ciudad, enfermedad o fallecimiento del sostenedor, debidamente justificados con los documentos pertinentes.";
		$parrafo_5c="c)En cualquier caso no estipulado en los puntos anteriores, el alumno podra exponer su problema, por escrito al Consejo Superior de la Institucion, el que decidira la situacion en particular.";
		
		$parrafo_6="El no pago del documento en la fecha dara derecho a la institucion a seguir las acciones legales que correspondan, y a suspender las prestaciones motivo de este contrato, siendo de responsabilidad del alumno los daños academicos que por este motivo se ocasione. Ademas, el alumno autoriza a enviar los antecedentes de su deuda vencida a los servicios de informacion comercial para conocimiento publico.";
		
		$parrafo_7="El Alumno queda matriculado en la Carrera: ".$carrera." Nivel: ".$nivel." Jornada: ".$jornada_label;
		$parrafo_8="Es decision del C.F.T realizar cambios de jornada de un curso cuando lo estime conveniente.";
		
		$parrafo_9="El Presente contrato durará hasta el ".fecha_format($fecha_fin_contrato);
		$parrafo_10="Los valores del proceso de titulacion no estan contemplados en este contrato.";
		$parrafo_11="El C.F.T. se reserva el derecho a no dar inicio a una carrera por no tener el número mínimo de veinte alumnos matriculados en el primer nivel. Las personas que se matriculan en alguna de estas carreras declaran que fueron informadas oportunamente de la alternativa de que la carrera no sea dictada por el C.F.T. por lo que renuncian a toda acción que tenga como objetivo de que la carrera se imparta o retribución pecuniaria. A su vez el C.F.T se obliga a reintegrar el monto de la matricula pagado por el suscrito.";
		if($mostrar_logo)
		{
			$pdf->image($logo,14,3,30,24,'jpg'); //este es el logo
		}	
	//titulo
	$pdf->SetY(0);
	$pdf->SetFont('Arial','U',14);
	$pdf->Text(50,12,$titulo);
	//$pdf->Cell(195,6,$titulo,$borde,1,'C');
	
	//***************************************************
	//
	$pdf->SetFont('Arial','',$letra_1);
	//folio
	$prefijo_folio=substr($sede_alumno,0,1);
	$pdf->Cell(195,$alto_celda+23,"FOLIO Nº.: ".$prefijo_folio." ".$folio_contrato,$borde,1,'R');
	///primer parrafo
	$pdf->MultiCell(195,$alto_celda,$parrafo_1,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"PRIMERO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_2,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"SEGUNDO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_3,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"TERCERO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_4,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"CUARTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->Cell(23,$alto_celda,"del Retiro",$borde,1,'L');	
	
	$pdf->MultiCell(195,$alto_celda,$parrafo_5a,$borde,1,'L');
	
	$pdf->MultiCell(195,$alto_celda,$parrafo_5b,$borde,1,'L');
	$pdf->Cell(10,$alto_celda,"",$borde,0,'L');
	$pdf->MultiCell(185,$alto_celda,$parrafo_5b1,$borde,1,'L');
	$pdf->Cell(10,$alto_celda,"",$borde,0,'L');
	$pdf->MultiCell(185,$alto_celda,$parrafo_5b2,$borde,1,'L');
	$pdf->Cell(10,$alto_celda,"",$borde,0,'L');
	//$pdf->MultiCell(185,$alto_celda,$parrafo_5b3,$borde,1,'L');
	
	$pdf->MultiCell(195,$alto_celda,$parrafo_5c,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"QUINTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_6,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"SEXTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_7,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"SEPTIMO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_8,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"OCTAVO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_9,$borde,1,'L');
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"NOVENO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_10,$borde,1,'L');
	//agregado
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"DECIMO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_11,$borde,1,'L');
	
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
		$pdf->Cell(195,$alto_celda,"*Vencimiento de Mensualidades - $vigencia*",$borde_p, 0,"C");
		//cabecera
		$pdf->Ln();
		$pdf->SetFont('Arial','B',$letra_1);
		$x_desplazamiento=35;
		$pdf->SetX($x_desplazamiento);
		$pdf->Cell(47,$alto_celda,"Cuotas",$borde_p, 0,"C");
		$pdf->Cell(47,$alto_celda,"Fecha Vencimiento",$borde_p, 0,"C");
		$pdf->Cell(47,$alto_celda,"Valor",$borde_p, 1,"C");
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
		
		//---------Modificado solo muestro cuotas pendientes---------------------///
		$mes=$mes_ini;
		$id_contrato=$_SESSION["CONTRATO_OLD"]["id_contrato"];
		$cons_cuotas="SELECT * FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND pagada IN('N','A', 'S') ORDER by fechavenc";
		//echo "$cons_cuotas<br>";
		$sql_cuota=mysql_query($cons_cuotas)or die(mysql_error());
		$num_cuotas=mysql_num_rows($sql_cuota);
		$cuenta_y=1;
		while($DL=mysql_fetch_assoc($sql_cuota))
		{
			
			$label_cuota="$cuenta_y/$num_cuotas";
			$cuenta_y++;
			$aux_vencimiento=$DL["fechavenc"];
			$valor_cuota_label=number_format($DL["valor"],0,",",".");
			$deudaXcuota_label=number_format($DL["deudaXletra"],0,",",".");//agregada
			$condicion_cuota=$DL["pagada"];
			switch($condicion_cuota)
			{
					case"S":
						$condicion_cuota_label="pagada";
						$valor_a_mostrar=$valor_cuota_label." (cancelada)";
						break;
					case"A":
						$condicion_cuota_label="abonada";
						$valor_a_mostrar=$deudaXcuota_label;
						break;
					case"N":
						$condicion_cuota_label="pendiente";
						$valor_a_mostrar=$deudaXcuota_label;
						break;		
						
			}
				$condicion_cuota_label="";
			
			$pdf->SetX($x_desplazamiento);
			$pdf->Cell(47,$alto_celda,$label_cuota." ".$condicion_cuota_label,$borde_p, 0,"C");
			$pdf->Cell(47,$alto_celda,fecha_format($aux_vencimiento),$borde_p, 0,"C");
			$pdf->Cell(47,$alto_celda,"$".$valor_a_mostrar,$borde_p, 1,"C");
		}
		mysql_free_result($sql_cuota);
	}	
	//----------------------------------------------------------------------------------------------//
	$pdf->Ln();
	//info de boleta
	//var_export($array_boleta);
	//texto para final matricula
	$txt_matricula="Matricula";
	switch($opcion_matricula)
	{
		case"L_CREDITO":
			//var_export($_SESSION["FINANZAS"]);
			$txt_matricula.=" Fecha Vencimiento Mensualidad (Matricula).: ".fecha_format($_SESSION["CONTRATO_OLD"]["fecha_vence_lcredito_mat"])." Valor.: $ ".number_format($valor_matricula,0,",",".");
			break;
			
		case"CONTADO":
		if($_SESSION["CONTRATO_OLD"]["sede_boleta"]!=$sede_alumno)
			{ $sede_diferente="-".$_SESSION["CONTRATO_OLD"]["sede_boleta"];}
			$txt_matricula.=" (Contado) ID Boleta.: ".$_SESSION["CONTRATO_OLD"]["id_boleta"]."(".$_SESSION["CONTRATO_OLD"]["folio_boleta"].$sede_diferente.") Fecha.: ".fecha_format($_SESSION["CONTRATO_OLD"]["fecha_boleta"],"-")." Valor.: $ ".number_format($valor_matricula,0,",",".");
			
			break;
		case"CHEQUE":
			$txt_matricula.=" (Cheque) ID Boleta.:".$_SESSION["CONTRATO_OLD"]["id_boleta"]."(".$_SESSION["CONTRATO_OLD"]["folio_boleta"].") Fecha.: ".$fecha_actual." Valor.: $ ".number_format($valor_matricula,0,",",".");	
			break;	
		case"NO":
			$txt_matricula.=" No Paga Matricula";
			break;
		case"EXCEDENTE":
			$excedente=$_SESSION["CONTRATO_OLD"]["saldo_a_favor"];
			$valor_matriculaX=$_SESSION["CONTRATO_OLD"]["valor_matricula"];
			if($excedente>=$valor_matriculaX)
			{
				
				$matricula_a_pagar=0;
				$excedentes_diponibles=($excedente-$valor_matriculaX);
				$txt_matricula.="*Alumno tiene un Saldo a Favor de $".number_format($excedente,0,",",".").", utiliza para matricula: $ ".number_format($valor_matricula,0,",",".").", en arancel: $".number_format($excedentes_diponibles,0,",",".")." Total a Pagar Mat.:$".number_format($matricula_a_pagar,0,",",".");
			}
			else
			{
				$matricula_a_pagar=($valor_matriculaX-$excedente);
				$excedentes_diponibles=0;
				
					
					$txt_matricula.=" *Alumno tiene un Saldo a Favor de $".number_format($excedente,0,",",".").", utiliza para matricula: $".number_format($excedente,0,",",".").", en arancel: $".number_format($excedentes_diponibles,0,",",".")." Total a Pagar Mat.:$".number_format($matricula_a_pagar,0,",",".")." ID Boleta.: ".$_SESSION["CONTRATO_OLD"]["id_boleta"]."(".$_SESSION["CONTRATO_OLD"]["folio_boleta"].") Fecha.: ".fecha_format($_SESSION["CONTRATO_OLD"]["fecha_boleta"],"-")." Valor a pagar.: $ ".number_format($matricula_a_pagar,0,",",".")."*";
			}
			break;		
	}
	////////////////////////////////////////////////////////////////////////
			$cheque_cantidad=$_SESSION["CONTRATO_OLD"]["cheque_paga"];
			$contado_cantidad=$_SESSION["CONTRATO_OLD"]["contado_paga"];
			$pagoXarancel=($cheque_cantidad + $contado_cantidad);
			
			$id_boleta=$_SESSION["CONTRATO_OLD"]["id_boleta_generada"];
			/////busco_folio boleta
			$cons_bf="SELECT folio, fecha, valor FROM boleta WHERE id='$id_boleta'";
			$sql_bf=mysql_query($cons_bf)or die("folio_boleta2".mysql_error());
			$D_bf=mysql_fetch_assoc($sql_bf);
			$folio_boletaX=$D_bf["folio"];
			$fecha_boletaX=$D_bf["fecha"];
			$valor_boletaX=$D_bf["valor"];
			mysql_free_result($sql_bf);
			if(($cheque_cantidad>0)or($contado_cantidad>0))
			{
				$txt_arancel="Arancel ID Boleta.:".$id_boleta." (".$folio_boletaX.") Fecha.: ".fecha_format($fecha_boletaX)." Valor.: $".number_format($pagoXarancel,0,",",".");	
			}
	////////////////////////////////////////////////////////////////////////
	
	
	$pdf->MultiCell(195,$alto_celda,$txt_matricula,$borde_p, 1,"C");
	$pdf->MultiCell(195,$alto_celda,$txt_arancel,$borde_p, 1,"C");
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
	//$pdf->MultiCell(195,$alto_celda,$msj_sostenedor,$borde_p, 1,"C");
	
	
	//fecha
	//$pdf->Cell(195,$alto_celda,$fecha_actual,$borde_p,0,'C');
	///texto extra comentario beca
	$porcentaje_beca=$_SESSION["CONTRATO_OLD"]["porcentaje_beca"];
	$comentario_beca=$_SESSION["CONTRATO_OLD"]["txt_beca"];
	$cantidad_beca=$_SESSION["CONTRATO_OLD"]["cantidad_beca"];
	
	$beca_nuevo_milenio=$_SESSION["CONTRATO_OLD"]["beca_nuevo_milenio"];
	$aporte_beca_nuevo_milenio=$_SESSION["CONTRATO_OLD"]["aporte_beca_nuevo_milenio"];
	
	
	$beca_excelencia=$_SESSION["CONTRATO_OLD"]["beca_excelencia"];
	$aporte_beca_excelencia=$_SESSION["CONTRATO_OLD"]["aporte_beca_excelencia"];
	
	/////////////
	$mostrar_comentario_beca=false;
	
	if(!empty($comentario_beca))
	{ $mostrar_comentario_beca=true;}
	
	if(($cantidad_beca>0)or($porcentaje_beca>0)or($aporte_beca_nuevo_milenio>0)or($aporte_beca_excelencia>0))
		{ $mostrar_comentario_beca=true;}
	if($mostrar_comentario_beca)
	{
		
		$pdf->SetFont('Arial','',$letra_3);
		$pdf->MultiCell(190,$alto_celda,"[".utf8_decode($comentario_beca)."]",$borde);
	}
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