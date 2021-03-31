<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(false);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
////////////////////////////////////////////////////
	$continuar=false;
	$institucion="C.F.T. Massachusetts";
	$verQr=true;
	$ver_logo=false;
	$mostrarParrafoPractica=false;
//////////////////////////////////////////////////////	

$continuar=true;
if($continuar)
{
			require("../../../../funciones/conexion_v2.php");
			include("../../../../funciones/funcion.php");
			include("../../../libreria_publica/fpdf/fpdf.php");
			include("../../../../funciones/funciones_sistema.php");
			require("../../../../funciones/class_ALUMNO.php");
			
			
			
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$tipoUsuario=$_SESSION["USUARIO"]["tipo"];
		if(DEBUG){ echo"fecha hora actual . $fecha_hora_actual<br>";}
		
		if($_POST)
		{
			if(DEBUG){ var_dump($_POST);}
			$id_solicitud=$_POST["id_solicitud"];
			$S_observacion=$_POST["presentado"];
			$S_id_firma=$_POST["firma_certificado"];
		}
		elseif($_GET)
		{
			if(DEBUG){ var_dump($_GET);}
			$id_solicitud=$_GET["id_solicitud"];
			$S_observacion="";
			$S_id_firma="";
		}
		//datos de solicitud
		$cons_s="SELECT * FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
			$sql_s=$conexion_mysqli->query($cons_s)or die($conexion_mysqli->error);
				$Ds=$sql_s->fetch_assoc();
				$S_semestre=$Ds["semestre"];
				$S_year=$Ds["year"];
				$S_idAlumno=$Ds["id_receptor"];
				if(empty($S_observacion)){$S_observacion=$Ds["observacion"];}
				if(empty($S_id_firma)){$S_id_firma=$Ds["id_firma"];}
			$sql_s->free();	
		//_________________________________________________	
		//-------------------------------------------------
		//datos del alumno
		
		
		if($tipoUsuario=="funcionario"){
			$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
			$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
			$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
			$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
			$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
			$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
			$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
			$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
			$sexo_alumno=$_SESSION["SELECTOR_ALUMNO"]["sexo"];
			
		}else{
			$ALUMNO=new ALUMNO($S_idAlumno);
			$id_alumno=$S_idAlumno;
			$id_carrera=$ALUMNO->getUltimaIdCarreraMat();
			$nombre_alumno=$ALUMNO->getNombre();
			$apellido_alumno=$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
			$carrera_alumno=NOMBRE_CARRERA($id_carrera);
			$sede_alumno=$ALUMNO->getSedeActual();
			$rut_alumno=$ALUMNO->getRut();
			$yearIngresoCarrera=$ALUMNO->getUltimoYearIngresoMat();
			$sexo_alumno=$ALUMNO->getSexo();
			$ver_logo=true;
		}
		//semestre año actual
		$year_actual=date("Y");
		$mes_actual=date("m");
		
		if($mes_actual>=8)
		{ $semestre_label="Segundo Semestre"; $semestre_actual=2;}
		else
		{ $semestre_label="Primer Semestre"; $semestre_actual=1;}
	///---------------------------------------------------///
		///busco si ya se ha generado certificado y obtengo CODIGO GENERACION
		$cons_c="SELECT COUNT(id) FROM registro_certificados WHERE id_solicitud='$id_solicitud'";
		$sql_c=$conexion_mysqli->query($cons_c)or die($conexion_mysqli->error);
		$Dc=$sql_c->fetch_row();
		$num_certificados=$Dc[0];
		if(empty($num_certificados)){ $num_certificados=0;}
		if(DEBUG){ echo"$cons_c<br>NUM: $num_certificados<br>";}
		$sql_c->free();	
		//////////////////////////////////////////////
		if($num_certificados>0)
		{
			if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>";}
			$cons_certificado="SELECT * FROM registro_certificados WHERE id_solicitud='$id_solicitud' LIMIT 1";
			$sql_certificados=$conexion_mysqli->query($cons_certificado)or die($conexion_mysqli->error);
				$D_certificado=$sql_certificados->fetch_assoc();
					$CODIGO_GENERACION=$D_certificado["codigo_generacion"];
					$array_fecha_hora_creacion_certificado=explode(" ",$D_certificado["fecha_hora"]);
					if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>CODIGO: $CODIGO_GENERACION<br>Fecha: ".$array_fecha_hora_creacion_certificado[0];}
				$fecha=fecha($array_fecha_hora_creacion_certificado[0]);
				$sql_certificados->free();		
		}
		else
		{
			if(DEBUG){ echo"NO EXISTE CERTIFICADO <br>";}
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("certificado de egreso",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ $conexion_mysqli->query($cons_UP_S)or die($conexion_mysqli->error);}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
	
	//-----------DATOS CARRERA------------------//
	$cons_car="SELECT * FROM certificados WHERE id_carrera = '$id_carrera' AND sede ='$sede_alumno'";
	$sql_car=$conexion_mysqli->query($cons_car);
	$DC = $sql_car->fetch_assoc();
    	$decreto=$DC["decreto"];
	$sql_car->free();
	//-----------------------------------------//
	//-----------DATOS FIRMA------------------//
	$firma="";
	$cargo="Director Académico";
	//-----------------------------------------//
	//AÑO INGRESO EGRESO
	list($esEgresado, $semestre_egreso_alumno, $year_egreso_alumno)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
	$year_ingreso_alumno=$yearIngresoCarrera;
	//--------------------------------------------//
	//PROCESO titulacion practica
	$cons_PT="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
	if(DEBUG){ echo"--->PROCESO TITULACION: $cons_PT<br>";}
		$sql_PT=$conexion_mysqli->query($cons_PT)or die($conexion_mysqli->error);
		$DPT=$sql_PT->fetch_assoc();
		$practica_condicion=$DPT["practica_condicion"];	
	$sql_PT->free();	
	
	if($practica_condicion=="aprobada"){$mostrar_msj_practica=true;}
	else{$mostrar_msj_practica=false;}
	//----------------------------------------------//
			////definicion de parametros
			$logo="../../../BAses/Images/logo_largo.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=5;//separacion entre parrafos
			
			$borde=0;
			
			$letra_1=14;
			$autor="ACX";
			$titulo="CERTIFICADO EGRESO";
			$zoom=50;	
			//inicializacion de pdf
			$pdf=new FPDF('P','mm','letter');
			//$pdf->AddFont('Allegro','','ALLEGRO.php');
			//$pdf->AddFont('ChopinScript','','CHOPS.php');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle(strtolower($titulo));
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
	$parrafo_1='El Centro de Formación Técnica, Massachusetts sede '.$sede_alumno.', Certifica:';

	switch($sexo_alumno)
	{
		case"M":
			$parrafo_1b='Que, el Señor: '.utf8_decode($nombre_alumno." ".$apellido_alumno).', Run '.$rut_alumno.', es alumno Egresado de esta Casa de Estudios Superiores en la carrera de '.utf8_decode($carrera_alumno).".";
			$parrafo_2="Que, el señor ".utf8_decode($apellido_alumno)." cursó los cuatro niveles de la carrera antes señalada entre los años ".$year_ingreso_alumno." y ".$year_egreso_alumno.".";
			$parrafo_5='Se extiende el presente certificado a solicitud del interesado para ser presentado a '.utf8_decode($S_observacion).".";
			break;
		case"F":
			$parrafo_1b='Que, la Señorita: '.utf8_decode($nombre_alumno." ".$apellido_alumno).', Run '.$rut_alumno.', es alumna Egresada de esta Casa de Estudios Superiores en la carrera de '.utf8_decode($carrera_alumno).".";
			$parrafo_2="Que, la señorita ".utf8_decode($apellido_alumno)." cursó los cuatro niveles de la carrera antes señalada entre los años ".$year_ingreso_alumno." y ".$year_egreso_alumno.".";
			$parrafo_5='Se extiende el presente certificado a solicitud del la interesada para ser presentado a '.utf8_decode($S_observacion).".";
			break;
	}
	
	if($mostrar_msj_practica)
	{$parrafo_3="Además, realizó su Práctica Laboral, correspondiente al Quinto Nivel. Quedando pendiente el Examen de Título, cabe señalar que esta carrera tiene una duración de dos años y medio (cinco semestres).";}
	else{$parrafo_3="Quedando pendiente su Práctica Laboral y el Examen de Título, cabe señalar que esta carrera tiene una duración de dos años y medio (cinco semestres).";}
	
	$parrafo_4='Que el funcionamiento de nuestro establecimiento como "Centro de Formación Técnica" fue aprobado por Decreto Exento N° 29 de 02 de febrero de 1983, inscrito en el Registro correspondiente bajo el N° 77,';
	
	$parrafo_4.=' la carrera fue aprobada por el Mineduc en '.utf8_decode($decreto).".";
	
	
	
	
	//---------INICIO ESCRITURA---------//
		///logo
		if($ver_logo){$pdf->image($logo,14,10,50,20,'jpg');} //este es el logo
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(195,6,$sede_alumno.", ".$fecha.".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y+20);
		$pdf->SetFont('Times','',24);
		$pdf->Cell(195,15,$titulo,0,1,'C');
		$pdf->Ln(13);
		$pdf->SetFont('Times','',16);
		//parrafo 1
		//$pdf->SetFont('ChopinScript','',$letra_1);
		$pdf->SetFont('Times','',$letra_1);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual );
		$pdf->MultiCell(195,8,$parrafo_1,$borde,'J');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_1b,$borde,'J');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_2,$borde,'J');
		
		if($mostrarParrafoPractica){
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_3,$borde,'J');
		}
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_4,$borde,'J');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_5,$borde,'J');
		$Y_actual=$pdf->GetY();
		$x_actual=$pdf->GetX();
		
		$pdf->SetFont('Times','',16);
		$pdf->SetY(230);
		
		$pdf->SetX($x_actual + 125);
		//$pdf->MultiCell(70,6,$firma,$borde,"C");
		//$pdf->SetX($x_actual + 125);
		//$pdf->MultiCell(70,6,$cargo,$borde,"C");
		//$pdf->SetX($x_actual + 125);
		$pdf->MultiCell(70,6,$institucion,$borde,"C");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		
		$infoQr="Certificado de Egreso de CFT Massashusetts Sede: $sede_alumno Fecha Generacion: $fecha_actual Rut: Alumno: $rut_alumno Carrera Alumno: $carrera_alumno Periodo $semestre_actual - $year_actual Codigo. $CODIGO_GENERACION URL VALIDACION: http://intranet.cftmassachusetts.cl/serviciosExternos/validarCertificado.php?codigo=".base64_encode($CODIGO_GENERACION)."&id_solicitud=".base64_encode($id_solicitud);
		$imgQR='http://intranet.cftmassachusetts.cl/libreria_publica/phpqrcode/ImagenQRv2.php?qr_info='.base64_encode($infoQr);
		if($verQr){$pdf->image($imgQR,14,205,50,50,'png');}
		
		$pdf->Text($x_actual+5,260,"*Cod.".$CODIGO_GENERACION."*");
		
		/////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificado Egreso X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		$conexion_mysqli->close();
		$pdf->Output();
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////

?> 