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
			require("../../../../funciones/conexion_v2.php");
			include("../../../../funciones/funcion.php");
			include("../../../libreria_publica/fpdf/fpdf.php");
			include("../../../../funciones/funciones_sistema.php");
			
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
		$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
		$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
		$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
		
	
	////-----------------------------------------------/////
		if($_POST)
		{
			if(DEBUG){ var_dump($_POST);}
			$id_solicitud=$_POST["id_solicitud"];
			$observacion=$_POST["presentado"];
		}
		elseif($_GET)
		{
			if(DEBUG){ echo "Hay GET<br>";var_dump($_GET);}
			$id_solicitud=mysqli_real_escape_string($conexion_mysqli, $_GET["id_solicitud"]);
			$cons_s="SELECT observacion FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
			$sql_s=$conexion_mysqli->query($cons_s)or die($conexion_mysqli->error);
				$Ds=$sql_s->fetch_assoc();
				$observacion=$Ds["observacion"];
			$sql_s->free();	
		}
	///---------------------------------------------------///
		///busco si ya se ha generado certificado y obtengo CODIGO GENERACION
		if(DEBUG){ echo"<br>Busco registro de certificado...<br>";}
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
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("certificado titulo en tramite",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
			if(DEBUG){ echo"marcar Solicitud como generada...<br>";}
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ $conexion_mysqli->query($cons_UP_S)or die($conexion_mysqli->error);}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
	
	//-----------DATOS Proceso Titulacion------------------//
	$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno'";
	$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
	$num_reg=$sql_pt->num_rows;
		$Dpt=$sql_pt->fetch_assoc();
			$nombre_titulo=$Dpt["nombre_titulo"];
			$fecha_examen=$Dpt["examen_fecha"];
			$numeroRegistroTitulo=$Dpt["numero_inscripcion_titulo"];
		if(DEBUG){echo"$cons_pt<br>tiene registro Proceso titulacion: $num_reg<br>---->Nombre titulo: $nombre_titulo  Fecha Examen: $fecha_examen<br>";}
	$sql_pt->free();	
	//nota Final
	$notaFinalTitulo=number_format(NOTA_FINAL_TITULO($id_alumno, $id_carrera, $yearIngresoCarrera),1,",",".");
	//----------------------------------------------------//
	//-----------DATOS CARRERA DECRETO -----------------//
	$cons_car="SELECT * FROM certificados WHERE id_carrera = '$id_carrera' AND sede ='$sede_alumno'";
	$sql_car=$conexion_mysqli->query($cons_car)or die($conexion_mysqli->error);
	$DC = $sql_car->fetch_assoc();
    	$decreto=$DC["decreto"];
	$sql_car->free();
	//-----------------------------------------//
	//-----------DATOS FIRMA------------------//
		switch($sede_alumno)
		{
			case"Talca":
				$firma="Renato Celis Saavedra";
				break;
			case"Linares":
				$firma="Paola Maureira Sanchez";
				break;
		}
		$cargo="Director Académico";
	//-----------------------------------------//
	
			////definicion de parametros
			$logo="../../../BAses/Images/logoX.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=10;//separacion entre parrafos
			
			
			$borde=1;
			
			$letra_1=14;
			$autor="ACX";
			$titulo="CERTIFICADO DE TITULO";
			$zoom=20;	
			//inicializacion de pdf
			$pdf=new FPDF('P','mm','letter');
			//$pdf->AddFont('Allegro','','ALLEGRO.php');
			//$pdf->AddFont('ChopinScript','','CHOPS.php');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
			
			$pdf->SetAutoPageBreak(false, 100);
			//imagenes
			$pdf->Image("../../../BAses/Images/logoDefinitivoV1.jpg",15,15,35,25,"JPG");
			//$pdf->Image("../../../BAses/Images/logoDefinitivoV1.jpg",30,75,150,120,"JPG");
			
////////////////////
		////////////////////////////
			$parrafo_1=utf8_decode("El Director Academico del C.F.T. Massachusetts sede $sede_alumno");
		
		$parrafo_2="Certifica que se confirio el titulo de: $nombre_titulo";
		
		$parrafo_3="A Don(a): $nombre_alumno $apellido_alumno";
		
		$parrafo_4="R.U.N.: $rut_alumno";
		
		$parrafo_5="Dicho Titulo fue conferido por esta Casa de Estudios segun consta en acta  de fecha $fecha_examen bajo el Registro N. $numeroRegistroTitulo";
		
		$parrafo_6="Siendo el Alumno antes señalado aprobado con Nota final de titulo";
		$parrafo_7=num_letra($notaFinalTitulo,true)." ($notaFinalTitulo)";
		
		///escritura de datos
		///logo
		//$pdf->image($logo,14,1,30,24,'jpg'); //este es el logo
		
		//titulo
		$pdf->SetFont('Times','',12);
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y+20);
		$pdf->SetFont('Times','',24);
		$pdf->Cell(195,15,$titulo,$borde,1,'C');
		//parrafo 1
		$Y_actual=$pdf->GetY();
		$pdf->SetFont('Times','',16);
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->Cell(15,8,"",$borde);
		$pdf->MultiCell(180,8,$parrafo_1,$borde,"J");
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
	
		$pdf->MultiCell(195,8,$parrafo_2,$borde,"J");
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_3,$borde,"J");
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_4,$borde,"J");
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_5,$borde,"J");
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_6,$borde,"J");
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_7,$borde,"J");
		
	
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,6,$sede_alumno.", ".fecha($fecha_examen).".-",$borde,'R');
		
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->Cell(97,6,"Director Academico",$borde,0,'C');
		$pdf->Cell(97,6,"",$borde,1,'C');
		
		$pdf->Cell(97,6,$firma,$borde,0,'C');
		$pdf->Cell(97,6,"Firma",$borde,1,'C');
		
		
		
		$pdf->Text($x_actual+15,250,"*Cod.".$CODIGO_GENERACION."*");
		
		 /////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificado Titulo en tramite X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		$conexion_mysqli->close();
		$pdf->Output();
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////

?> 