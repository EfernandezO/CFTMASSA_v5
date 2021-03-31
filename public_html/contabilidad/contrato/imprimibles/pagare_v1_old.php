<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	
	require("../../../libreria_publica/fpdf/fpdf.php");
	require("../../../../funciones/funcion.php");
//---------------------------------------------------------------------//
$mostrar_logo=true;	
$VER_DATOS=true;
$VER_PLANTILLA=false;
$alto_celda=6;
$borde=0;
$datos_utilizados="datos"; ///datos, lineas
//---------------------------------------------------------------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{$acceso=true;}
	else
	{$acceso=false;}
}
else
{$acceso=false;}
//-------------------------------------------------------------------------------------//

if(isset($_GET["id_contrato"]))
{$id_contrato=str_inde($_GET["id_contrato"]);}
else
{$id_contrato=0; if(DEBUG){echo"sin datos desde GET<br>";}}
//-------------------------------------------------------------------------------------//
if(is_numeric($id_contrato))
{
	if($id_contrato>0)
	{$datos_correctos=true; if(DEBUG){echo"Datos correctos<br>";}}
	else
	{$datos_correctos=false; if(DEBUG){echo"Datos Incorrectos<br>";}}
}
else
{$datos_correctos=false; if(DEBUG){echo"Datos incorrecots<br>";}}
//------------------------------------------------------------------------------------------//

