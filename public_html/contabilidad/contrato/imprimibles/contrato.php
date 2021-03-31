<?php
error_reporting(E_ALL);
	ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	require("../../../libreria_publica/fpdf/flowing_block.php");
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
//-----------------------------------------//
$continuar=false;
if(isset($_SESSION["FINANZAS"]["GRABADO"]))
{
	if($_SESSION["FINANZAS"]["GRABADO"])
	{$continuar=true;}
}
//----------------------------------------------//

if($continuar)
{
	///////////////saco de lista de pendientes al contrato academico//////
	$_SESSION["FINANZAS"]["impresion"]["contrato_academico"]=true;
	/////////////////

	$logo="../../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$y_firmas=250;
	
	$alto_celda=5;
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
	$pdf=new PDF_FlowingBlock('P','mm',$hoja_oficio);
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetAutoPageBreak(true, 5);
	//arreglo variables
	$id_alumnoX=$_SESSION["FINANZAS"]["id_alumno"];
		$lugar_contrato=$_SESSION["FINANZAS"]["lugar_contrato"];
		$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
		if($sede_alumno=="Talca")
		{
			$direccion_cft="3 Sur Nª 1068";
		}
		else
		{
			$direccion_cft="O'Higgins Nª 313";
		}	
		//nombre alumno
		$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
		$alumno=utf8_decode(ucwords(strtolower($alumno)));
		
		
		$cons_DA="SELECT direccion, ciudad FROM alumno WHERE id='$id_alumnoX'";
		$sqli_DA=$conexion_mysqli->query($cons_DA);
		$DAD=$sqli_DA->fetch_assoc();
			$direccion_alumno=$DAD["direccion"].", ".$DAD["ciudad"];
			$ciudad_alumno=$DAD["ciudad"];
		$sqli_DA->free();	
		
		/////////////////////////
		$vigencia=$_SESSION["FINANZAS"]["vigencia_cuotas"];
		
		
		//echo"----> $semestre_año<br>";
		////////////////////////////////////
		
		
		$valor_matricula=$_SESSION["FINANZAS"]["matricula"];
		$opcion_matricula=$_SESSION["FINANZAS"]["opcion_matricula"];
		$semestre=$_SESSION["FINANZAS"]["semestre"];
		//$año=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]));
		$año=$_SESSION["FINANZAS"]["year_estudio"];//cambiado
		$year_estudio=$_SESSION["FINANZAS"]["year_estudio"];
		
		//$estacion_retiro=$_SESSION["FINANZAS"]["estacion_retiro"];
		$carrera=utf8_decode($_SESSION["FINANZAS"]["carrera_alumno"]);
		$nivel=$_SESSION["FINANZAS"]["nivel"];
		$nivel_2="aun no definido";/// 02062016
		$jornada=$_SESSION["FINANZAS"]["jornada"];
		$fecha_fin_contrato=$_SESSION["FINANZAS"]["fecha_fin"];
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		$sostenedor=$_SESSION["FINANZAS"]["sostenedor"];
		
		
		
		if($sostenedor=="otro")
		{
			$nombre_sostenedor=$_SESSION["FINANZAS"]["sostenedor_nombre"];
		}
		$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
		
		//////////////////////////////////////////////////////////////////////////
		$folio_contrato=FOLIO($id_alumnoX, $sede_alumno, $semestre, $year_estudio);
		$actual_largo_folio=strlen($folio_contrato);
		$diferencia=($largo_folio-$actual_largo_folio);
		$prex="";
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
		
		/*
		
		if($porcentaje_beca>0)
		{
			$descuento=($valor_arancel*$porcentaje_beca)/100;
			$valor_arancel=round($valor_arancel-$descuento);
		}
		//////////////descontando la cantidad de beca
		if($_SESSION["FINANZAS"]["cantidad_beca"]>0)
		{
			$descuento=$_SESSION["FINANZAS"]["cantidad_beca"];
			$valor_arancel=round($valor_arancel-$descuento);
		}
		////////////
		*/
		$valor_arancel=$_SESSION["FINANZAS"]["total_a_pagar_arancel"];
		
		switch($vigencia)
		{
			case"anual":
				$valor_arancel=$_SESSION["FINANZAS"]["arancel_anual"];
				$msj_vigencia="(anual)";
				$semestre_año=" año ".$año;
				break;
			case"semestral":
				$valor_arancel=$_SESSION["FINANZAS"]["arancel"];
				$msj_vigencia="";
				$semestre_año=$semestre." Semestre del año ".$año;
				break;	
		}
	//variable x parrafo parrafo
	$parrafo_1_a="\t\t\t\t\t\t\t\tEn ".$lugar_contrato." a ".$fecha_actual_palabra.", entre Juan Carlos Figueroa U., RUT: 6.015.058-3, Representante Legal del Centro de Formacion Tecnica Massachusetts. RUT.:89.921.100-6 con domicilio en ".$direccion_cft." de esta ciudad y Don(ña) ";
	$parrafo_1_b=" con domicilio en ".utf8_decode($direccion_alumno).", ".$ciudad_alumno." en adelante el Alumno, conviene el siguiente contrato de Prestacion de Servicios Educacionales:";
	//*************
	$parrafo_2="El C.F.T Massachusetts, se obliga a impartir la carrera que el alumno opta de acuerdo a los Planes aprobados. Ademas ofrecera todo el uso del equipamiento Academico, según necesidad de la carrera.";
	
	$parrafo_3="Don(ña) ".$alumno." se obliga a asistir a clases regularmente y a cumplir las norrmas establecidas en el Reglamento Académico.";
	
	$parrafo_4="Don(ña) ".$alumno." pagará un arancel $msj_vigencia de $".number_format($valor_arancel,0,",",".")." por el ".$semestre_año." y una matricula anual de $ ".number_format($valor_matricula,0,",",".").", cuyas fechas de vencimiento serán fijados libremente por el alumno, respetando los plazos generales fijados por el C.F.T. Massachusetts Ltda.";
	
	$parrafo_5a="a)	El alumno para hacer efectivo su retiro deberá informarlo por escrito en los departamentos de secretaría académica y finanzas.";
	
	$parrafo_5b="b)	Administrativamente, el alumno tendrá la obligación de cancelar la totalidad del valor del semestre en curso a menos que:";
		$parrafo_5b1="1. Se comunique antes del inicio de clases.";
		$parrafo_5b2="2. Sea en caso  de fuerza mayor como: Traslado de ciudad, enfermedad o fallecimiento del sostenedor, debidamente justificados con los documentos pertinentes.";
		$parrafo_5c="c)En cualquier caso no estipulado en los puntos anteriores, el alumno podra exponer su problema, por escrito al Consejo Superior de la Institucion, el que decidira la situacion en particular.";
		
		$parrafo_6="El no pago del documento en la fecha dara derecho a la institucion a seguir las acciones legales que correspondan, y a suspender las prestaciones motivo de este contrato, siendo de responsabilidad del alumno los daños academicos que por este motivo se ocasione. Ademas, el alumno autoriza a enviar los antecedentes de su deuda vencida a los servicios de informacion comercial para conocimiento publico.";
		
		$parrafo_7_a="El Alumno queda matriculado en la Carrera: ";
		
		if($vigencia=="anual")
		{$parrafo_7_b=" Nivel: ".$nivel." (semestre 1) y Nivel: ".$nivel_2." (semestre 2), Jornada: ".$jornada_label;}
		else
		{ $parrafo_7_b=" Nivel: ".$nivel.", Jornada: ".$jornada_label;}
		
		$parrafo_8="Es decision del C.F.T realizar cambios de jornada de un curso cuando lo estime conveniente.";
		
		$parrafo_9="El Presente contrato durará hasta el ".$fecha_fin_contrato;
		$parrafo_10="Los valores del proceso de titulacion no estan contemplados en este contrato.";
		$parrafo_11="El C.F.T. se reserva el derecho a no dar inicio a una carrera por no tener el número mínimo de veinte alumnos matriculados en el primer nivel. Para optar a la mencion de una carrera es requisito contar con diez alumnos matriculados como minimo en el quinto nivel de dicha carrera. Las personas que se matriculan en alguna de estas carreras declaran que fueron informadas oportunamente de la alternativa de que la carrera no sea dictada por el C.F.T. por lo que renuncian a toda acción que tenga como objetivo de que la carrera se imparta o retribución pecuniaria. A su vez el C.F.T se obliga a reintegrar el monto de la matricula pagado por el suscrito.";
		if($mostrar_logo)
		{
			$pdf->image($logo,14,3,30,24,'jpg'); //este es el logo
		}	
	//titulo
	//titulo
	$pdf->SetY(0);
	$pdf->SetFont('Arial','U',14);
	$pdf->Text(50,12,$titulo);
	
	//***************************************************
	//
	$pdf->SetFont('Arial','',$letra_1);
	//folio
	$prefijo_folio=substr($sede_alumno,0,1);
	$pdf->Cell(195,$alto_celda+23,"FOLIO N.: ".$prefijo_folio." ".$folio_contrato,$borde,1,'R');
	///primer parrafo
	$pdf->newFlowingBlock( 195, $alto_celda, 0, 'L');
	$pdf->SetFont( 'Arial', '', $letra_1 );
	$pdf->WriteFlowingBlock( $parrafo_1_a );
	$pdf->SetFont( 'Arial', 'B', $letra_1 );
	$pdf->WriteFlowingBlock($alumno);
	$pdf->SetFont( 'Arial', '', $letra_1 );
	$pdf->WriteFlowingBlock($parrafo_1_b);
	$pdf->finishFlowingBlock();
	
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
	//$pdf->MultiCell(195,$alto_celda,$parrafo_5d,$borde,1,'L');
	
	
	//$pdf->Ln();
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"QUINTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->MultiCell(172,$alto_celda,$parrafo_6,$borde,1,'L');
	
	$pdf->SetFont('Arial','B',$letra_1);
	$pdf->Cell(23,$alto_celda,"SEXTO",$borde,0,'L');	
	$pdf->SetFont('Arial','',$letra_1);
	$pdf->setX(10);
		$pdf->newFlowingBlock(172, $alto_celda, 0, 'L');
		$pdf->SetFont( 'Arial', '', $letra_1 );
		$pdf->WriteFlowingBlock($parrafo_7_a);
		$pdf->SetFont( 'Arial', 'B', $letra_1 );
		$pdf->WriteFlowingBlock($carrera);
		$pdf->SetFont( 'Arial', '', $letra_1 );
		$pdf->WriteFlowingBlock($parrafo_7_b);
		$pdf->Cell(23,$alto_celda,"",$borde,0,'L');
		$pdf->finishFlowingBlock();
	
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
	$linea_credito_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"];
	if($linea_credito_cantidad>0)
	{	
		$pdf->Ln();
		$pdf->Cell(195,$alto_celda,"*Vencimiento de Mensualidades - ".ucwords(strtolower($vigencia))."*",$borde_p, 0,"C");
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
		
		
		$cantidad_cuotas=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"];
		$aux=0;
		$linea_credito_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"];
		$valor_cuota=round($linea_credito_cantidad/$cantidad_cuotas);
		$valor_cuota_label=number_format($valor_cuota,0,",",".");
		$dia_vence=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["dia_vence_cuota"];
		$mes_ini=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["mes_ini_cuota"];
		$year=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["year"];
		
		///////ESCRITURA DE CUOTAS///
	//	var_export($_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]);
		$meses_avance=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["meses_avance"];//agregado
		$mes=$mes_ini;
		$pdf->SetFont('Arial','',$letra_1);
		for($c=1;$c<=$cantidad_cuotas;$c++)
		{
			if($mes<10)
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
			$pdf->Cell(47,$alto_celda,$label_cuota,$borde_p, 0,"C");
			$pdf->Cell(47,$alto_celda,$aux_vencimiento,$borde_p, 0,"C");
			$pdf->Cell(47,$alto_celda,"$".$valor_cuota_label,$borde_p, 1,"C");
			$aux++;
			
			/////
			$mes+=$meses_avance;
			if($mes>12)
			{
				$mes-=12;//modificado
				$year++;
			}
		}
	}	
	
	$pdf->Ln();
	//info de boleta
	$array_boleta=$_SESSION["FINANZAS"]["BOLETA"];
	$numero_boleta_matricula=$array_boleta["matricula"];
	$numero_boleta_contado=$array_boleta["contado"];
	$numero_boleta_cheque=$array_boleta["cheque"];
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
			$consB="SELECT folio FROM boleta WHERE id='$numero_boleta_contado'";
			
			$sql_bo=$conexion_mysqli->query($consB);
			$DB=$sql_bo->fetch_assoc();
			$aux_folio=$DB["folio"];
			$sql_bo->free();
			
			$txt_matricula.=" (Contado) ID Boleta.: ".$numero_boleta_contado."($aux_folio) Fecha.: ".$fecha_actual." Valor.: $ ".number_format($valor_matricula,0,",",".");
			break;
		case"EXCEDENTE":
			$excedente=$_SESSION["FINANZAS"]["excedente"];
			if($excedente>=$valor_matricula)
			{
				
				$matricula_a_pagar=0;
				$excedentes_diponibles=($excedente-$valor_matricula);
				$txt_matricula.="*Alumno tiene un Saldo a Favor de $".number_format($excedente,0,",",".").", utiliza para matricula: $ ".number_format($valor_matricula,0,",",".").", en arancel: $".number_format($excedentes_diponibles,0,",",".")." Total a Pagar Mat.:$".number_format($matricula_a_pagar,0,",",".");
			}
			else
			{
				$matricula_a_pagar=($valor_matricula-$excedente);
				$excedentes_diponibles=0;
				if(!empty($numero_boleta_contado))
				{
					$consB="SELECT folio FROM boleta WHERE id='$numero_boleta_contado'";
					
					$sql_bo=$conexion_mysqli->query($consB);
					$DB=$sql_bo->fetch_assoc();
					$aux_folio=$DB["folio"];
					$sql_bo->free();
					
					$txt_matricula.=" *Alumno tiene un Saldo a favor de $".number_format($excedente,0,",",".").", utiliza para matricula: $".number_format($excedente,0,",",".").", en arancel: $".number_format($excedentes_diponibles,0,",",".")." Total a Pagar Mat.:$".number_format($matricula_a_pagar,0,",",".")." ID Boleta.: ".$numero_boleta_contado."($aux_folio) Fecha.: ".$fecha_actual." Valor a pagar.: $ ".number_format($matricula_a_pagar,0,",",".")."*";
				}
			}
			
			break;	
			
		case"CHEQUE":
			$consB="SELECT folio FROM boleta WHERE id='$numero_boleta_cheque'";
			
			$sql_bo=$conexion_mysqli->query($consB);
			$DB=$sql_bo->fetch_assoc();
			$aux_folio=$DB["folio"];
			$sql_bo->free();
			
			$txt_matricula.=" (Cheque) ID Boleta.:".$numero_boleta_cheque."($aux_folio) Fecha.: ".$fecha_actual." Valor.: $ ".number_format($valor_matricula,0,",",".");	
			break;	
		case"NO":
			$txt_matricula.=" No Paga Matricula";
			break;	
	}
			//////////////////////////////////Mensaje detalle de pagos contado y cheques//////////////////////////////////////////////
			////busco_folio boleta
			$id_boleta=$_SESSION["FINANZAS"]["BOLETA"]["contado"];
			$cheque_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"];
			$contado_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"];
			
			$pagoXarancel=($cheque_cantidad + $contado_cantidad);
			
			$cons_bf="SELECT folio, fecha, valor FROM boleta WHERE id='$id_boleta'";
		//	echo"--> $cons_bf<br>";
			$sql_bf=$conexion_mysqli->query($cons_bf)or die($conexion_mysqli->error);
			$D_bf=$sql_bf->fetch_assoc();
			$folio_boletaX=$D_bf["folio"];
			$fecha_boletaX=$D_bf["fecha"];
			$valor_boletaX=$D_bf["valor"];
			$sql_bf->free();
			
			//echo"||||||>$contado_cantidad $cheque_cantidad<br>";
			if(($cheque_cantidad>0)or($contado_cantidad>0))
			{
				$txt_arancel="Arancel ID Boleta.:".$id_boleta." (".$folio_boletaX.") Fecha.: ".fecha_format($fecha_boletaX)." Valor.: $".number_format($pagoXarancel,0,",",".");	
				$mostrar_txt_arancel=true;
			}
			else{ $txt_arancel=""; $mostrar_txt_arancel=false;}
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	$pdf->MultiCell(195,$alto_celda,$txt_matricula,$borde_p, 1,"C");
	if($mostrar_txt_arancel){$pdf->MultiCell(195,$alto_celda,$txt_arancel,$borde_p, 1,"C");}
	//--------------------------------------
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
			$cons_AP="SELECT rut_apoderado, apoderado FROM alumno WHERE id='$id_alumnoX' LIMIT 1";
			
			$sql_AP=$conexion_mysqli->query($cons_AP)or die($conexion_mysqli->error);
			$DAP=$sql_AP->fetch_assoc();
				$rut_apoderado=$DAP["rut_apoderado"];
				$nombre_apellido_apoderado=$DAP["apoderado"];
			$msj_sostenedor="Sostenedor.: Apoderado $rut_apoderado $nombre_apellido_apoderado.-";		
			$sql_AP->free();
			break;
	}
	$pdf->MultiCell(195,$alto_celda,utf8_decode($msj_sostenedor),$borde_p, 1,"C");
	
	
	//fecha
	//$pdf->Cell(195,$alto_celda,$fecha_actual,$borde_p,0,'C');

	///texto extra comentario beca
	if(isset($_SESSION["FINANZAS"]["porcentaje_beca"]))
	{$porcentaje_beca=$_SESSION["FINANZAS"]["porcentaje_beca"];}
	else{$porcentaje_beca=0;}
	
	if(isset($_SESSION["FINANZAS"]["comentario_beca"]))
	{$comentario_beca=$_SESSION["FINANZAS"]["comentario_beca"];}
	else{$comentario_beca="";}
	
	if(isset($_SESSION["FINANZAS"]["comentario_beca_v2"]))
	{$comentario_beca_v2=$_SESSION["FINANZAS"]["comentario_beca_v2"];}
	else{$comentario_beca_v2="";}
	
	if(isset($_SESSION["FINANZAS"]["cantidad_beca"]))
	{$cantidad_beca=$_SESSION["FINANZAS"]["cantidad_beca"];}
	else{$cantidad_beca=0;}
	
	if(($cantidad_beca>0)or($porcentaje_beca>0)or(!empty($comentario_beca)))
	{ $mostrar_comentario_beca=true;}
	else
	{ $mostrar_comentario_beca=false;}
	
	if($mostrar_comentario_beca)
	{$pdf->MultiCell(190,$alto_celda,$comentario_beca_v2."[".$comentario_beca."]",$borde);}
	
	//fin documento
	$conexion_mysqli->close();
	$pdf->Output();
}
else
{
	if(DEBUG){ echo"Sin Sesion GRABADO<br>";}
	else{header("location: ../opciones_finales.php?error=1");}
}	
function FOLIO($id_alumno, $sede, $semestre, $año)
{
	require("../../../../funciones/conexion_v2.php");
	$tabla_contrato="contratos2";
	$consF2="SELECT id FROM $tabla_contrato WHERE id_alumno='$id_alumno' AND sede='$sede' AND ano='$año' AND semestre='$semestre'";
	$sql2=$conexion_mysqli->query($consF2)or die("FOLIO ".$conexion_mysqli->error);
	$D2=$sql2->fetch_row();
	$id_contrato=$D2[0];
	$sql2->free();
	$conexion_mysqli->close();
	return($id_contrato);
}
?>	