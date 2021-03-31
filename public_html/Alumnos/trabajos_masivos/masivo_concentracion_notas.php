<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	define("YEAR_CONSULTA",2014);/////////////establecer
if($_GET)
{
	$nombre_archivo=base64_decode($_GET["archivo"]);
	if(!empty($nombre_archivo))
	{
		$directorio="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
		if(DEBUG){ echo"Archivo A Procesar: $nombre_archivo<br>";}
		$archivo_a_procesar=$directorio.$nombre_archivo;
////////////////////////////////
//LECTURA DE EXCEL
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../libreria_publica/PHPExcel-1.7.7/Classes/');
/** PHPExcel_IOFactory */
include('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
///////////////////////////////////////////////////////////////
//fin LECTURA EXCEL
/////////////////////
//comienza escritura archivo

	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require('../../libreria_publica/fpdf/mc_table.php');
		//////////////////////////
	////definicion de parametros
	$logo="../../BAses/Images/logo_cft.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	$borde=0;
	$letra_1=12;
	$autor="ACX";
	$titulo="INFORME DE CONCENTRACION DE NOTAS";
	$zoom=50;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	//inicializacion de pdf
	$pdf=new PDF_MC_Table('P','mm',"Letter");
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);


	
	 /////Registro ingreso///
	 include("../../../funciones/VX.php");
	
	
if(isset($sheetData))
{
	
	foreach($sheetData as $fila => $array_columnas)
	{
		if(isset($array_columnas["A"])){$COLUMNA_A=$array_columnas["A"];}
		else{ $COLUMNA_A=NULL;}
		
		
		if(isset($array_columnas["B"])){$COLUMNA_B=strtoupper($array_columnas["B"]);}
		else{$COLUMNA_B=NULL;}
		
		if(DEBUG){var_dump($COLUMNA_A); var_dump($COLUMNA_B);}
		

		if((empty($COLUMNA_B))and(!empty($COLUMNA_A)))
		{
			if(DEBUG){ echo"Solo Columna A con datos<br>";}
			$aux_rut=strip_tags($COLUMNA_A);
			$continuar=true;
			//veo ai tiene o no -
			
			if(strpos($aux_rut,"-")){if(DEBUG){ echo"Rut Con DV<br>";}}
			else
			{ 
				if(DEBUG){ echo"Rut sin DV<br>";}
				$aux_dv=validar_rut($aux_rut);
				
				$aux_rut.="-".$aux_dv;
			}
			
		}
		else
		{
			if(DEBUG){echo"$fila -> $COLUMNA_A - $COLUMNA_B :";}
			$aux_rut=str_inde($COLUMNA_A,"")."-".str_inde($COLUMNA_B,"");
			
			if((is_numeric($COLUMNA_A))and(is_string($COLUMNA_B)))
			{ $continuar=true;}
			else{ $continuar=false;}
		}
		
		////--------------------------------------------/////
		if($continuar)
		{
			$cons_1="SELECT * FROM alumno WHERE rut='$aux_rut' ORDER by id, situacion desc";
			if(DEBUG){ echo"-->$cons_1<br>";}
			$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			$num_registros_encontrados=$sql_1->num_rows;
			if($num_registros_encontrados>0)
			{
				while($D_1=$sql_1->fetch_assoc())
				{
					
					$A_id=$D_1["id"];
					$A_situacion=strtoupper($D_1["situacion"]);
			
					$A_nombre=$D_1["nombre"];
					$A_apellido_P=$D_1["apellido_P"];
					$A_apellido_M=$D_1["apellido_M"];
					$A_carrera=$D_1["carrera"];
					$A_id_carrera=$D_1["id_carrera"];
					$A_nivel=$D_1["nivel"];
					$A_sede=$D_1["sede"];
					$A_ingreso=$D_1["ingreso"];
					$A_jornada=$D_1["jornada"];
					///logo
					
					
						$pdf->AddPage();
						$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
						$pdf->SetFont('Arial','B',16);
						$pdf->Cell(50,25,"",$borde,0,"C");
						
						$pdf->Cell(145,25,$titulo,$borde,1,'L');
						
						$pdf->Ln(5);
						$pdf->SetFont('Arial','',12);
						
						$texto_1="Cualquier error u omisión en las calificaciones o en los años cursados, debe presentar su solicitud de corrección a la secretaria de la carrera. Señor(ita) ".utf8_decode(ucwords(strtolower($A_nombre))." ".ucwords(strtolower($A_apellido_P))." ".ucwords(strtolower($A_apellido_M))).", alumno de la carrera ".utf8_decode($A_carrera).", Ud. obtuvo las siguientes calificaciones:";
						
						$pdf->MultiCell(195,6,$texto_1,$borde,"J");
						$pdf->Ln();
						//tabla
						$pdf->SetFont('Arial','B',12);
						$pdf->Cell(15,6,"Cod",1,0,'C');
						$pdf->Cell(120,6,"Asignatura",1,0,'L');
						$pdf->Cell(15,6,"Nivel",1,0,'C');
						$pdf->Cell(25,6,"Periodo",1,0,'C');
						$pdf->Cell(20,6,"Nota",1,1,'R');
						$pdf->SetFont('Arial','',10);
						
						//datos alumno
								
								
								//------------------------------------------------------//
								 $evento="Emision Informe Notas pdf (Masivo) a alumno id_alumno: $A_id id_carrera: $A_id_carrera";
								 REGISTRA_EVENTO($evento);
								///////////////////////
								
								$cons_N="SELECT * FROM notas WHERE id_alumno='$A_id' AND id_carrera='$A_id_carrera' order by cod";
							   if(DEBUG){ echo"-->$cons_N<br>";}
							   $sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
							   $num_notas=$sqli_N->num_rows;
							   if($num_notas>0)
							   {
								   $nivel_old=0;
								   $primera_vuelta=true;
								   $cuenta_notas=0;
								   $acumula_nota=0;
								   $pdf->SetFillColor(216,216,216);
									while($N=$sqli_N->fetch_assoc()) 
									{
										$N_id=$N["id"];
										$N_cod=$N["cod"];
										$N_ramo=$N["ramo"];
										$N_nota=$N["nota"];
										$N_nivel=$N["nivel"];
										$N_semeste=$N["semestre"];
										$N_year=$N["ano"];
										
										if($primera_vuelta){ $primera_vuelta=false;}
										else
										{
											if($N_nivel!=$nivel_old)
											{
												$pdf->SetFont('Arial','B',10);
												if($cuenta_notas>0){$promedio=($acumula_nota/$cuenta_notas);}
												else{ $promedio=0;}
												$pdf->Cell(175,6,"Promedio",1,0,'L', true);
												$pdf->Cell(20,6,number_format($promedio,1,",","."),1,1,'R',true);
												$cuenta_notas=0;
												$acumula_nota=0;
												$pdf->SetFont('Arial','',10);
											}
										}
										
										
										
										if(empty($N_ramo)){ $mostrar_registro_1=false;}
										else{ $mostrar_registro_1=true;}
										
										if($mostrar_registro_1)
										{
											if(!empty($N_nota))
											{ 
												$cuenta_notas++;
												$acumula_nota+=$N_nota;
											}
											$pdf->SetAligns(array("C","L","C","C","R"));
											$pdf->SetWidths(array(15,120,15,25,20));
											$pdf->Row(array($N_cod,utf8_decode($N_ramo),$N_nivel, "$N_semeste - $N_year", $N_nota));
										}
										$nivel_old=$N_nivel;
									}
								}
							   else
							   { $pdf->Cell(195,6,$aux_rut."--> ALUMNO Sin Registro academico Creado",$borde,1);}
							   
							   $pdf->SetFillColor(216,50,50);
							   
							   if($A_situacion!=="V"){ $pdf->Cell(195,6,$aux_rut."--> ALUMNO, en condicion $A_situacion",$borde,1,"L",true);}
					
						   $sqli_N->free();
				}//fin while
			}
			else
			{
				if(DEBUG){echo"$aux_rut -> Alumno No encontrado en el Sistema<br>";}
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',18);	
				$pdf->Cell(195,6,$aux_rut."--> ALUMNO No encontrado en el Sistema",$borde,1);
				
			}
			$sql_1->free();
		}//fin si continuar
		else
		{
			if(DEBUG){echo"$aux_rut -> Rut Incorrecto<br>";}
			
		}
		
	}//FIN FOREACH
	
	$conexion_mysqli->close();
	@mysql_close($conexion);
	if(!DEBUG){$nombre_archivo="Concentracion_de_notas_masivo_pdf"; $pdf->Output($nombre_archivo, "I");}
}
else
{
	//sin datos
}
//////////////////////////////////////////////////////
	
}
else
{ echo"Sin Archivo Fuente Enviado...<br>";}
}
else
{
	header("location: index.php");
}
/////////////////////////////////////

?>