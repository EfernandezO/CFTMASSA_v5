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
	$ver_logo=false;
	$ver_firma=true;
	$verQr=true;
	$institucion="C.F.T. Massachusetts";
//////////////////////////////////////////////////////	
//$continuar=true;
$id_usuario_actual=$_SESSION["USUARIO"]["id"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$tipoUsuario=$_SESSION["USUARIO"]["tipo"];
$fecha_hora_actual=date("Y-m-d H:i:s");
if(DEBUG){ echo"fecha hora actual . $fecha_hora_actual<br>tipo usuario: $tipoUsuario<br>";}

if($tipoUsuario=="funcionario"){
if(isset($_SESSION["SELECTOR_ALUMNO"])){if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]==true){$continuar=true;}}
}
if($tipoUsuario=="alumno"){$continuar=true;}

if($continuar)
{
		require("../../../../funciones/conexion_v2.php");
		include("../../../../funciones/funcion.php");
		require("../../../libreria_publica/fpdf/fpdf.php");
		require("../../../../funciones/funciones_sistema.php");
		require("../../../../funciones/class_ALUMNO.php");
			
		
	////-----------------------------------------------/////

		
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
			$yearIngreso=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
			
		}else{
			$ALUMNO=new ALUMNO($S_idAlumno);
			$id_alumno=$S_idAlumno;
			$id_carrera=$ALUMNO->getUltimaIdCarreraMat();
			$nombre_alumno=$ALUMNO->getNombre();
			$apellido_alumno=$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
			$carrera_alumno=NOMBRE_CARRERA($id_carrera);
			$sede_alumno=$ALUMNO->getSedeActual();
			$rut_alumno=$ALUMNO->getRut();
			$yearIngreso=$ALUMNO->getUltimoYearIngresoMat();
			$ver_logo=true;
		}
		
		
			
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
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("titulo en tramite",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada', id_firma='$S_id_firma', observacion='$S_observacion' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ $conexion_mysqli->query($cons_UP_S)or die($conexion_mysqli->error);}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
	
	//-----------DATOS Proceso Titulacion------------------//
	$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' and yearIngresoCarrera='$yearIngreso'";
	$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
	$Dpt=$sql_pt->fetch_assoc();
		$nombre_titulo=$Dpt["nombre_titulo"];
		$fecha_examen=$Dpt["examen_fecha"];
		if(DEBUG){echo"$cons_pt<br>tiene registro Proceso titulacion: $num_reg<br>---->Nombre titulo: $nombre_titulo  Fecha Examen: $fecha_examen<br>";}
		$sql_pt->free();	
	//----------------------------------------------------//
	//-----------DATOS CARRERA------------------//
	$cons_car="SELECT * FROM certificados WHERE id_carrera = '$id_carrera' AND sede ='$sede_alumno'";
	$sql_car=$conexion_mysqli->query($cons_car);
	$DC = $sql_car->fetch_assoc();
    	$decreto=$DC["decreto"];
	$sql_car->free();
	//$S_id_firma=411;
	//-----------------------------------------//
	if(DEBUG){echo"<br>id firma a consultar: $S_id_firma<br>";}
	if($S_id_firma==1){$S_id_firma=417;} //if($S_id_firma==1){$nombre_firma="C.F.T. Massachusetts Internet";}
	$cargo_firma=CARGO_FUNCIONARIO($S_id_firma);
	$nombre_firma=NOMBRE_PERSONAL($S_id_firma);
	
	//-----------------------------------------//
	
			////definicion de parametros
			$logo="../../../BAses/Images/logo_cft.jpg";
			$logo2="../../../BAses/Images/logo_largo.jpg";
			$firmaIMG="../../../BAses/unicas/JPJP.jpg";
			
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=10;//separacion entre parrafos
			
			
			$borde=0;
			
			$letra_1=14;
			$autor="ACX";
			$titulo="Certificado Titulo en Tramite";
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
		{ $semestre_label="Segundo Semestre"; $semestre_actual=2;}
		else
		{ $semestre_label="Primer Semestre"; $semestre_actual=1;}
		//---------------------------------------------------------//
	
		
		
	//--parrafos--//
	$parrafo_1=utf8_decode('Quien suscribe').', '.$cargo_firma.' del Centro de Formación Técnica Massachusetts, Certifica:';

	$parrafo_1b='Que, el(la) Señor(ita): '.utf8_decode($nombre_alumno)." ".utf8_decode($apellido_alumno).', Run '.$rut_alumno.' rindió y aprobó el examen de titulo de la carrera "'.utf8_decode(NOMBRE_CARRERA($id_carrera)).'", el dia '.fecha($fecha_examen).'.';
	
	//$parrafo_1b='Que, el(la) Señor(ita): '.$nombre_alumno." ".$apellido_alumno.' es alumno(a) regular de la carrera de '.$carrera_alumno.", nivel 2, este corresponde al Segundo Semestre año lectivo 2011.";
	
	$parrafo_2='Actualmente su titulo de "'.utf8_decode($nombre_titulo).'", se encuentra en proceso de tramitación académica.';
	
	$parrafo_3='Se extiende el presente certificado a solicitud del(la) interesado(a) para ser presentado a '.utf8_decode($S_observacion).".";
	
	
	//---------INICIO ESCRITURA---------//
		///logo
		if($ver_logo){$pdf->image($logo2,14,10,50,20,'jpg');} //este es el logo
		
	
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(195,6,$sede_alumno.", ".$fecha.".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','',24);
		$pdf->Cell(195,15,$titulo,$borde,1,'C');
		$pdf->SetFont('Times','',16);
		//parrafo 1
		//$pdf->SetFont('ChopinScript','',$letra_1);
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
		$Y_actual=$pdf->GetY();
		$x_actual=$pdf->GetX();
		
		
		$infoQr="Certificado titulo en tramite de CFT Massashusetts Sede: $sede_alumno Fecha Generacion: $fecha_actual Rut: Alumno: $rut_alumno Carrera Alumno: $carrera_alumno Periodo $semestre_actual - $year_actual Codigo. $CODIGO_GENERACION URL VALIDACION: http://intranet.cftmassachusetts.cl/serviciosExternos/validarCertificado.php?codigo=".base64_encode($CODIGO_GENERACION)."&id_solicitud=".base64_encode($id_solicitud);
		$imgQR='http://intranet.cftmassachusetts.cl/libreria_publica/phpqrcode/ImagenQRv2.php?qr_info='.base64_encode($infoQr);
		if($verQr){$pdf->image($imgQR,14,205,50,50,'png');}
		
		$pdf->SetFont('Times','',16);
		$pdf->SetY(225);
		$pdf->SetX($x_actual + 110);
		$pdf->MultiCell(85,6,utf8_decode($nombre_firma),$borde,"C");
		$pdf->SetX($x_actual + 110);
		$pdf->MultiCell(85,6,$cargo_firma,$borde,"C");
		$pdf->SetX($x_actual + 110);
		$pdf->MultiCell(85,6,$institucion,$borde,"C");
		if($ver_firma){$pdf->image($firmaIMG,100,188,100,37,'jpg');}
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		
		
		$pdf->Text(14,260,"*Cod.".$CODIGO_GENERACION."*");
		
		/////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificadotitulo en tramite X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		$conexion_mysqli->close();
		$pdf->Output();
}
else
{ if(DEBUG){ echo"No se puede continuar<br>";}}

/////////////////////////////////

?> 