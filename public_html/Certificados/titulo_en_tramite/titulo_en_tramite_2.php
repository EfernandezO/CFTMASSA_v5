<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->titulo_en_tramite_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
////////////////////////////////////////////////////
	$registrar_certificado=true;
	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))	
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		require("../../../funciones/conexion_v2.php");
			include("../../../funciones/funcion.php");
			require("../../libreria_publica/fpdf/fpdf.php");
			
			////definicion de parametros
			$logo="../../BAses/Images/logoX.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=10;//separacion entre parrafos
			
			
			$borde=0;
			$fecha=fecha();
			$letra_1=14;
			$autor="ACX";
			$titulo="Certificado Título en Trámite";
			$zoom=50;	
			//inicializacion de pdf
			$pdf=new FPDF('P','mm','letter');
			$pdf->AddFont('Allegro','','ALLEGRO.php');
			$pdf->AddFont('ChopinScript','','CHOPS.php');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
			
			$pdf->SetAutoPageBreak(false, 100);
			
			
			///arreglo de txt
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
		$nombre_alumno=utf8_decode(ucwords(strtolower($nombre_alumno)));
		$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
		$apellido_alumno=utf8_decode(ucwords(strtolower($apellido_alumno)));
		$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		if(DEBUG){ var_export($_POST);}
		$array_firma=explode(",",mysql_real_escape_string($_POST["firma"]));
		
		$firma_nombre=ucwords(strtolower($array_firma[0]));
		$firma_nombre=utf8_decode(str_replace("É","é",$firma_nombre));
		$firma_nombre=str_replace("Ñ","ñ",$firma_nombre);
		
		$firma_cargo=utf8_decode($array_firma[1]);
		
		$presentado_a=mysqli_real_escape_string($conexion_mysqli, $_POST["presentado"]);
		$presentado_a=strtolower($presentado_a);
		$fecha_examen=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_examen"]);
		$nombre_titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_titulo"]);
		$nombre_titulo=ucwords(strtolower($nombre_titulo));
		
		if($registrar_certificado)
		{
			$id_certificadoX=REGISTRAR_CERTIFICADO($id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno);
			$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
			$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
			$tipo_certificado="certificado titulo en tramite";
			$cons="SELECT COUNT(id) FROM registro_certificados WHERE rut_alumno='$rut_alumno' AND carrera_alumno='$carrera_alumno' AND tipo_certificado='$tipo_certificado'";
			$sqlXX=$conexion_mysqli->query($cons);
			$C=$sqlXX->fetch_row();
			$numero_certificados=$C[0];
			if(empty($numero_certificados))
			{ $numero_certificados=0;}
			$sqlXX->free();
			if(DEBUG){echo"-->($id_certificadoX _ $numero_certificados)<br>";}
			
			$codigo_certificado=$id_certificadoX."_".$numero_certificados;
		}
		else
		{ $codigo_certificado="xx_xx";}
		
		////////////////////////////
			$parrafo_1="$firma_nombre,$firma_cargo del Centro de Formación Técnica Massachusetts $sede_alumno, quien suscribe certifica:";
		
		$parrafo_2="Que, el alumno(a) ".$nombre_alumno." ".$apellido_alumno.", Rut.: $rut_alumno,  de la carrera ".utf8_decode($carrera_alumno).",  rindió y  aprobó  su Examen  de Título  el día ".fecha_format($fecha_examen);
		
		$parrafo_3="En estos momentos el Título como ".utf8_decode($nombre_titulo)." del señor(ita) $apellido_alumno, se encuentra en proceso de tramitación académica.";
		
		$parrafo_4="Se extiende el presente Certificado a solicitud del interesado(a) para ser presentado en ".utf8_decode($presentado_a);
		
		///escritura de datos
		///logo
		//$pdf->image($logo,14,1,30,24,'jpg'); //este es el logo
		
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(195,6,$sede_alumno.", ".fecha().".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('ALLEGRO','',36);
		$pdf->Cell(195,20,$titulo,$borde,1,'C');
		$pdf->SetFont('Times','',14);
		//parrafo 1
		//$pdf->SetFont('ChopinScript','',$letra_1);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y+15);
		$pdf->MultiCell(195,8,"   ".$parrafo_1,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
	
		$pdf->MultiCell(195,8,"   ".$parrafo_2,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_3,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,"   ".$parrafo_4,$borde);
		$Y_actual=$pdf->GetY();
		$x_actual=$pdf->GetX();
		
		$pdf->SetFont('Times','',14);
		$pdf->SetY(210);
		$pdf->SetX($x_actual + 115);
		$pdf->MultiCell(80,6,$firma_nombre,$borde,"C");
		$pdf->SetX($x_actual + 115);
		$pdf->MultiCell(80,6,$firma_cargo,$borde,"C");
		$pdf->SetX($x_actual + 115);
		$pdf->MultiCell(80,6,"C.F.T. Massachusetts",$borde,"C");
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		
		
		$pdf->Text($x_actual,250,"*Cod.".$codigo_certificado."*");
		

		$conexion_mysqli->close();
		$pdf->Output();
	}
	else{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////
function REGISTRAR_CERTIFICADO($id_alumno, $rut, $id_carrera, $carrera, $sede)
{
	require("../../../funciones/conexion_v2.php");
	$fecha_hora=date("Y-m-d H:i:s");
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$tipo_certificado="certificado titulo en tramite";
	/////////////////////////////////////////////
	$campos="id_alumno, rut_alumno, id_carrera, carrera_alumno, tipo_certificado, fecha_hora, id_user, sede";
	$valores="'$id_alumno', '$rut', '$id_carrera', '$carrera', '$tipo_certificado', '$fecha_hora', '$id_user_activo', '$sede'";
	
	$cons_IN="INSERT INTO registro_certificados($campos) VALUES($valores)";
	$conexion_mysqli->query($cons_IN);
	
	$id_certificado=$conexion_mysqli->insert_id;
	if(DEBUG){echo"----------->$cons_IN<br>";}
	$conexion_mysqli->close();
	return($id_certificado);
}
?> 