<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->informe_de_notas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	/////////////////////////
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require('../../libreria_publica/fpdf/mc_table.php');
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	////////////////////////
	$cons_B="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql_B=$conexion_mysqli->query($cons_B);
	$DX=$sql_B->fetch_assoc();
		$A_nombre=$DX["nombre"];
		$A_apellido_P=$DX["apellido_P"];
		$A_apellido_M=$DX["apellido_M"];
		$A_rut=$DX["rut"];
	$sql_B->free();
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
	$pdf->AddPage();
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);


	///logo
	
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(50,25,"",$borde,0,"C");
	
	$pdf->Cell(145,25,$titulo,$borde,1,'L');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',12);
	
	$texto_1="Cualquier error u omisión en las calificaciones o en los años cursados, debe presentar su solicitud de corrección a la secretaria de la carrera. Señor(ita) ".ucwords(strtolower($A_nombre))." ".ucwords(strtolower($A_apellido_P))." ".ucwords(strtolower($A_apellido_M)).", alumno de la carrera $carrera_alumno ingreso $yearIngresoCarrera, Ud. obtuvo las siguientes calificaciones:";
	
	$pdf->MultiCell(195,6,utf8_decode($texto_1),$borde,"J");
	$pdf->Ln();
	//tabla
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(15,6,"Cod",1,0,'C');
	$pdf->Cell(120,6,"Asignatura",1,0,'L');
	$pdf->Cell(15,6,"Nivel",1,0,'C');
	$pdf->Cell(25,6,"Periodo",1,0,'C');
	$pdf->Cell(20,6,"Nota",1,1,'R');
	$pdf->SetFont('Arial','',10);
	 /////Registro ingreso///
	 include("../../../funciones/VX.php");
	 $evento="Emision Informe Notas pdf a alumno id_alumno: $id_alumno id_carrera: $id_carrera";
	 REGISTRA_EVENTO($evento);
	///////////////////////
		 
	$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND es_asignatura='1' order by cod";
   if(DEBUG){ echo"-->$cons_N<br>";}
   $sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
   $num_notas=$sqli_N->num_rows;
   if($num_notas>0)
   {
	   $nivel_old=0;
	   $primera_vuelta=true;
	   $cuenta_notas=0;
	   $acumula_nota=0;
	   
	   $acumula_promedio=0;
	   $cuenta_promedio=0;
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
			$N_condicion=$N["condicion"];
			
			if($primera_vuelta){ $primera_vuelta=false;}
			else
			{
				if($N_nivel!=$nivel_old)
				{
					$pdf->SetFont('Arial','B',10);
					if($cuenta_notas>0){$promedio=round(($acumula_nota/$cuenta_notas),1); $cuenta_promedio++; $acumula_promedio+=$promedio;}
					else{ $promedio=0;}
					$pdf->Cell(175,6,"Promedio",1,0,'L', true);
					$pdf->Cell(20,6,number_format($promedio,1,",","."),1,1,'R',true);
					$cuenta_notas=0;
					$acumula_nota=0;
					$pdf->SetFont('Arial','',10);
				}
			}
			
			
			
			if((empty($N_ramo))or ($N_nivel>4)){ $mostrar_registro_1=false;}
			else{ $mostrar_registro_1=true;}
			
			if($mostrar_registro_1)
			{
				$usarNota=true;
				
				if(empty($N_nota))
				{ $usarNota=false;}
				
				if($N_condicion=="convalidacion"){$N_nota="C"; $usarNota=false;}
				if($N_condicion=="homologacion"){$N_nota="H"; $usarNota=false;}
				
			
				
				if($usarNota){
					$cuenta_notas++;
					$acumula_nota+=$N_nota;
				}
				
				$pdf->SetAligns(array("C","L","C","C","R"));
				$pdf->SetWidths(array(15,120,15,25,20));
				$pdf->Row(array($N_cod,utf8_decode($N_ramo),$N_nivel, "$N_semeste - $N_year", $N_nota));
			}
			$nivel_old=$N_nivel;
		}
		
		$pdf->SetFont('Arial','B',10);
		if(($cuenta_notas>0)and($acumula_nota>0)){$promedio=round(($acumula_nota/$cuenta_notas),1); $cuenta_promedio++; $acumula_promedio+=$promedio;}
		else{ $promedio=0;}
		if($promedio>0){
			$pdf->Cell(175,6,"Promedio",1,0,'L', true);
			$pdf->Cell(20,6,number_format($promedio,1,",","."),1,1,'R',true);
			$cuenta_notas=0;
			$acumula_nota=0;
			$pdf->SetFont('Arial','',10);
		}
	}
   else
   {}
	 
	$conexion_mysqli->close();
	
	//Promedio Final
	$promedio_final=($acumula_promedio/$cuenta_promedio);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(175,6,"Promedio Final",1,0,'L', true);
	$pdf->Cell(20,6,number_format(round($promedio_final,1),1,",","."),1,1,'R',true);
	///
	
	$msj="*Informe impreso el ".date("d-m-Y")." a las ".date("H:i:s")." hrs";
	$pdf->Ln();
	$pdf->cell(195,6, $msj,1,1,"C",true);
	if(!DEBUG){$nombre_archivo="Concentracion_de_notas_".$A_rut.".pdf"; $pdf->Output($nombre_archivo, "I");}
}
else
{
	header("location : index.php");
}

?>