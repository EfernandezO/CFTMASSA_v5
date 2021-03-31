<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->ficha_de_Matricula_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	$A_nivelAcademico=$_SESSION["SELECTOR_ALUMNO"]["nivel_academico"];
	
	$continuar_1=false;
	if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
	{
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $continuar_1=true;}
	}

if($continuar_1)
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_sistema.php");
	require('../../libreria_publica/fpdf/mc_table.php');
	
	//---------------------------------------------//
	include("../../../funciones/VX.php");
	$evento="Imprime Ficha de matricula de Alumno id_alumno: $id_alumno id_carrera: $id_carrera";
	REGISTRA_EVENTO($evento);
	//---------------------------------------------//
	
	$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_A)or die("Alumno".$conexion_mysqli->error);
	$A=$sqli_A->fetch_assoc();
		$A_nivel=$A["nivel"];
		$A_nivel=$A_nivelAcademico;//actualizado
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
		
		
		
		$A_idLiceo=$A["idLiceo"];
		list($L_nombreEstablecimiento, $L_region, $L_comuna)=DATOS_LICEO($A_idLiceo);
		
		$A_liceo=$A["liceo"];
		$A_liceo_ciudad=$A["liceo_ciudad"];
		$A_liceo_year_egreso=$A["liceo_egreso"];
		
		$A_apoderado=$A["apoderado"];
		$A_rut_apoderado=$A["rut_apoderado"];
		$A_direccion_apoderado=$A["direccion_apoderado"];
		$A_ciudad_apoderado=$A["ciudad_apoderado"];
		$A_fono_Apoderado=$A["fonoa"];
		
	$sqli_A->free();	
	$conexion_mysqli->close();
	
	if(($A_fecha_registro=="NULL")or(empty($A_fecha_registro))){$fecha_registro_label=$yearIngresoCarrera;}
	else{ $fecha_registro_label=$A_fecha_registro;}
	
	if($jornada_alumno=="D"){ $jornada_label="Diurno";}
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
	$titulo="Ficha de Matricula";
	$zoom=50;
	$pdf=new FPDF();
	$pdf->AddPage('P','Letter');
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	//arreglo variables
	$largo_mat=strlen($id_alumno);
	$prex='';
	if($largo_mat<6){$diferencia=6-$largo_mat;}
	for($x=1;$x<=$diferencia;$x++)
	{$prex.="0";}
	$id_mat=$prex.$id_alumno;
	//************************

	//*************
	$pdf->image($logo,5,5,35,30,'jpg'); //este es el logo
	//titulo
	$pdf->Ln(7);
	$pdf->SetFont('Arial','U',16);
	$pdf->Cell(195,6,$titulo,$borde*0,1,'C');
	
	$pdf->Ln(15);
	//***************************************************
	//datos Alumno
	$pdf->SetFont('Arial','B',$letra_2);
	 $pdf->SetFillColor(216,216,216);
	$pdf->Cell(195,6,"Datos Personales (Alumno)",$borde,1,'L',true);
	//id matricula
	$pdf->SetFont('Arial','',$letra_2);
	$pdf->Cell(40,6,"Numero de Matricula ",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($id_mat)),$borde,1,'L');
	//C.I.
	$pdf->Cell(40,6,"Cedula de Identidad ",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_rut)),$borde,1,'L');
	//nombre
	$pdf->Cell(40,6,"Nombre",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_nombre." ".$A_apellido_P." ".$A_apellido_M))),$borde,1,'L');
	//fecha nac
	$pdf->Cell(40,6,"Fecha Nacimiento",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_fecha_nacimiento)),$borde,1,'L');
	//fono
	$pdf->Cell(40,6,"Fono",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_fono)),$borde,1,'L');
	//domicilio
	$pdf->Cell(40,6,"Domicilio",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_direccion.", ".$A_ciudad))),$borde,1,'L');
	//****************************************************************
	//datos academicos
	$pdf->SetFont('Arial','B',$letra_2);
	$pdf->Cell(195,6," ",$borde,1,'L');
		$pdf->Ln();
	$pdf->Cell(195,6,"Datos Academicos",$borde,1,'L', true);
	//liceo procedencia
	$pdf->SetFont('Arial','',$letra_2);
	$pdf->Cell(40,6,"Liceo Procedencia",$borde,0,'L');
	$pdf->Cell(155,6,$L_nombreEstablecimiento,$borde,1,'L');
	//ciudad
	$pdf->Cell(40,6,"Ciudad",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($L_region." Region, ".$L_comuna)),$borde,1,'L');
	//ano egreso
	$pdf->Cell(40,6,"A�o Egreso",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_liceo_year_egreso)),$borde,1,'L');
	//carrera
	$pdf->Cell(40,6,"Carrera",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($carrera_alumno))),$borde,1,'L');
	//jornada
	$pdf->Cell(40,6,"Jornada",$borde,0,'L');
	

	$pdf->Cell(155,6,ucwords(strtolower($jornada_label)),$borde,1,'L');
	////nivel
	$pdf->Cell(40,6,"Nivel",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_nivel)),$borde,1,'L');
	//grupo
	$pdf->Cell(40,6,"Grupo",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_grupo)),$borde,1,'L');
	
	//ingreso
	$pdf->Cell(40,6,"Fecha Registro",$borde,0,'L');
	$pdf->Cell(155,6,$fecha_registro_label,$borde,1,'L');
	//*****************************************************************
	//datos apoderado
	$pdf->SetFont('Arial','B',$letra_2);
	$pdf->Cell(195,6," ",$borde,1,'L');
	$pdf->Ln();
	$pdf->Cell(195,6,"Datos del Apoderado",$borde,1,'L', true);
	//nombre
	$pdf->SetFont('Arial','',$letra_2);
	$pdf->Cell(40,6,"Nombre",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_apoderado))),$borde,1,'L');
	//C.I. apoderado
	$pdf->Cell(40,6,"Cedula de Identidad",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_rut_apoderado)),$borde,1,'L');
	//Domicilio apoderado
	$pdf->Cell(40,6,"Domicilio",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_direccion_apoderado.", ".$A_ciudad_apoderado)),$borde,1,'L');
	//Fono apoderado
	$pdf->Cell(40,6,"Fono",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_fono_Apoderado)),$borde,1,'L');
	$pdf->Cell(195,6," ",$borde,1,'L');
	//*****************************************************
	//firmas
	$pdf->SetY($y_firmas);
	$pdf->Cell(40,6,"___________________",$borde_p,0,'C');
	$pdf->Cell(115,6,"",$borde_p,0,'C');
	$pdf->Cell(40,6,"___________________",$borde_p,1,'C');
	
	$pdf->Cell(40,6,"Firma Apoderado",$borde_p,0,'C');
	$pdf->Cell(115,6,"",$borde_p,0,'C');
	$pdf->Cell(40,6,"Firma Alumno",$borde_p,1,'C');
	//fecha
	$pdf->Cell(195,6,$fecha_actual,$borde_p,0,'C');

	$pdf->Output();
}
else
{
	if(DEBUG){ echo"Sin Acceso<br>";}
	else{header("../../buscador_alumno_BETA/HALL/index.php");}
}
?>