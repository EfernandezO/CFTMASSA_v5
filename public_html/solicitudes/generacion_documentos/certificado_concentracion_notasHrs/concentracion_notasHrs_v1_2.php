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
	$ver_logo=false;
	$ver_firma=true;
//////////////////////////////////////////////////////	
$tipoUsuario=$_SESSION["USUARIO"]["tipo"];
$continuar=true;
$verQr=true;
if($continuar)
{
			require("../../../../funciones/conexion_v2.php");
			include("../../../../funciones/funcion.php");
			require('../../../libreria_publica/fpdf/mc_table.php');
			require("../../../../funciones/funciones_sistema.php");
			require("../../../../funciones/class_ALUMNO.php");
			
		//semestre año actual
		$year_actual=date("Y");
		$mes_actual=date("m");
		
		if($mes_actual>=8)
		{ $semestre_label="Segundo Semestre"; $semestre_actual=2;}
		else
		{ $semestre_label="Primer Semestre"; $semestre_actual=1;}	
			
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
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
			$sql_s=$conexion_mysqli->query($cons_s)or die($conexion_mysqli->error);
				$Ds=$sql_s->fetch_assoc();
				$observacion=$Ds["observacion"];
			$sql_s->free();	
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
	///---------------------------------------------------///
		switch($tipoUsuario){
			case"alumno":
				$ALUMNO=new ALUMNO($S_idAlumno);
				$id_alumno=$S_idAlumno;
				$id_carrera=$ALUMNO->getUltimaIdCarreraMat();
				$nombre_alumno=$ALUMNO->getNombre();
				$apellido_alumno=$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
				$carrera_alumno=NOMBRE_CARRERA($id_carrera);
				$sede_alumno=$ALUMNO->getSedeActual();
				$rut_alumno=$ALUMNO->getRut();
				$yearIngreso=$ALUMNO->getUltimoYearIngresoMat();
				$situacionAcademica=$_SESSION["USUARIO"]["situacion"];
				$ver_logo=true;
				break;
			case"funcionario":
				$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
				$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
				$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
				$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
				$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
				$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
				$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
				$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
				$yearIngreso=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
				$situacionAcademica=$_SESSION["SELECTOR_ALUMNO"]["situacion"];
				$ver_logo=true;
				break;
		}
		
	
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
			$cons_certificado="SELECT * FROM registro_certificados WHERE id_solicitud='$id_solicitud' LIMIT 1";
			$sql_certificados=$conexion_mysqli->query($cons_certificado)or die($conexion_mysqli->error);
				$D_certificado=$sql_certificados->fetch_assoc();
					$CODIGO_GENERACION=$D_certificado["codigo_generacion"];
					$array_fecha_hora_creacion_certificado=explode(" ",$D_certificado["fecha_hora"]);
				$sql_certificados->free();	
				if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>CODIGO: $CODIGO_GENERACION<br>Fecha: ".$array_fecha_hora_creacion_certificado[0];}
				$fecha=fecha($array_fecha_hora_creacion_certificado[0]);
		}
		else
		{
			if(DEBUG){ echo"NO EXISTE CERTIFICADO <br>";}
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("concentracion de notas",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ $conexion_mysqli->query($cons_UP_S)or die("UP Solicitud: ".$conexion_mysqli->error);}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
		
		$notaFinalAsignaturas=PROMEDIO_FINAL_ASIGNATURAS($id_alumno, $id_carrera, $yearIngreso);
	
	//-----------DATOS FIRMA------------------//
		$cargo="Director Academico";
	//-----------------------------------------//
	
			////definicion de parametros
			$logo="../../../BAses/Images/logo_cft.jpg";
			$logo="../../../BAses/Images/logo_largo.jpg";
			$firmaIMG="../../../BAses/unicas/JPJP.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=15;//separacion entre parrafos
			
			
			$borde=0;
			
			$letra_1=14;
			$autor="ACX";
			$titulo="Concentracion de Notas con Horas";
			$zoom=50;	
			$hoja_oficio[0]=217;
			$hoja_oficio[1]=330;
			//inicializacion de pdf
			$pdf=new PDF_MC_Table('P','mm',"Letter");
			//$pdf->AddFont('Allegro','','ALLEGRO.php');
			//$pdf->AddFont('ChopinScript','','CHOPS.php');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
			
			$pdf->SetAutoPageBreak(false, 10);
////////////////////
			
			$parrafo_1="El $institucion sede $sede_alumno, reconocido por el Ministerio de Educación el 3 de febrero de 1983, según Decreto Exento N° 29, certifica que el (la) señor(ita) ".utf8_decode(ucwords(strtolower($nombre_alumno." ".$apellido_alumno))).", rut.: $rut_alumno, alumno de la carrera ".utf8_decode($carrera_alumno)." - $yearIngreso, obtuvo las siguientes calificaciones:";
		
		
		//titulo
		if($ver_logo){$pdf->image($logo,10,10,50,18,'jpg');} //este es el logo
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(195,5,$fecha,$borde, 1,'R');
		$pdf->ln(15);
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(195,6,$titulo,$borde, 1,'C');
		$pdf->ln(10);
		$pdf->SetFont('Arial','',12);
		//parrafo 1
		$pdf->MultiCell(195,6,$parrafo_1,$borde,"J");
		$pdf->ln();
		/////
		//notas encabezado
		//tabla
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(15,5,"Cod",1,0,'C');
			$pdf->Cell(120,5,"Asignatura",1,0,'L');
			$pdf->Cell(15,5,"Nivel",1,0,'C');
			$pdf->Cell(25,5,"Periodo",1,0,'C');
			$pdf->Cell(20,5,"Nota",1,1,'R');
			
			$pdf->SetFont('Arial','',10);
		
		$pdf->SetFont('Times','',14);
		$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngreso' AND ramo<>'' ORDER by cod";
		$sql_N=$conexion_mysqli->query($cons_N)or die("Notas ".$conexion_mysqli->error);
		$num_notas=$sql_N->num_rows;
		if($num_notas>0)
		{
			 $nivel_old=0;
			   $primera_vuelta=true;
			   $cuenta_notas=0;
			   $acumula_nota=0;
			   $pdf->SetFillColor(216,216,216);
			while($N=$sql_N->fetch_assoc())
			{
				$N_id=$N["id"];
				$N_cod=$N["cod"];
				$N_nivel=$N["nivel"];
				$N_ramo=$N["ramo"];
				$N_nota=$N["nota"];
				$N_semestre=$N["semestre"];
				$N_year=$N["ano"];
				$N_condicion=$N["condicion"];
				$pdf->SetFont('Arial','',8);
				if($primera_vuelta){ $primera_vuelta=false;}
				else
				{
					if($N_nivel!=$nivel_old)
					{
						
						$pdf->SetFont('Arial','B',10);
						if($cuenta_notas>0){$promedio=($acumula_nota/$cuenta_notas);}
						else{ $promedio=0;}
						$pdf->Cell(175,5,"Promedio",1,0,'L', true);
						$pdf->Cell(20,5,number_format($promedio,1,",","."),1,1,'R',true);
						$cuenta_notas=0;
						$acumula_nota=0;
						$pdf->SetFont('Arial','',8);
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
				
				/*
				$pdf->SetAligns(array("C","L","C","C","R"));
				$pdf->SetWidths(array(15,120,15,25,20));
				$pdf->Row(array($N_cod,utf8_decode($N_ramo),$N_nivel, "$N_semestre - $N_year", $N_nota));
				*/
				
				
				$pdf->Cell(15,4,$N_cod,1,0,'C');
				$pdf->Cell(120,4,utf8_decode($N_ramo),1,0,'L');
				$pdf->Cell(15,4,$N_nivel,1,0,'C');
				$pdf->Cell(25,4,"$N_semestre - $N_year",1,0,'C');
				$pdf->Cell(20,4,$N_nota,1,1,'R');
				
			}
			$nivel_old=$N_nivel;
				
			}
			
			$pdf->SetFont('Arial','B',10);
			if($cuenta_notas>0){$promedio=($acumula_nota/$cuenta_notas);}
			else{ $promedio=0;}
			$pdf->Cell(175,5,"Promedio",1,0,'L', true);
			$pdf->Cell(20,5,number_format($promedio,1,",","."),1,1,'R',true);
			$cuenta_notas=0;
			$acumula_nota=0;
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(175,5,"Promedio Final ",1,0,'L', true);
			$pdf->Cell(20,5,number_format($notaFinalAsignaturas,1,",","."),1,1,'R',true);
			$cuenta_notas=0;
			$acumula_nota=0;
			$pdf->SetFont('Arial','',10);
						
			
		}
		else
		{ 	$pdf->Cell(195,6,"Sin Notas Registradas...",1,1,"C");}
		$sql_N->free();
		
		$infoQr="Certificado Concentracion de Notas Con HORAS de CFT Massashusetts Sede: $sede_alumno Fecha Generacion: $fecha_actual Rut: Alumno: $rut_alumno Carrera Alumno: $carrera_alumno Periodo $semestre_actual - $year_actual Codigo. $CODIGO_GENERACION URL VALIDACION: http://intranet.cftmassachusetts.cl/serviciosExternos/validarCertificado.php?codigo=".base64_encode($CODIGO_GENERACION)."&id_solicitud=".base64_encode($id_solicitud);
		$imgQR='http://intranet.cftmassachusetts.cl/libreria_publica/phpqrcode/ImagenQRv2.php?qr_info='.base64_encode($infoQr);
		if($verQr){$pdf->image($imgQR,10,220,50,50,'png');}
		$pdf->Text(10,271,"*Cod.".$CODIGO_GENERACION."*");
		
		 $pdf->SetXY(140,240);
		 $pdf->Multicell(60,6,"C.F.T. Massachusetts",0,"C");
		 if($ver_firma){$pdf->image($firmaIMG,140,220,50,18,'jpg');}
	
		
		$Y_actual=$pdf->GetY();
		$x_actual=$pdf->GetX();
		
		///inicio segunda hota
		
		$mostrarHoja2=false;
		if($situacionAcademica=="T"){$mostrarHoja2=true;}
		
		if($mostrarHoja2){
			
			$parrafo_12="El $institucion sede $sede_alumno, reconocido por el Ministerio de Educación el 3 de febrero de 1983, según Decreto Exento N° 29, certifica que el (la) señor(ita) ".utf8_decode($nombre_alumno." ".$apellido_alumno).", rut.: $rut_alumno, alumno de la carrera ".utf8_decode($carrera_alumno)." - $yearIngreso, obtuvo las siguientes calificaciones en su proceso de titulacion:";
			
			$pdf->AddPage();
			if($ver_logo){$pdf->image($logo,10,10,50,18,'jpg');} //este es el logo
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(195,5,$fecha,$borde, 1,'R');
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(195,6,$titulo,$borde, 1,'C');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(195,6,"(Anexo Titulados)",$borde, 1,'C');
			$pdf->ln(10);
			$pdf->SetFont('Arial','',12);
			//parrafo 1
			$pdf->MultiCell(195,6,$parrafo_12,$borde,"J");
			$pdf->ln();
			////
			 $cons_pp="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngreso'";
		 if(DEBUG){ echo"-> $cons_pp<br>";}
		
		 $sql_pp=$conexion_mysqli->query($cons_pp)or die($conexion_mysqli->error);
		 $num_regpp=$sql_pp->num_rows;
	
			 if($num_regpp>0)
			 {
				$DPP=$sql_pp->fetch_assoc();
					$cod_proceso_practica=$DPP["id"];
					$practica_condicion=$DPP["practica_condicion"];
					$practica_fecha_inicio=$DPP["practica_fecha_inicio"];
					$practica_lugar=$DPP["practica_lugar"];
					$informe_fecha_recepcion=$DPP["informe_fecha_recepcion"];
					$examen_condicion=$DPP["examen_condicion"];
					$examen_fecha=$DPP["examen_fecha"];
					$titulo_fecha_emision=$DPP["titulo_fecha_emision"];
					
					$notaInformePractica=$DPP["notaInformePractica"];
					$notaEvaluacionEmpresa=$DPP["notaEvaluacionEmpresa"];
					$notaSupervisionPractica=$DPP["notaSupervisionPractica"];
					$notaExamenTitulo=$DPP["notaExamen"];
					
					$notaFinalPractica=$notaInformePractica*0.3+$notaEvaluacionEmpresa*0.4+$notaSupervisionPractica*0.3;
					
					$fecha_generacion=fecha_format($DPP["fecha_generacion"]);
					$id_user=$DPP["cod_user"];
			 }
			$sql_pp->free();
			
			
			$notaFinalTitulo=NOTA_FINAL_TITULO($id_alumno, $id_carrera, $yearIngreso);
			
			
			//tabla
			$parrafo_13="Notas obtenidas en la practica profesional, realizada en '$practica_lugar', con fecha de inicio: '$practica_fecha_inicio', son las siguientes:";
			
			$parrafo_14="La nota obtenida en el examen de titulo, realizado el '$examen_fecha', fue un '$notaExamenTitulo'.";
			$parrafo_15="En resumen de las calificaciones finales obtenidas son las siguientes:";
			
			
			$pdf->MultiCell(195,6,$parrafo_13,$borde,"J");
			$pdf->ln();
			
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Proceso",1,0,'L');
			$pdf->Cell(35,5,"Ponderacion",1,0,'L');
			$pdf->Cell(35,5,"Nota",1,1,'L');
			
			$pdf->SetFont('Arial','',10);
	
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Supervicion de Practica",1,0,'L');
			$pdf->Cell(35,5,"30%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaSupervisionPractica,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Evaluacion de Empresa",1,0,'L');
			$pdf->Cell(35,5,"40%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaEvaluacionEmpresa,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Informe Practica",1,0,'L');
			$pdf->Cell(35,5,"30%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaInformePractica,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"*",1,0,'L');
			$pdf->Cell(110,5,"Nota Final Practica",1,0,'L');
			$pdf->Cell(35,5,"",1,0,'C');
			$pdf->Cell(35,5,number_format($notaFinalPractica,1,",","."),1,1,'R');
			
			$pdf->ln();
			
			$pdf->SetFont('Arial','',12);
			$pdf->MultiCell(195,6,$parrafo_14,$borde,"J");
			$pdf->ln();
			
			$pdf->MultiCell(195,6,$parrafo_15,$borde,"J");
			$pdf->ln();
			
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Proceso",1,0,'L');
			$pdf->Cell(35,5,"Ponderacion",1,0,'L');
			$pdf->Cell(35,5,"Nota",1,1,'L');
			
			$pdf->SetFont('Arial','',10);
	
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Promedio Notas Asignaturas",1,0,'L');
			$pdf->Cell(35,5,"30%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaFinalAsignaturas,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Nota Final de Practica",1,0,'L');
			$pdf->Cell(35,5,"35%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaFinalPractica,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"-",1,0,'L');
			$pdf->Cell(110,5,"Nota Examen de Titulo",1,0,'L');
			$pdf->Cell(35,5,"35%",1,0,'C');
			$pdf->Cell(35,5,number_format($notaExamenTitulo,1,",","."),1,1,'R');
			
			$pdf->Cell(15,5,"*",1,0,'L');
			$pdf->Cell(110,5,"Nota Final Titulo",1,0,'L');
			$pdf->Cell(35,5,"",1,0,'C');
			$pdf->Cell(35,5,number_format($notaFinalTitulo,1,",","."),1,1,'R');
			
			
			
		if($verQr){$pdf->image($imgQR,10,220,50,50,'png');}
		$pdf->Text(10,271,"*Cod.".$CODIGO_GENERACION."*");
		
		$pdf->SetXY(140,240);
		$pdf->Multicell(60,6,"C.F.T. Massachusetts",0,"C");
		 if($ver_firma){$pdf->image($firmaIMG,140,220,50,18,'jpg');}

		}
		
		$mostrarHoja3=true;
		if($mostrarHoja3){
			$parrafo_12a="El $institucion sede $sede_alumno, reconocido por el Ministerio de Educación el 3 de febrero de 1983, según Decreto Exento N° 29, certifica que el (la) señor(ita) ".utf8_decode(ucwords(strtolower($nombre_alumno." ".$apellido_alumno))).", rut.: $rut_alumno, alumno de la carrera ".utf8_decode($carrera_alumno)." - $yearIngreso, registra el siguiente grado de avance acádemico.";
			
			$pdf->AddPage();
			if($ver_logo){$pdf->image($logo,10,10,50,18,'jpg');} //este es el logo
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(195,5,$fecha,$borde, 1,'R');
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(195,6,$titulo,$borde, 1,'C');
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(195,6,"(Anexo Numero Horas)",$borde, 1,'C');
			$pdf->ln(10);
			$pdf->SetFont('Arial','',12);
			//parrafo 1
			$pdf->MultiCell(195,6,$parrafo_12a,$borde,"J");
			$pdf->ln();
			
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(15,5,"Cod",1,0,'C');
			$pdf->Cell(120,5,"Asignatura",1,0,'L');
			$pdf->Cell(25,5,"N. de Horas",1,0,'C');
			$pdf->Cell(15,5,"Nivel",1,0,'C');
			$pdf->Cell(20,5,"Estado",1,1,'C');
			$pdf->ln();
			
			$pdf->SetFont('Arial','',10);
		
		$pdf->SetFont('Times','',14);
		$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngreso' AND ramo<>'' ORDER by cod";
		$sql_N=$conexion_mysqli->query($cons_N)or die("Notas ".$conexion_mysqli->error);
		$num_notas=$sql_N->num_rows;
		if($num_notas>0)
		{
			   $nivel_old=1;
			   $primera_vuelta=true;
			   $cuenta_notas=0;
			   $acumula_horas=0;
			   $cuenta_notas_aprobadas=0;
			   $pdf->SetFillColor(216,216,216);
			while($N=$sql_N->fetch_assoc())
			{
				$N_id=$N["id"];
				$N_cod=$N["cod"];
				$N_nivel=$N["nivel"];
				$N_ramo=$N["ramo"];
				$N_nota=$N["nota"];
				$N_semestre=$N["semestre"];
				$N_year=$N["ano"];
				$N_condicion=$N["condicion"];
				$pdf->SetFont('Arial','',8);
				if(DEBUG){echo"codigo de asignatura: $N_cod ";}
				if(empty($N_ramo)or empty($N_nota)){ $mostrar_registro_3=false; if(DEBUG){echo"-- No MOSTRAR<br>";}}
				else{ $mostrar_registro_3=true; if(DEBUG){echo"-- MOSTRAR<br>";}}
			
		
				if($mostrar_registro_3)
				{
					 $cuenta_notas++;
					
					/*
					$pdf->SetAligns(array("C","L","C","C","R"));
					$pdf->SetWidths(array(15,120,15,25,20));
					$pdf->Row(array($N_cod,utf8_decode($N_ramo),$N_nivel, "$N_semestre - $N_year", $N_nota));
					*/
					
					$auxCondicion="Reprobado";
					if($N_nota>=4){$auxCondicion="Aprobado"; $cuenta_notas_aprobadas++;}
					
					if($N_nivel!=$nivel_old){$pdf->ln(4);}else{}
					//$pdf->Cell(20,4,$nivel_old." ".$N_nivel,1,1,'C');
						
					$horasSemestrales=0;
					if(($N_cod>=1)and($N_cod<86)){
						$horasSemestrales=HORAS_PROGRAMA($id_carrera, $N_cod,"semestral");
					}
					$acumula_horas+=$horasSemestrales;
					
					$pdf->Cell(15,4,$N_cod,1,0,'C');
					$pdf->Cell(120,4,utf8_decode($N_ramo),1,0,'L');
					$pdf->Cell(25,4,$horasSemestrales,1,0,'C');
					$pdf->Cell(15,4,$N_nivel,1,0,'C');
					$pdf->Cell(20,4,$auxCondicion,1,1,'C');
					
					$nivel_old=$N_nivel;
				}
				
			}
			$pdf->SetFont('Arial','B',10);
			$pdf->ln();
			$pdf->Cell(15,4,"*",1,0,'C',true);
			$pdf->Cell(120,4,"Total Horas Pedagogicas Cursadas",1,0,'L');
			$pdf->Cell(25,4,$acumula_horas,1,1,'C');
			
			$pdf->ln();
			$pdf->Cell(15,4,"*",1,0,'C',true);
			$pdf->Cell(120,4,"Total Asignaturas Aprobadas / Cursadas",1,0,'L');
			
			$pdf->Cell(25,4,"$cuenta_notas_aprobadas / $cuenta_notas",1,1,'C');
		}
		
			if($verQr){$pdf->image($imgQR,10,220,50,50,'png');}
		$pdf->Text(10,271,"*Cod.".$CODIGO_GENERACION."*");
		
		$pdf->SetXY(140,240);
		$pdf->Multicell(60,6,"C.F.T. Massachusetts",0,"C");
		 if($ver_firma){$pdf->image($firmaIMG,140,220,50,18,'jpg');}
			
		}
		///-----------------------------------------------------------//
		
		
		
		 /////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificado concentracion de notas X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		$conexion_mysqli->close();
		$pdf->Output();
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////

?> 