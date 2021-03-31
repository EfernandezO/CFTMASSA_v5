<?php
//--------------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
////////////////////////////////////////////////////
	$continuar=false;
	$institucion="C.F.T. Massachusetts";
//////////////////////////////////////////////////////	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))	
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{$continuar=true;}
}

if($continuar)
{
			include("../../../../funciones/conexion_v2.php");
			include("../../../../funciones/funcion.php");
			include("../../../libreria_publica/fpdf/fpdf.php");
			include("../../../../funciones/funciones_sistema.php");
			
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		if(DEBUG){ echo"fecha hora actual . $fecha_hora_actual<br>";}
		
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
		$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
		$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
		$sexo_alumno=strtoupper($_SESSION["SELECTOR_ALUMNO"]["sexo"]);
		$situacion_academica_alumno=strtoupper($_SESSION["SELECTOR_ALUMNO"]["situacion"]);
	////-----------------------------------------------/////
		if($_POST)
		{
			if(DEBUG){ var_dump($_POST);}
			$id_solicitud=$_POST["id_solicitud"];
			$observacion=$_POST["presentado"];
		}
		elseif($_GET)
		{
			if(DEBUG){ var_dump($_GET);}
			$id_solicitud=$_GET["id_solicitud"];
			$cons_s="SELECT observacion FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
			$sql_s=mysql_query($cons_s)or die(mysql_error());
				$Ds=mysql_fetch_assoc($sql_s);
				$observacion=$Ds["observacion"];
			mysql_free_result($sql_s);	
		}
	///---------------------------------------------------///
		///busco si ya se ha generado certificado y obtengo CODIGO GENERACION
		$cons_c="SELECT COUNT(id) FROM registro_certificados WHERE id_solicitud='$id_solicitud'";
	$sql_c=mysql_query($cons_c)or die(mysql_error());
		$Dc=mysql_fetch_row($sql_c);
		$num_certificados=$Dc[0];
		if(empty($num_certificados)){ $num_certificados=0;}
		if(DEBUG){ echo"$cons_c<br>NUM: $num_certificados<br>";}
	mysql_free_result($sql_c);	
		//////////////////////////////////////////////
		if($num_certificados>0)
		{
			if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>";}
			$cons_certificado="SELECT * FROM registro_certificados WHERE id_solicitud='$id_solicitud' LIMIT 1";
			$sql_certificados=mysql_query($cons_certificado)or die(mysql_error());
				$D_certificado=mysql_fetch_assoc($sql_certificados);
					$CODIGO_GENERACION=$D_certificado["codigo_generacion"];
					$array_fecha_hora_creacion_certificado=explode(" ",$D_certificado["fecha_hora"]);
					if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>CODIGO: $CODIGO_GENERACION<br>Fecha: ".$array_fecha_hora_creacion_certificado[0];}
				$fecha=fecha($array_fecha_hora_creacion_certificado[0]);
				mysql_free_result($sql_certificados);	
				
		}
		else
		{
			if(DEBUG){ echo"NO EXISTE CERTIFICADO <br>";}
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("certificado de alumno regular",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ mysql_query($cons_UP_S)or die(mysql_error());}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
	
	//-----------DATOS CARRERA------------------//
	$cons_car="SELECT * FROM certificados WHERE id_carrera = '$id_carrera' AND sede ='$sede_alumno'";
	$sql_car=mysql_query($cons_car);
	$DC = mysql_fetch_assoc($sql_car);
    	$decreto=$DC["decreto"];
	mysql_free_result($sql_car);
	//-----------------------------------------//
	//-----------DATOS FIRMA------------------//
		switch($sede_alumno)
		{
			case"Talca":
				$firma="Osvaldo Acevedo Gutierrez";
				break;
			case"Linares":
				$firma="Nibaldo Benavides Moreno";
				break;
		}
		$cargo="Director Académico";
	//-----------------------------------------//
	//AÑO INGRESO EGRESO
	$cons_A="SELECT ingreso, year_egreso FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
	$sql_A=mysql_query($cons_A)or die(mysql_error());
		$DA=mysql_fetch_assoc($sql_A);
		$year_ingreso_alumno=$DA["ingreso"];
		$year_egreso_alumno=$DA["year_egreso"];
	mysql_free_result($sql_A);	
	//--------------------------------------------//
	//PROCESO titulacion practica
	$cons_PT="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' LIMIT 1";
		$sql_PT=mysql_query($cons_PT)or die(mysql_error());
		$DPT=mysql_fetch_assoc($sql_PT);
		$practica_condicion=$DPT["practica_condicion"];
	mysql_free_result($sql_PT);	
	
	if($practica_condicion=="aprobada")
	{ $mostrar_msj_practica=true;}
	else
	{ $mostrar_msj_practica=false;}
	//----------------------------------------------//
	//numero de horas
	$cons_h_T="SELECT SUM(horas_teoricas) FROM mallas WHERE id_carrera='$id_carrera'";
	$cons_h_P="SELECT SUM(horas_practicas) FROM mallas WHERE id_carrera='$id_carrera'";
	
	$sql_ht=mysql_query($cons_h_T)or die("Teorica".mysql_error());
	$sql_hp=mysql_query($cons_h_P)or die("Practica".mysql_error());
	
	$DHT=mysql_fetch_row($sql_ht);
	$DHP=mysql_fetch_row($sql_hp);
	
	$numero_horas_teoricas=$DHT[0];
	$numero_horas_practicas=$DHP[0];
	
	if(empty($numero_horas_teoricas)){ $numero_horas_teoricas=0;}
	if(empty($numero_horas_practicas)){ $numero_horas_practicas=0;}
	
	mysql_free_result($sql_ht);
	mysql_free_result($sql_hp);
	//----------------------------------------------//
			////definicion de parametros
			$logo="../../../BAses/Images/logoX.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=5;//separacion entre parrafos
			
			
			$borde=0;
			
			$letra_1=14;
			$autor="ACX";
			$titulo="Certificado Plan Curricular";
			$zoom=50;	
			//inicializacion de pdf
			$pdf=new FPDF('P','mm','letter');
			//$pdf->AddFont('Allegro','','ALLEGRO.php');
			//$pdf->AddFont('ChopinScript','','CHOPS.php');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
			
			$pdf->SetAutoPageBreak(false, 100);
////////////////////
		$encabezado=$sede_alumno." ".$fecha;
		///////
		//semestre año actual
		$year_actual=date("Y");
		$mes_actual=date("m");
		
		if($mes_actual>=8)
		{ $semestre_label="Segundo Semestre";}
		else
		{ $semestre_label="Primer Semestre";}
		
		
	//--parrafos--//
	$parrafo_1=$firma.', '.$cargo.' del Centro de Formación Técnica, Massachusetts, Certifica:';
	//---------------------------------------------------------------------------------------------//
	
	
	//----------------------------------------------------------------------------------------------//
	switch($sexo_alumno)
	{
		case"M":
			switch($situacion_academica_alumno)
			{
				case"EG":
					$situacion_academica_label="Egresado";
					break;
				case"T":
					$situacion_academica_label="Titulado";
					break;	
			}
			$parrafo_1b='Que, el Señor: '.$nombre_alumno." ".$apellido_alumno.', Run '.$rut_alumno.', es alumno '.$situacion_academica_label.' de esta Casa de Estudios Superiores en la carrera de '.$carrera_alumno.".";
			$parrafo_2="Que, el señor ".$apellido_alumno." cursó los cuatro niveles de la carrera antes señalada entre los años ".$year_ingreso_alumno." y ".$year_egreso_alumno.".";
			$parrafo_5='Se extiende el presente certificado a solicitud del interesado para ser presentado a '.$observacion;
			break;
		case"F":
			switch($situacion_academica_alumno)
			{
				case"EG":
					$situacion_academica_label="Egresada";
					break;
				case"T":
					$situacion_academica_label="Titulada";
					break;	
			}
			$parrafo_1b='Que, la Señorita: '.$nombre_alumno." ".$apellido_alumno.', Run '.$rut_alumno.', es alumna '.$situacion_academica_label.' de esta Casa de Estudios Superiores en la carrera de '.$carrera_alumno.".";
			$parrafo_2="Que, la señorita ".$apellido_alumno." cursó los cuatro niveles de la carrera antes señalada entre los años ".$year_ingreso_alumno." y ".$year_egreso_alumno.".";
			$parrafo_5='Se extiende el presente certificado a solicitud del la interesada para ser presentado a '.$observacion;
			break;
	}
	
	if($mostrar_msj_practica)
	{
		$parrafo_3="Además, realizó su Práctica Laboral, correspondiente al Quinto Nivel, cabe señalar que esta carrera tiene una duración de dos años y medio (cinco semestres).";
	}
	else
	{
		$parrafo_3="Quedando pendiente su Práctica Laboral, cabe señalar que esta carrera tiene una duración de dos años y medio (cinco semestres).";
	}
	
	$parrafo_4='Que, su funcionamiento como "Centro de Formación Técnica" fue aprobado por Decreto Exento N° 29 de 02 de febrero de 1983, inscrito en el Registro correspondiente bajo el N° 77,';
	
	$parrafo_4.=' la carrera fue aprobada por el Mineduc en '.$decreto;
	
	
	
	
	//---------INICIO ESCRITURA---------//
		///logo
		//$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(195,6,$sede_alumno.", ".$fecha.".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','',24);
		$pdf->Cell(195,15,$titulo,$borde,1,'C');
		$pdf->Ln();
		$pdf->SetFont('Times','',16);
		//parrafo 1
		//$pdf->SetFont('ChopinScript','',$letra_1);
		$pdf->SetFont('Times','',$letra_1);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_1,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_1b,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_2,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_3,$borde);
		//-----------------------------------------------------//
		
		$Y_actual=$pdf->GetY();
		$pdf->SetXY(50,$Y_actual + $salto_Y);
		$pdf->Cell(120,6,"EL Plan Curricular se desglosa de la siguiente manera:",1,1,"C");
		$pdf->SetX(50);
		$pdf->Cell(60,6,"4 Semestres Academicos",1,0,"C");
		$pdf->Cell(60,6,$numero_horas_teoricas." horas",1,1,"C");
		$pdf->SetX(50);
		$pdf->Cell(60,6,"Practica Profesional",1,0,"C");
		$pdf->Cell(60,6,$numero_horas_practicas." horas",1,1,"C");
		$pdf->SetX(50);
		$pdf->Cell(60,6,"Total Plan de Estudios ",1,0,"C");
		$pdf->Cell(60,6,($numero_horas_teoricas+$numero_horas_practicas)." horas",1,1,"C");
		
		
		//----------------------------------------------------//
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_4,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_5,$borde);
		$Y_actual=$pdf->GetY();
		$x_actual=$pdf->GetX();
		
		$pdf->SetFont('Times','',16);
		$pdf->SetY(230);
		$pdf->SetX($x_actual + 125);
		$pdf->MultiCell(70,6,$firma,$borde,"C");
		$pdf->SetX($x_actual + 125);
		$pdf->MultiCell(70,6,$cargo,$borde,"C");
		$pdf->SetX($x_actual + 125);
		$pdf->MultiCell(70,6,$institucion,$borde,"C");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		
		
		$pdf->Text($x_actual,250,"*Cod.".$CODIGO_GENERACION."*");
		
		/////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificado Alumno Regular X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		mysql_close($conexion);
		$pdf->Output();
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////

?> 