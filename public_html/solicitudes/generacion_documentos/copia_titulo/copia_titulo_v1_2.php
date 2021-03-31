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
		$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
		$apellido_alumno=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
		$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
		
		$notaFinalTitulo=4.5;
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
			$cons_certificado="SELECT * FROM registro_certificados WHERE id_solicitud='$id_solicitud' LIMIT 1";
			$sql_certificados=mysql_query($cons_certificado)or die(mysql_error());
				$D_certificado=mysql_fetch_assoc($sql_certificados);
					$CODIGO_GENERACION=$D_certificado["codigo_generacion"];
					$array_fecha_hora_creacion_certificado=explode(" ",$D_certificado["fecha_hora"]);
				mysql_free_result($sql_certificados);	
				if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>CODIGO: $CODIGO_GENERACION<br>Fecha: ".$array_fecha_hora_creacion_certificado[0];}
				$fecha=fecha($array_fecha_hora_creacion_certificado[0]);
		}
		else
		{
			if(DEBUG){ echo"NO EXISTE CERTIFICADO <br>";}
			$CODIGO_GENERACION=REGISTRAR_CERTIFICADO("certificado titulo en tramite",$id_alumno, $rut_alumno, $id_carrera, $carrera_alumno, $sede_alumno, $id_solicitud);
			$fecha=fecha();
			
			//marco solicitud como generada
		$cons_UP_S="UPDATE solicitudes SET tipo_creador='$privilegio', id_creador='$id_usuario_actual', fecha_hora_creacion='$fecha_hora_actual', estado='generada' WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"$cons_UP_S<br>";}
		else{ mysql_query($cons_UP_S)or die(mysql_error());}
	//-**-///////////////////////////////////////////////////////////////////////////////***/-//
		}
		
	
	//-----------DATOS Proceso Titulacion------------------//
	$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno'";
	$sql_pt=mysql_query($cons_pt)or die(mysql_error());
	$num_reg=mysql_num_rows($sql_pt);
		$Dpt=mysql_fetch_assoc($sql_pt);
			$nombre_titulo=$Dpt["nombre_titulo"];
			$fecha_examen=$Dpt["examen_fecha"];

		
		if(DEBUG){echo"$cons_pt<br>tiene registro Proceso titulacion: $num_reg<br>---->Nombre titulo: $nombre_titulo  Fecha Examen: $fecha_examen<br>";}
	mysql_free_result($sql_pt);	
	//----------------------------------------------------//
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
				$firma="Jaime Auladell Aldana";
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
			$pdf->Image("../../../BAses/Images/logoDefinitivoV1.jpg",15,15,35,25,"JPG");
			
			$pdf->Image("../../../BAses/Images/logoDefinitivoV1.jpg",30,75,150,120,"JPG");
			
////////////////////
		////////////////////////////
			$parrafo_1=utf8_decode("El Director Academico del C.F.T. Massachusetts $sede_alumno");
		
		$parrafo_2="certifica que se confirio el titulo de: $nombre_titulo";
		
		$parrafo_3="A Don(a): $nombre_alumno $apellido_alumno";
		
		$parrafo_4="R.U.N.: $rut_alumno";
		
		$parrafo_5="Dicho Titulo fue conferido por esta Casa de Estudios segun consta en acta de de fecha $fecha_examen bajo el Registro N. $num_reg";
		
		$parrafo_6="Siendo el Alumno antes señalado aporbado con Nota final de titulo";
		$parrafo_7=num_letra($notaFinalTitulo,true)." ($notaFinalTitulo)";
		
		///escritura de datos
		///logo
		//$pdf->image($logo,14,1,30,24,'jpg'); //este es el logo
		
		//titulo
		$pdf->SetFont('Times','',12);
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','',24);
		$pdf->Cell(195,15,$titulo,$borde,1,'C');
		//parrafo 1
		$Y_actual=$pdf->GetY();
		$pdf->SetFont('Times','',16);
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->Cell(15,8,"",$borde);
		$pdf->MultiCell(180,8,$parrafo_1,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
	
		$pdf->MultiCell(195,8,$parrafo_2,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_3,$borde);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_4,$borde);
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_5,$borde);
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_6,$borde);
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,8,$parrafo_7,$borde);
		
	
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->MultiCell(195,6,$sede_alumno.", ".fecha($fecha_examen).".-",$borde,'R');
		
		
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->Cell(97,6,"Director Academico",$borde,0,'C');
		$pdf->Cell(97,6,"",$borde,1,'C');
		
		$pdf->Cell(97,6,$firma,$borde,0,'C');
		$pdf->Cell(97,6,"Firma",$borde,1,'C');
		
		
		
		$pdf->Text($x_actual,250,"*Cod.".$CODIGO_GENERACION."*");
		
		 /////Registro evento///
		 include("../../../../funciones/VX.php");
		 $evento="Generacion Certificado Titulo en tramite X solicitud: (".$id_solicitud.") para Alumno:(".$id_alumno.")";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		mysql_close($conexion);
		$pdf->Output();
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}

/////////////////////////////////

?> 