<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->asignaturas_pendientes");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	$continuar_1=false;
	if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
	{
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $continuar_1=true;}
	}

if($continuar_1)
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require('../../libreria_publica/fpdf/mc_table.php');
	
	//---------------------------------------------//
	include("../../../funciones/VX.php");
	$evento="Imprime informe Asignaturas Pendientes Alumno id_alumno: $id_alumno id_carrera: $id_carrera";
	REGISTRA_EVENTO($evento);
	//---------------------------------------------//
	
	$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_A)or die("Alumno".$conexion_mysqli->error);
	$A=$sqli_A->fetch_assoc();
		$A_nivel=$A["nivel"];
		$A_year_ingreso=$A["ingreso"];
		$A_nombre=$A["nombre"];
		$A_apellido_P=$A["apellido_P"];
		$A_apellido_M=$A["apellido_M"];
		$A_carrera=$A["carrera"];
		$A_rut=$A["rut"];
		$A_fono=$A["fono"];
		$A_direccion=$A["direccion"];
		$A_ciudad=$A["ciudad"];
		$A_jornada=$A["jornada"];
		$A_grupo=$A["grupo"];
		$A_fecha_nacimiento=$A["fnac"];
		$A_fecha_registro=$A["fecha_registro"];
		
		$A_liceo=$A["liceo"];
		$A_liceo_ciudad=$A["liceo_ciudad"];
		$A_liceo_year_egreso=$A["liceo_egreso"];
		
		$A_apoderado=$A["apoderado"];
		$A_rut_apoderado=$A["rut_apoderado"];
		$A_direccion_apoderado=$A["direccion_apoderado"];
		$A_ciudad_apoderado=$A["ciudad_apoderado"];
		$A_fono_Apoderado=$A["fonoa"];
	$sqli_A->free();	
	
	if(($A_fecha_registro=="NULL")or(empty($A_fecha_registro))){$fecha_registro_label=$A_year_ingreso;}
	else{ $fecha_registro_label=$A_fecha_registro;}
	
	if($A_jornada=="D"){ $jornada_label="Diurno";}
	else{ $jornada_label="Vespertino";}	
	
	$logo="../../BAses/Images/logo_cft.jpg";
	$fecha_actual=fecha();
	$y_firmas=250;
	$borde=1;
	$borde_p=0;
	$letra_1=12;
	$letra_2=10;
	$fecha=fecha();
	$autor="ACX";
	$titulo="Asignaturas Pendientes";
	$zoom=50;
	$pdf=new PDF_MC_Table('P','mm',"Letter");
	
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	//arreglo variables
	

	//*************
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	$pdf->SetFont('Arial','B',16);
	//titulo
	$pdf->Ln(7);
	$pdf->Cell(195,6,$titulo,$borde*0,1,'C');
	
	$pdf->Ln(15);
	//***************************************************
	//datos Alumno
	$pdf->SetFont('Arial','B',$letra_2);
	 $pdf->SetFillColor(216,216,216);
	$pdf->Cell(195,6,"Datos Personales (Alumno)",$borde,1,'L',true);
	//id matricula
	//C.I.
	$pdf->Cell(40,6,"Cedula de Identidad ",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_rut)),$borde,1,'L');
	//nombre
	$pdf->Cell(40,6,"Nombre",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_nombre." ".$A_apellido_P." ".$A_apellido_M))),$borde,1,'L');
	//fecha nac
	//****************************************************************
	//datos academicos
	//carrera
	$pdf->Cell(40,6,"Carrera",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_carrera))),$borde,1,'L');
	//jornada
	$pdf->Cell(40,6,"Jornada",$borde,0,'L');
	

	$pdf->Cell(155,6,ucwords(strtolower($jornada_label)),$borde,1,'L');
	////nivel
	$pdf->Cell(40,6,"Nivel",$borde,0,'L');
	$pdf->Cell(40,6,ucwords(strtolower($A_nivel)),$borde,0,'L');
	//grupo
	$pdf->Cell(40,6,"Grupo",$borde,0,'L');
	$pdf->Cell(75,6,ucwords(strtolower($A_grupo)),$borde,1,'L');
	$pdf->Ln();
	//*****************************************************************
	///logo
	$borde=0;

	//tabla
	$pdf->SetFont('Arial','B',12);
	 $pdf->SetFillColor(216,216,216);
	$pdf->Cell(195,6,"Asignaturas",1,1,'C', true);
	
	$pdf->Cell(15,6,"Cod",1,0,'C');
	$pdf->Cell(120,6,"Asignatura",1,0,'L');
	$pdf->Cell(15,6,"Nivel",1,0,'C');
	$pdf->Cell(25,6,"Periodo",1,0,'C');
	$pdf->Cell(20,6,"Nota",1,1,'R');
	$pdf->SetFont('Arial','',10);

		 
	$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND nivel<'$A_nivel' AND ramo<>''  AND (nota<'4' OR nota='0') order by cod";
   if(DEBUG){ echo"-->$cons_N<br>";}
   $sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
   $num_notas=$sqli_N->num_rows;
   if($num_notas>0)
   {
	  
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
			
			
				$pdf->SetAligns(array("C","L","C","C","R"));
				$pdf->SetWidths(array(15,120,15,25,20));
				$pdf->Row(array($N_cod,utf8_decode($N_ramo),$N_nivel, "$N_semeste - $N_year", $N_nota));
			
		}
	}
   else
   { $pdf->Cell(195,6,"Sin Asignaturas Pendientes",1,1,'C');}
	   
   	$pdf->Ln();
	 $pdf->Cell(195,6,"Impreso el ".$fecha_actual,$borde_p,0,'C');
	@mysql_close($conexion);	 
	$conexion_mysqli->close();
	if(!DEBUG){$nombre_archivo="Informe_general_alumno_".$A_rut.".pdf"; $pdf->Output($nombre_archivo, "I");}
}
else
{
	if(DEBUG){ echo"Sin Acceso<br>";}
	else{header("../../buscador_alumno_BETA/HALL/index.php");}
}
?>