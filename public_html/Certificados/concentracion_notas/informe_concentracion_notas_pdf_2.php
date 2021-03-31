<?php
#error_reporting(E_ALL);
#ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->concentracion_de_notas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$hay_post=false;
$ver_logo=false;
if($_POST)
{
	if(isset($_POST["firma"]))
	{
		$cargo="Director Academico";	
		$firma=$_POST["firma"];
		$hay_post=true;
		
	}
	$ver_logoX=$_POST["ver_logo"];
	if($ver_logoX=="si"){$ver_logo=true;}
	else{ $ver_logo=false;}
	
}
//var_dump($_POST);

if((isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))and($hay_post))
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
	////-----------------------------------------------/////
	
		
		//---------------------------------------//
	$cons_B="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql_B=$conexion_mysqli->query($cons_B);
	$DX=$sql_B->fetch_assoc();
		$A_nombre=$DX["nombre"];
		$A_apellido_P=$DX["apellido_P"];
		$A_apellido_M=$DX["apellido_M"];
		$A_rut=$DX["rut"];
		$A_year_ingreso=$DX["ingreso"];
		$A_year_egreso=$DX["year_egreso"];
		$A_carrera=$DX["carrera"];
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
	$pdf->SetAutoPageBreak(false, 10);


	///logo

	if($ver_logo){$pdf->image($logo,14,10,30,24,'jpg');} //este es el logo
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(50,25,"",$borde,0,"C");
	
	$pdf->Cell(145,25,$titulo,$borde,1,'L');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',12);
	
	$texto_1="$firma, $cargo del C.F.T. Massachusetts de $sede_alumno , reconocido por el Ministerio de Educación el 3 de febrero de 1983, según Decreto Exento N° 29, certifica que el señor(ita) $A_nombre $A_apellido_P $A_apellido_M, alumno de la carrera $A_carrera ingreso $yearIngresoCarrera, obtuvo las siguientes calificaciones";
	
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
	 $evento="Emision Concentracion de Notas pdf a alumno id_alumno: $id_alumno id_carrera: $id_carrera";
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
   {$pdf->Cell(195,6,"Sin Registro Academico Creado",1,1,'R');}
	 
	 $pdf->Ln();
	 $pdf->SetX(145);
	 $pdf->Multicell(60,6,"_____________________________ ".$firma,0,"C");
	
	 
	@mysql_close($conexion);	 
	$conexion_mysqli->close();
	if(!DEBUG){$nombre_archivo="Concentracion_de_notas_".$A_rut.".pdf"; $pdf->Output($nombre_archivo, "I");}
}
else
{
	header("location : index.php");
}

?>