//------------------------------------------------------------------------------------------//
if(($acceso)and($datos_correctos))
{
	require("../../../../funciones/conexion_v2.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	///////////////saco de lista de pendientes al contrato academico//////
	if(!DEBUG){$_SESSION["FINANZAS"]["impresion"]["pagare"]=true;}
	/////////////////

	$logo="../../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$y_firmas=250;
	
	
	
	$fecha=fecha();
	$autor="ACX";
	$titulo="PAGARE";
	$zoom=40;
	$largo_folio=5;///agrega "0" antes del folio hasta dejarlo del largo
	
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	$pdf=new FPDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->SetAutoPageBreak(true, 5);
	//------------------------------------------------//
	//arreglo variables
	switch($datos_utilizados)
	{
		case"lineas":
			$W_sede="_______";
			$W_dia="___";
			$W_mes="_________";
			$W_year="______";
			$W_nombre_completo_alumno="______________________________________________";
			$W_nacionalidad="_________";
			$W_estado_civil="_________";
			$W_rut_alumno="___________";
			$W_direccion_completa_alumno="___________________________________________________________";
			$W_telefono_1="___________";
			$W_telefono_2="___________";
			$W_valor_arancel="_________";
			$W_folio_boleta="_________";
			$W_numero_cuotas="____";
			$W_valor_cuota="_______";
			$W_dia_vencimiento_cuota="____";
			$W_primer_vencimiento_cuota="___________";
			$W_ultimo_vencimiento_cuota="___________";
			
			$W_F_deudor="____________________________________________________________";
			$W_F_direccion="____________________________________________________________";
			$W_F_ciudad="_______________________________________";
			$W_concepto_pago="__________________";
			break;
			
		case"datos":
			//----------------------------------------------------------------------------//
			//datos contrato
			$cons_C="SELECT * FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
			$sqli_C=$conexion_mysqli->query($cons_C);
			$DC=$sqli_C->fetch_assoc();
				$C_fecha_inicio=$DC["fecha_inicio"];
				$C_numero_cuotas=$DC["numero_cuotas"];
				$C_arancel=$DC["arancel"];
				$C_linea_credito=$DC["linea_credito_paga"];
				$C_sede=$DC["sede"];
				$C_vigencia=$DC["vigencia"];
				$C_semestre=$DC["semestre"];
				//$C_id_boleta_pagare=$DC["id_boleta_pagare"];
			$sqli_C->free();
			//datos alumno
			$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A);
			$DA=$sqli_A->fetch_assoc();
				$A_nombre=$DA["nombre"];
				$A_apellido_P=$DA["apellido_P"];
				$A_apellido_M=$DA["apellido_M"];
				$A_nacionalidad=$DA["nacionalidad"];
				$A_rut=$DA["rut"];
				$A_direccion=$DA["direccion"];
				$A_ciudad=$DA["ciudad"];
				$A_fono=$DA["fono"];
				$A_fonoa=$DA["fonoa"];
				$A_estado_civil=$DA["estado_civil"];
			$sqli_A->free();	
			//datos cuotas
			$cons_CUO="SELECT * FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' ORDER by fechavenc";
			$sqli_CUO=$conexion_mysqli->query($cons_CUO);
			$num_cuotas=$sqli_CUO->num_rows;
			$primera_vuelta=true;
			if($num_cuotas>0)
			{
				while($DCUO=$sqli_CUO->fetch_assoc())
				{
					$CUO_valor=$DCUO["valor"];
					$CUO_fecha_vence=$DCUO["fechavenc"];
					if($primera_vuelta)
					{ $W_primer_vencimiento_cuota=fecha_format($CUO_fecha_vence);}
				}
				$W_ultimo_vencimiento_cuota=fecha_format($CUO_fecha_vence);
				$W_valor_cuota=$CUO_valor;
				$W_dia_vencimiento_cuota=end(explode("-",$CUO_fecha_vence));
			}
			else
			{
				//sin cuotas
			}
			$sqli_CUO->free();	
			//--------------------------------------------------------------------------//
			/*boleta pagare
			$cons_B="SELECT folio FROM boleta WHERE id='$C_id_boleta_pagare' LIMIT 1";
			$sqli_B=$conexion_mysqli->query($cons_B);
			$BP=$sqli_B->fetch_assoc();
				$B_folio=$BP["folio"];
			$sqli_B->free();*/	
			//------------------------------------------------------------------------//
			$W_sede=$C_sede;
			$array_fecha_inicio_contrato=explode("-",$C_fecha_inicio);
			
			$W_dia=$array_fecha_inicio_contrato[2];
			$W_mes=$array_fecha_inicio_contrato[1];
			$W_year=$array_fecha_inicio_contrato[0];
			
			switch($C_vigencia)
			{
				case"semestral":
					$W_concepto_pago=$C_semestre."° semestre año ".$W_year;
					break;
				case"anual":
					$W_concepto_pago="año".$W_year;
					break;
			}
			$W_nombre_completo_alumno=$A_nombre." ".$A_apellido_P." ".$A_apellido_M;
			$W_nacionalidad=$A_nacionalidad;
			$W_estado_civil=$A_estado_civil;
			$W_rut_alumno=$A_rut;
			$W_direccion_completa_alumno=$A_direccion.", ".$A_ciudad;
			$W_telefono_1=$A_fono;
			$W_telefono_2=$A_fonoa;
			
			$W_valor_arancel=$C_linea_credito;
			
			//$W_folio_boleta=$B_folio;
			
			$W_numero_cuotas=$C_numero_cuotas;

			
			$W_F_deudor=$A_nombre." ".$A_apellido_P." ".$A_apellido_M;
			$W_F_direccion=$A_direccion;
			$W_F_ciudad=$A_ciudad;
			break;
}
	
	
	
	//-----------------------------------------------//	
	//imagen texto
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
		$pdf->SetFont('Times','B',14);
		$pdf->Text(160,10,"PAGARE N° 00000");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	if($VER_PLANTILLA){$pdf->image($logo,15,5,30,24,'jpg');} //este es el logo
	$pdf->SetFont('Times','',10);
	$pdf->Text(45,14,"Centro de Formacion Tecnica");
	$pdf->SetFont('Times','',10);	
	$pdf->Text(45,17,"Massachusetts Limitada");
	$pdf->Text(45,21,"Rut: 89.921.100-6");
	$pdf->Text(45,24,"Casa Matriz. 3 Sur 1068 Talca");
	$pdf->Text(45,27,"Sucursal: O'higgins 313 Linares");
	//-----------------------------------------------//
	

	$parrafo="En caso de mora o simplemente retardo en el pago de una cualquiera de las cuotas señaladas, este pagaré devengará, por el lapso que dure la mora o retardo, el interés maximo convencional que la ley permita estipular para operaciones de credito de dinero en moneda nacional no reajustable, pudiendo el Centro de Formacion Tecnica Massachusetts Limitada, si el atraso es superior a un mes, hacer exigible el total de lo adeudado, el que en ese momento se considerara de plazo vencido para todos los efectos legales.- El suscriptor autoriza expresamente al Centro de Formación Técnica Massachusetts Limitada, para que en el caso de mora en el cumplimiento de sus obligaciones emanadas del presente pagaré, sea incluida en los listados que se remiten a las empresas de informacion comercial, conforme a lo dispuesto en la ley 19.628.- Asimismo, libero al Centro de Formacion Técnica Massachusetts Limitada en caso de extinguirse la deuda de las obligaciones de aviso y modificacion señaladas en el articulo 19, de la ley 19.628, las que requeriré directa y personalmente al banco de datos respectivos, previa entrega de la constancia de pago correspondiente. Libero además, al Centro de Formacion Técnica Massachusetts Limitada de la obligacion de protesto. Cualquier impuesto, derecho o gasto que se devengue con ocasión de este pagaré, su modificación, prorroga, pago, protesto será de cargo exclusivo del suscriptor.- Para todos los efectos legales del pagaré, el suscriptor constituye domicilio especial en las ciudades de Talca y/o Linares, según donde se firme el presente pagaré y se someten a la jurisdicción de sus tribunales de justicia.-";
	
	$pdf->Ln(30);
	$pdf->SetFont('Times','',12);
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	//linea 1
	$pdf->Cell(7,$alto_celda,"En ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(17,$alto_celda,$W_sede,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(5,$alto_celda," a ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(7,$alto_celda,$W_dia,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(7,$alto_celda," de ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(23,$alto_celda,$W_mes,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(9,$alto_celda," del ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(12,$alto_celda,$W_year,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(8,$alto_celda," yo, ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(100,$alto_celda,$W_nombre_completo_alumno,$borde,1,"L");
	//linea 2
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,", nacionalidad ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,$W_nacionalidad,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,", estado civil, ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(17,$alto_celda,$W_estado_civil,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(60,$alto_celda,", Cedula Nacional de Identidad N° ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(27,$alto_celda,$W_rut_alumno,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(21,$alto_celda,"domiciliado",$borde,1,"R");
	//linea 3
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(7,$alto_celda,"en ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(126,$alto_celda,$W_direccion_completa_alumno,$borde,0,"L");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,", Telefono ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,$W_telefono_1,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(17,$alto_celda,", y/o ",$borde,1,"C");
	//linea 4
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,$W_telefono_2,$borde,0,"L");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(170,$alto_celda,", debo y pagaré a la orden de CENTRO DE FORMACION TECNICA MASSACHUSETTS Ltda,",$borde,1,"L");
	//linea 5
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(60,$alto_celda,"Rut N° 89.921.100-6, la suma de $ ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(22,$alto_celda,$W_valor_arancel,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(45,$alto_celda,"por concepto de Arancel ",$borde,0,"L"); 
	
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(38,$alto_celda,$W_concepto_pago,$borde,0,"C"); 
	
	//linea 6
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(30,$alto_celda,"dicha suma o ",$borde,1,"L"); 
	//linea 7
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(195,$alto_celda,"capital será pagado en el domicilio de CFT MASSACHUSETTS LIMITADA, calle tres sur N° 1068, Talca y/o",$borde,1,"L"); 
	//linea 8
	 
	 if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(150,$alto_celda,"calle O'higgins N° 313, Linares, según la comuna en que se suscriba el presente pagaré,",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(10,$alto_celda,$W_numero_cuotas,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(35,$alto_celda,"Cuotas mensuales,",$borde,1,"L");
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(45,$alto_celda,"iguales y sucesivas de $",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,$W_valor_cuota,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(75,$alto_celda,"cada una, que el deudor cancelara los dias ",$borde,0,"L"); 
	
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(10,$alto_celda,$W_dia_vencimiento_cuota,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(45,$alto_celda,"de cada mes, venciendo ",$borde,1,"L"); //la primera de ellas el dia
	//linea 9
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(45,$alto_celda,"la primera de ellas el dia",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,$W_primer_vencimiento_cuota,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(33,$alto_celda,"y la ultima, el dia",$borde,0,"L"); 
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,$W_ultimo_vencimiento_cuota,$borde,0,"C"); 
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(67,$alto_celda,", según consta en el contrato de",$borde,1,"L"); 
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(57,$alto_celda,"prestacion de servicios.-",$borde,1,"L"); 
	
	//$pdf->Ln();
	$pdf->MultiCell(195,$alto_celda, $parrafo, $borde, "L", false);
	$pdf->Ln();
	//linea 10
	$pdf->Cell(7,$alto_celda,"En ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(17,$alto_celda,$W_sede,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(5,$alto_celda," a ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(7,$alto_celda,$W_dia,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(7,$alto_celda," de ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(23,$alto_celda,$W_mes,$borde,0,"C");
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(9,$alto_celda," del ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(12,$alto_celda,$W_year,$borde,1,"C");
	//linea 11
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Ln();
	$pdf->SetX(140);
	$pdf->MultiCell(65,$alto_celda, "_____________________________ Firma del Suscriptor o Deudor", $borde, "C", false);
	
	//linea 12
	$pdf->Ln();
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,"Deudor ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(131,$alto_celda,$W_F_deudor,$borde,1,"L");
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,"Direccion ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(131,$alto_celda,$W_F_direccion,$borde,1,"L");
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,"Rut ",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(25,$alto_celda,$W_rut_alumno,$borde,0,"C");
	
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(20,$alto_celda,"Ciudad",$borde,0,"L");
	if($VER_DATOS){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->Cell(86,$alto_celda,$W_F_ciudad,$borde,1,"L");
	//------------------------------------------------//
	$pdf->Ln();
	if($VER_PLANTILLA){$pdf->SetTextColor(0,0,0);}
	else{$pdf->SetTextColor(255,255,255);}
	$pdf->SetFont('Times','',10);
	$pdf->Cell(195,4,"NOTARIO",$borde,1,"L");
	$pdf->SetFont('Times','',9);
	$pdf->Cell(195,4,"Autorizo la(s) firma(s) puesta(s) en este documento por:",$borde,1,"L");
	$pdf->Cell(195,4,"El Impuesto de Timbres y Estampillas que grava a este documento se paga por ingresos de dinero en Tesorería, según Decreto Ley N°3.475",$borde,0,"L");
	

	
	//fin documento
	mysql_close($conexion);
	$pdf->Output();
	
	mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{
	if(DEBUG){echo"Sin Acceso<br>";}
	else{header("location: opciones_finales.php?error=1");}
}	

?>	