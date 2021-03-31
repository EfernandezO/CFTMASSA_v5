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
$ARRAY_MESES=array("01"=>"Enero",
					"02"=>"Febrero",
					"03"=>"Marzo",
					"04"=>"Abril",
					"05"=>"Mayo",
					"06"=>"Junio",
					"07"=>"Julio",
					"08"=>"Agosto",
					"09"=>"Septiembre",
					"10"=>"Octubre",
					"11"=>"Noviembre",
					"12"=>"Diciembre");
					
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

	$fecha=fecha();
	$autor="eliasfernandezo@gmail.com";
	$titulo="PAGARE";
	$zoom=40;
	$largo_folio=5;///agrega "0" antes del folio hasta dejarlo del largo
	
	$hoja_pagare[0]=216;
	$hoja_pagare[1]=300;
	$pdf=new FPDF('P','mm',$hoja_pagare);
	$pdf->AddPage();
	$pdf->SetMargins(10,10,10);
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
			$W_direccion_completa_alumno="_____________________________________________";
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
				$C_year_contrato=$DC["ano"];
				//$C_id_boleta_pagare=$DC["id_boleta_pagare"];
			$sqli_C->free();
			//datos alumno
			$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A);
			$DA=$sqli_A->fetch_assoc();
				$A_nombre=utf8_decode($DA["nombre"]);
				$A_apellido_P=utf8_decode($DA["apellido_P"]);
				$A_apellido_M=utf8_decode($DA["apellido_M"]);
				$A_nacionalidad=$DA["nacionalidad"];
				$A_rut=$DA["rut"];
				$A_direccion=utf8_decode($DA["direccion"]);
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
					{ $W_primer_vencimiento_cuota=fecha_format($CUO_fecha_vence); $primera_vuelta=false;}
				}
				$W_ultimo_vencimiento_cuota=fecha_format($CUO_fecha_vence);
				$W_valor_cuota=$CUO_valor;
				$W_dia_vencimiento_cuota=explode("-",$CUO_fecha_vence);
				$W_dia_vencimiento_cuota=end($W_dia_vencimiento_cuota);
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
					$W_concepto_pago=$C_semestre."° semestre año ".$C_year_contrato;
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
//--------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------//
//			INICIO ESCRITURA					
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------//

	$pdf->SetFont('Times','',12);
	
	
	//primera linea
	$posicion_1=63;
	$posicion_2=70;
	$posicion_3=76;
	$posicion_4=83;
	$posicion_5=89;
	$posicion_6=102;
	$posicion_7=109;
	$posicion_8=115;
	
	$posicion_9=236;
	
	$posicion_10=266;//direccion final
	$posicion_11=272;//direccion final
	$posicion_12=279;
	
	if(DEBUG)
	{for($x=0;$x<=300;$x+=10){$pdf->Text(10,$x,"_____---- $x");}}
	
	$pdf->Text(20,$posicion_1,$W_sede);
	$pdf->Text(42,$posicion_1,$W_dia);
	$pdf->Text(57,$posicion_1,$ARRAY_MESES[$W_mes]);
	$pdf->Text(88,$posicion_1,$W_year);
	$pdf->Text(113,$posicion_1,$W_nombre_completo_alumno);
	
	//$pdf->Cell(7,$alto_celda,$W_dia,$borde,0,"C");
	//$pdf->Cell(23,$alto_celda,$W_mes,$borde,0,"C");
	//$pdf->Cell(12,$alto_celda,$W_year,$borde,0,"C");
	//$pdf->Cell(100,$alto_celda,$W_nombre_completo_alumno,$borde,1,"L");
	
	//segunda linea
	$pdf->Text(38,$posicion_2,$W_nacionalidad);
	$pdf->Text(88,$posicion_2,$W_estado_civil);
	$pdf->Text(179,$posicion_2,$W_rut_alumno);
	//$pdf->Cell(20,$alto_celda,$W_nacionalidad,$borde,0,"C");
	//$pdf->Cell(17,$alto_celda,$W_estado_civil,$borde,0,"C");
	//$pdf->Cell(27,$alto_celda,$W_rut_alumno,$borde,0,"C");
	
	//linea 3
	$pdf->Text(41,$posicion_3,$W_direccion_completa_alumno);
	$pdf->Text(167,$posicion_3,$W_telefono_1);
	//$pdf->Cell(126,$alto_celda,$W_direccion_completa_alumno,$borde,0,"L");
	//$pdf->Cell(25,$alto_celda,$W_telefono_1,$borde,0,"C");
	
	//linea 4
	$pdf->Text(15,$posicion_4,$W_telefono_2);
	//$pdf->Cell(25,$alto_celda,$W_telefono_2,$borde,0,"L");
	
	//linea 5
	$pdf->Text(80,$posicion_5,$W_valor_arancel);
	$pdf->SetFont('Times','',10);
	$pdf->Text(149,$posicion_5,$W_concepto_pago);
	$pdf->SetFont('Times','',12);
	//$pdf->Cell(22,$alto_celda,$W_valor_arancel,$borde,0,"C"); 
	//$pdf->Cell(38,$alto_celda,$W_concepto_pago,$borde,0,"C"); 
	
	//linea 6
	$pdf->Text(165,$posicion_6,$W_numero_cuotas);
	//$pdf->Cell(10,$alto_celda,$W_numero_cuotas,$borde,0,"C"); 
	
	//linea 7
	$pdf->Text(56,$posicion_7,$W_valor_cuota);
	$pdf->Text(155,$posicion_7,$W_dia_vencimiento_cuota);
	//$pdf->Cell(20,$alto_celda,$W_valor_cuota,$borde,0,"C"); 
	//$pdf->Cell(10,$alto_celda,$W_dia_vencimiento_cuota,$borde,0,"C"); 
	
	//linea 8
	$pdf->Text(58,$posicion_8,$W_primer_vencimiento_cuota);
	$pdf->Text(125,$posicion_8,$W_ultimo_vencimiento_cuota);
	//$pdf->Cell(25,$alto_celda,$W_primer_vencimiento_cuota,$borde,0,"C"); 
	//$pdf->Cell(25,$alto_celda,$W_ultimo_vencimiento_cuota,$borde,0,"C"); 
	
	//linea 9
	//$pdf->Cell(17,$alto_celda,$W_sede,$borde,0,"C");
	//$pdf->Cell(7,$alto_celda,$W_dia,$borde,0,"C");
	//$pdf->Cell(23,$alto_celda,$W_mes,$borde,0,"C");
	//$pdf->Cell(12,$alto_celda,$W_year,$borde,1,"C");
	$pdf->Text(25,$posicion_9,$W_sede);
	$pdf->Text(58,$posicion_9,$W_dia);
	$pdf->Text(78,$posicion_9,$ARRAY_MESES[$W_mes]);
	$pdf->Text(122,$posicion_9,$W_year);
	
	//linea 10
	$pdf->Text(42,$posicion_10,$W_F_deudor);
	//$pdf->Cell(131,$alto_celda,$W_F_deudor,$borde,1,"L");
	
	//linea 11
	$pdf->Text(43,$posicion_11,$W_F_direccion);
	//$pdf->Cell(131,$alto_celda,$W_F_direccion,$borde,1,"L");
	
	//linea 12
	$pdf->Text(42,$posicion_12,$W_rut_alumno);
	//$pdf->Cell(25,$alto_celda,$W_rut_alumno,$borde,0,"C");
	
	//linea 13
	$pdf->Text(110,$posicion_12,$W_F_ciudad);
	//$pdf->Cell(86,$alto_celda,$W_F_ciudad,$borde,1,"L");
	//------------------------------------------------//
	//fin documento
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