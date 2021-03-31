<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
	
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
	$evento="Revisa Informe General de Alumno para docentes id_alumno: $id_alumno id_carrera: $id_carrera";
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
	else
	{ $jornada_label="Vespertino";}	
	
	$logo="../../BAses/Images/logo_cft.jpg";
	$fecha_actual=fecha();
	$y_firmas=250;
	$borde=1;
	$borde_p=0;
	$letra_1=12;
	$letra_2=10;
	$fecha=fecha();
	$autor="ACX";
	$titulo="INFORME GENERAL";
	$zoom=75;
	$pdf=new PDF_MC_Table('P','mm',"Letter");
	
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
	$pdf->Cell(155,6,ucwords(strtolower($A_liceo)),$borde,1,'L');
	//ciudad
	$pdf->Cell(40,6,"Ciudad",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_liceo_ciudad)),$borde,1,'L');
	//ano egreso
	$pdf->Cell(40,6,"Año Egreso",$borde,0,'L');
	$pdf->Cell(155,6,ucwords(strtolower($A_liceo_year_egreso)),$borde,1,'L');
	//carrera
	$pdf->Cell(40,6,"Carrera",$borde,0,'L');
	$pdf->Cell(155,6,utf8_decode(ucwords(strtolower($A_carrera))),$borde,1,'L');
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
	$pdf->Cell(40,6,"Fecha Ingreso",$borde,0,'L');
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
//-------------------------------------------------------------------------------------------------------------------//
	$pdf->AddPage();
	///logo
	$borde=0;
	
	$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(60,25,"",0,0,"C");
	
	$pdf->Cell(135,25,$titulo,$borde,1,'L');
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',12);
	
	$texto_1="Cualquier error u omisión en las calificaciones o en los años cursados, debe presentar su solicitud de corrección a la secretaria de la carrera. Señor(ita) ".utf8_decode(ucwords(strtolower($A_nombre)))." ".utf8_decode(ucwords(strtolower($A_apellido_P)))." ".utf8_decode(ucwords(strtolower($A_apellido_M))).", alumno de la carrera ".utf8_decode($A_carrera).", Ud. obtuvo las siguientes calificaciones:";
	
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

		 
	$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' order by cod";
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
   {}
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